<?php

namespace App\Http\Controllers\AdminPanel;

use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookamenities;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use App\Traits\FirebaseNotificationTrait;

class BookedamenitiesController extends Controller
{

    use FirebaseNotificationTrait;

public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Bookamenities::select(
                    'bookamenities.id',
                    'bookamenities.date',
                    'bookamenities.status',
                    'users.name as user_name',
                    'amenities.amenities_name'
                )
                ->join('users', 'users.id', '=', DB::raw('CAST(bookamenities.user_id AS UNSIGNED)'))
                ->join('amenities', 'amenities.id', '=', DB::raw('CAST(bookamenities.amenities_id AS UNSIGNED)'))
                ->orderBy('bookamenities.created_at', 'desc') // ✅ FIX HERE
                ->get();

            return DataTables::of($data)->make(true);
        }

        return view('admin_panel.admin.bookamenities');
    }

    public function updatestatus(Request $request)
    {
        $amenity = Bookamenities::find($request->id);

        if ($amenity) {
            $amenity->status = $request->status;
            $amenity->save();

                  $user = User::where('id',$amenity->user_id)->first();
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
        
            $this->sendFirebaseStaffNotification($fcmToken, $title, $body);
            }
            return response()->json(['success' => true]);
        }

   
        return response()->json(['error' => 'Record not found'], 404);
    }
}
