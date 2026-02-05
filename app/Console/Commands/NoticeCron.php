<?php

namespace App\Console\Commands;
use Kreait\Firebase;
use Google_Client;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\FirebaseNotificationTrait;
use Illuminate\Console\Command;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class NoticeCron extends Command
{
    use FirebaseNotificationTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notice:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     $timestamp = now();

    //     $date = now()->format('Y-m-d');

    //     // info("Cron Job running at laravel ". $date);


    //     // info("Cron Job running at laravel ". now());

    //    $data =  Notice::all();

        
    //     foreach($data as $item)
    //     {
    //         if($item->start_date == $date)
    //         {
    //              // $fcmToken = $request->input('token');
    //             $fcmToken = 'c0gJrrjaQuuL_6TGPHDGM7:APA91bECccj1BiJlrpF4kRVzyxM3JVfaK_cWE-EPx0x7M9h-ui-_dd17lZWwzpi2POD05RbCfegXQV7oiX8G1dWhqrkfMfe-cpI499sUt8x-5sW672sbO8LzIlPP1Ob6k9cF2BQjDklj';
    //             $title = $item->title;
    //             $body = $item->description;
    //     return $this->sendFirebaseNotification($fcmToken, $title, $body);
    //         }
    //     }



    // }


public function handle()
{
    $date = now()->format('Y-m-d'); 
    Log::info('NoticeCron executed at: ' . now());

    $notices = Notice::all();

    $users = User::where('user_type', 2)
        ->whereNotNull('fcm_token')
        ->get();

    foreach ($notices as $notice) {

        if ($notice->start_date == $date) {

            foreach ($users as $user) {

                $response = $this->sendFirebaseStaffNotification(
                    $user->fcm_token,
                    $notice->title,
                    $notice->description
                );

                if ($response->failed()) {

                    Log::error('FCM failed', [
                        'user_id' => $user->id,
                        'status'  => $response->status(),
                        'body'    => $response->body(),
                    ]);

                    $this->error(
                        "Notification failed for user ID {$user->id}"
                    );

                } else {

                    Log::info('FCM sent', [
                        'user_id' => $user->id,
                        'response' => $response->json(),
                    ]);

                    $this->info(
                        "Notification sent to user ID {$user->id}"
                    );
                }
            }
        }
    }
}

}
