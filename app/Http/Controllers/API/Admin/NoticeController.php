<?php
namespace App\Http\Controllers\API\Admin;
use App\Models\Notice;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;
use App\Traits\ImageUpload;
use App\Models\NoticeComment;
use App\Traits\FirebaseNotificationTrait;
use Symfony\Component\HttpFoundation\File\File;

class NoticeController extends Controller
{
    use ImageUpload;
    use FirebaseNotificationTrait;
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
           ]);
             $notice =  new Notice;
        $notice->title = $request->title;
        $notice->description= $request->description;
        $notice->start_date = $request->start_date;
        $notice->time = $request->time;
        $notice->save();
            if ($request->hasFile('image')) 
                {
                $image_1 = $request->file('image');
                $image = $this->uploadImage($image_1, 'image'); // Pass both the file and directory
                $notice->image= $image;
                $notice->save();
            }
      
        if(!$request->has('start_date'))
        {
         $user = User::where('user_type','2')->get();
           foreach($user as $userdata)
           {
            $fcmToken = $userdata->fcm_token;
            \Log::info('Notice Notification Token', [
                'user_id'   => $userdata->id,
                'fcm_token' => $userdata->fcm_token,
            ]);
                if($fcmToken != null)
        {
            $title = "🌟 Exciting News! A New Notice Has Arrived!";
            $body  = "Hey there! We've got something new for you. Check out the latest notice and stay informed. Don't miss it!";

          $response = $this->sendFirebaseNotification(
    $userdata->fcm_token,
    $title,
    $body,
    $data = []
);

            \Log::info('Notice Notification Status', [
                'user_id'   => $userdata->id,
                'fcm_token' => $userdata->fcm_token,
                'response'  => $response,
                'status'    => isset($response['success']) && $response['success'] == 1 ? 'SENT' : 'FAILED'
            ]);
        }
           }
        }


        return response( [
            'message' => 'Notice Created Successfully..!',
            'statusCode' => 200
        ],200 );

    }
    public function display(Request $request)
    {
        
        // $data = Notice::orderBy('created_at', 'desc')->get();


        // return response( [
        //     'message' => 'Notice Displayed Successfully..!',
        //     'data' => $data,
        //     'statusCode' => 200
        // ],200 );

        // $data = Notice::whereNull('start_date')->whereNull('time')->orderBy('created_at', 'desc')->get()->map(function($item) {
        //     $item->image = url('image/' . $item->image);
        //     return $item;
        // });
        $data = Notice::where(function ($query) {
            $now = now()->setTimezone('Asia/Kolkata');
            $query->whereNull('start_date')
                  ->whereNull('time')
                  ->orWhere(function ($q) use ($now) {
                      $q->whereDate('start_date', '<=', $now->toDateString())
                        ->whereTime('time', '<=', $now->toTimeString());
                  });
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($item) {
            $item->image = url('image/' . $item->image);
            $item->created_at = Carbon::parse($item->created_at)->setTimezone('Asia/Kolkata');
           
            return $item;
        });
        

        return response([
            'message' => 'Notice Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ], 200);

    }
    public function noticeupdate(Request $request)
    {
        $notice_id = $request->notice_id;

        $data = Notice::find($notice_id);

        if($data)  
        {
            $data->title = $request->title;
            $data->description = $request->description;
            $data->start_date = $request->start_date;
            $data->time = $request->time;

            $data->save();

            return response( [
                'message' => 'Notice Updated Successfully..!',
                'statusCode' => 200
            ],200 );
    


        }

        return response( [
            'message' => 'Notice Not Found..!',
            'statusCode' => 400
        ],404);
    }
    public function schedulenoticedisplay(Request $request)
    {
       
  
        $data = Notice::where(function ($query) {
            $now = now()->setTimezone('Asia/Kolkata');
            $query->whereNotNull('start_date')
                  ->where(function ($q) use ($now) {
                      // Compare both start_date and time together
                      $q->whereDate('start_date', '>', $now->toDateString()) // Start date is in the future
                        ->orWhere(function ($subQuery) use ($now) {
                            // If start_date is today, check if the time is in the future
                            $subQuery->whereDate('start_date', '=', $now->toDateString())
                                     ->whereTime('time', '>', $now->toTimeString());
                        });
                  });
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($item) {
            $item->image = url('image/' . $item->image); // Add full URL to the image
            return $item;
        });

        return response([
            'message' => 'Notice Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ], 200);
    }
    public function commentlist(Request $request)
    {
         $data = NoticeComment::with('user')->with('notice')->where('notice_id',$request->id)->get()->map(function($item){

            $item->created_at = Carbon::parse($item->created_at)->setTimezone('Asia/Kolkata');
            return $item;

         });
        //  $data = Amenities::with('bookamenities')->get()->map(function($item)


         if($data)
         {
         return response([
            'message' => 'Notice Comment displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );
        }        
        return response([
            'message' => 'Notice Comment Not Found..!',
            'data' => $data,
            'statusCode' => 404
           ],404 );

    }
    public function noticedelete(Request $request)

    {
        $data = Notice::find($request->id);
        if($data)
        {
             $data->delete();

             return response( [
                'message' => 'Notice deleted Successfully..!',
                'statusCode' => 200
            ],200 );
    

        }
        return response( [
            'message' => 'Notice Not Found..!',
            'statusCode' => 400
        ],404);
    }
}
