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
        $credentialsFilePath = public_path('society-management-2de9d-firebase-adminsdk-c2g7j-756228fb96.json');
       
        
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
    public function sendFirebaseNotification($fcmToken, $title, $body, $data = [])
    {
        try {
            $stringifiedData = $data ? array_map('strval', $data) : [];
            $message = [
                "message" => [
                    "token" => $fcmToken,
                    "notification" => [
                        "title" => $title,
                        "body"  => $body,
                    ],
                    "data" => $stringifiedData
                ]
            ];

            $apiurl = 'https://fcm.googleapis.com/v1/projects/society-management-daea4/messages:send';

            $headers = [
                'Authorization' => 'Bearer ' . $this->getGoogleAccessToken(),
                'Content-Type'  => 'application/json',
            ];

            $response = Http::withHeaders($headers)->post($apiurl, $message);

            if ($response->failed()) {
                \Log::error('FCM Notification Failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return false; // 🔴 silently fail
            }

            return true; // ✅ success

        } catch (\Throwable $e) {
            \Log::error('FCM Exception', [
                'message' => $e->getMessage(),
            ]);
            return false; // 🔴 silently fail
        }
    }
    public function getGoogleAccessToken2()
    {
        // Path to your Firebase service account JSON file
        $credentialsFilePath = public_path('society-management-2de9d-firebase-adminsdk-c2g7j-756228fb96.json');
       
        
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
    public function sendFirebaseStaffNotification($fcmToken, $title, $body)
    {
        $message = 
        [
            "message" => [
                "token" => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],      
            ],
        ];
        $apiurl = 'https://fcm.googleapis.com/v1/projects/society-management-daea4/messages:send'; 
        $headers = [
            'Authorization' => 'Bearer ' . $this->getGoogleAccessToken2(),
            'Content-Type' => 'application/json',
        ];
        $response = Http::withHeaders($headers)->post($apiurl, $message);
        if ($response->failed()) 
        {
            return response()->json(['error' => 'Notification failed to send.', 'details' => $response->json()], $response->status());
        } else 
        {
            return response()->json(['success' => 'Notification sent successfully', 'status' => 200]);
        }
    }
    
}
