<?php
namespace App\Http\Controllers\API\Admin;
use App\Models\Notice;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            if ($request->hasFile('image')) 
            {
                $image_1 = $request->file('image');
                $image = $this->uploadImage($image_1, 'image'); // Pass both the file and directory
            }
        $notice =  new Notice;
        $notice->title = $request->title;
        $notice->description= $request->description;
        $notice->image = $image;
        $notice->start_date = $request->start_date;
        $notice->time = $request->time;
        $notice->save();
        
         $user = User::where('user_type','2')->get();
           foreach($user as $userdata)
           {
            $fcmToken = $userdata->fcm_token;
              if($fcmToken)
              {
            $title = "🌟 Exciting News! A New Notice Has Arrived!";
            $body = "Hey there! We've got something new for you. Check out the latest notice and stay informed. Don't miss it!";
             $this->sendFirebaseNotification($fcmToken, $title, $body);
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

        $data = Notice::whereNull('start_date')->whereNull('time')->orderBy('created_at', 'desc')->get()->map(function($item) {
            $item->image = url('image/' . $item->image);
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
        $data = Notice::whereNotNull('start_date')->orderBy('created_at', 'desc')->get()->map(function($item) {
            $item->image = url('images/' . $item->image);
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
         $data = NoticeComment::with('user')->with('notice')->get();

         return response([
            'message' => 'Notice Comment displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );

   }
}
