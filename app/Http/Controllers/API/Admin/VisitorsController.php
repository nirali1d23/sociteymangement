<?php


namespace App\Http\Controllers\API\Admin;
use App\Models\preapproval;
use App\Models\User;
use App\Models\Visitor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FirebaseNotificationTrait;
class VisitorsController extends Controller
{
    use FirebaseNotificationTrait;
    public function prebookingrequestlist(Request $request)
    {

        $data = preapproval::where('status',0)->get();

        return response([
        
            'message' => 'Prebooking Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
         
         ],200);
        
    }
    public function visitorlist(Request $request)
    {
         $data = Visitor::where('status',0)->get();
         return response([        
            'message' => 'visitorlist Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
         ],200);

          
    }
    public function approvalprebooking(Request $request)
    {
        $booking = preapproval::find($request->booking_id);
        if($booking!=null)
        {
             $booking->status =  $request->status;
             $booking->save();
            $user = User::find($booking->user_id);
     
             $fcmToken = $user->fcm_token;
             if($fcmToken)
             {
               
                 if($request->status == '1')
                 {
 
                  $title = "Your Pre-Visitor Booking is Confirmed!✅";
                  $body = "Good news! Your pre-visitor booking for  visitor has been approved by our admin team. We're excited to welcome your visitor!";
                 }
 
                  else
                  {
                     $title = "Update on Your Pre-Visitor Booking Request 🚫";
                      $body = "We regret to inform you that your pre-visitor booking for visitor on could not be approved. For further assistance, please reach out to our support team. We're here to help!";
                  }
         
             $this->sendFirebaseNotification($fcmToken, $title, $body);
             }



             return response([
        
                'message' => 'Status changed Successfully..!',
                'data' => $booking,
                'statusCode' => 200
             
             ],200);

        }
    }
}
