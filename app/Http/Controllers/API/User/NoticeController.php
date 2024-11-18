<?php

namespace App\Http\Controllers\API\User;
use App\Models\NoticeComment;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FirebaseNotificationTrait;

class NoticeController extends Controller
{
     use FirebaseNotificationTrait;

     public function notice_comment(Request $request)
     {
        $data =  new NoticeComment;
        $data->user_id = $request->user_id;
        $data->notice_id = $request->notice_id;
        $data->comment = $request->comment;

        $data->save();

        $data = User::where('user_type','0')->first();
       
            if($data->fcm_token !=null)
            {
                $fcmToken = $data->fcm_token;
                $title = "💬 New Comment on Your Notice!";
                $body = "📢 Someone has commented on your notice. Kindly review the comment and take appropriate action if needed. Thank you! 🏢";
                 $this->sendFirebaseNotification($fcmToken, $title, $body);
            }
        

        return response([
            'message' => 'Notice Comment Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );
     }
}
