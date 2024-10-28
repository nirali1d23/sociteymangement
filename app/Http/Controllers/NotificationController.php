<?php

namespace App\Http\Controllers;
use Kreait\Firebase;
use Google_Client;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\FirebaseNotificationTrait;

class NotificationController extends Controller
{
    use FirebaseNotificationTrait;
    public function send(Request $request)
    {
        $fcmToken = $request->input('token');
        $title = "Test Notification";
        $body = "This is a test notification";
        
        return $this->sendFirebaseNotification($fcmToken, $title, $body);
    }

    


}
