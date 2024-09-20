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
        $notices = Notice::all();
        foreach ($notices as $notice) {
            if ($notice->start_date == $date) {
                $fcmToken = 'c0gJrrjaQuuL_6TGPHDGM7:APA91bECccj1BiJlrpF4kRVzyxM3JVfaK_cWE-EPx0x7M9h-ui-_dd17lZWwzpi2POD05RbCfegXQV7oiX8G1dWhqrkfMfe-cpI499sUt8x-5sW672sbO8LzIlPP1Ob6k9cF2BQjDklj';
                $title = $notice->title;
                $body = $notice->description;

                $response = $this->sendFirebaseNotification($fcmToken, $title, $body);

          
                if ($response->failed()) {
                    $this->error('Notification failed to send. Details: ' . json_encode($response->json()));
                } else {
                    $this->info('Notification sent successfully! Details: ' . json_encode($response->json()));
                }
            }
        }

        $this->info("Notification process completed.");
    }
}
