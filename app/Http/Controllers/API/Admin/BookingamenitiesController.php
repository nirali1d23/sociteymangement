<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookamenities;
use App\Models\User;
use App\Traits\FirebaseNotificationTrait;

class BookingamenitiesController extends Controller
{
    use FirebaseNotificationTrait;

    public function display(Request $request)
    {
        $query = Bookamenities::with('amenity')->with('user')->orderBy('created_at', 'desc');

$data = $query->get()->map(function ($item) {
    if ($item->amenity && $item->amenity->image) {
        
        if (!filter_var($item->amenity->image, FILTER_VALIDATE_URL)) {
            $item->amenity->image = url('image/' . $item->amenity->image);
        }
    }
    return $item;
});

         return response( [
            'message' => 'Bookeaemenies Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200);
        
    }
    public function changestatusbookaementies(Request $request)
    {
          $data = Bookamenities::find($request->bookaemnitites_id);
          if($data!=null)
          {

            $data->status = $request->status;
            $data->save();

            $user = User::where('id',$data->user_id)->first();
            $fcmToken = $user->fcm_token;
            if($fcmToken)
            {
              
                if($request->status == '1')
                {

                 $title = " Your Booking Amenities Request is Approved! 🎉";
                 $body = "Great news! Your request for amenity booking has been approved by our admin team. We hope you enjoy the added comfort and convenience!";
                }

                 else
                 {
                    $title = " Update on Your Booking Amenities Request ❗";
                     $body = "Unfortunately, your request for amenity Booking could not be approved. Please contact our support team for further details or assistance. We're here to help!";
                 }
        
            $this->sendFirebaseNotification($fcmToken, $title, $body);
            }
            return response( [
                'message' => 'status updated Successfully..!',
                'data' => $data,
                'statusCode' => 200
            ],200 );

          }
    }
}
