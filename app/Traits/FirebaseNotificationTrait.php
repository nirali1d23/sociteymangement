<?php

namespace App\Traits;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
trait FirebaseNotificationTrait
{
    public function getGoogleAccessToken()
    {
        // Path to your Firebase service account JSON file
        $credentialsFilePath = public_path('word-wizard-17983-firebase-adminsdk-xxnt0-e9f45c1840.json');
        
        // Initialize the Google Client
        $client = new Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        
        // Fetch access token with assertion
        $client->fetchAccessTokenWithAssertion();
        
        // Get the token and return it
        $token = $client->getAccessToken();
        return $token['access_token'];
    }
    public function sendFirebaseNotification($fcmToken, $title, $body)
    {
        $message = [
            "message" => [
                "token" => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                
            ],
        ];

       
        $apiurl = 'https://fcm.googleapis.com/v1/projects/word-wizard-17983/messages:send';
        
      
        $headers = [
            'Authorization' => 'Bearer ' . $this->getGoogleAccessToken(),
            'Content-Type' => 'application/json',
        ];

    
        $response = Http::withHeaders($headers)->post($apiurl, $message);

        return $response;

        // if ($response->failed()) {
        //     return response()->json(['error' => 'Notification failed to send.', 'details' => $response->json()], $response->status());
        // } else {
        //     return response()->json(['success' => 'Notification sent successfully', 'status' => 200]);
        // }
    }
}
