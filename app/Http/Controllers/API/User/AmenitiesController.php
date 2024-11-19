<?php
namespace App\Http\Controllers\API\User;
use App\Models\Bookamenities;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FirebaseNotificationTrait;

class AmenitiesController extends Controller
{
    use FirebaseNotificationTrait;

    public function requestamenitiesbooking(Request $request)
    {
        $data = Bookamenities::create([
            'user_id' => $request->user_id,
            'amenities_id' => $request->amenities_id,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description
        ]);

        $token = User::where('user_type',0)->first();


       
            if($token->fcm_token !=null)
            {
                $fcmToken = $token->fcm_token;
                $title = "🛎️ New Amenity Booking Request!";
                $body = "📋 A new request for booking amenities has been submitted. Please review the details and take action. ✅ Approve or ❌ Disapprove the request now.";
                
                return   $this->sendFirebaseNotification($fcmToken, $title, $body,null);
            }
        
  
        
        return response([
            'message' => 'Amenities Booked Successfully..!',
            'data' => $data,    
            'statusCode' => 200],200);
    }
    public function cancelbooking(Request $request)
    {
       $data =  Bookamenities::find($request->id);

       if($data)
       {
            $data->status = 3;
            $data->save();

            return response([
                'message' => 'Amenities canceled Successfully..!',
                'data' => $data,    
                'statusCode' => 200],200);
       }

       return response([
        'message' => 'No  Amenities Found..!',
        'data' => $data,    
        'statusCode' => 400],400);
    }
    
}
