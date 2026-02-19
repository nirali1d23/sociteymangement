<?php
namespace App\Http\Controllers\AdminPanel;

use DataTables;
use App\Models\Notice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ImageUpload;
use App\Models\NoticeComment;
use App\Models\User;
use App\Traits\FirebaseNotificationTrait;
use Symfony\Component\HttpFoundation\File\File;
class NoticeController extends Controller
{
        use ImageUpload;
    use FirebaseNotificationTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Notice::latest())
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-primary btn-sm editProduct" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-danger btn-sm deleteProduct" data-id="'.$row->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin_panel.admin.notice');
    }

    public function edit($id)
    {
        return Notice::findOrFail($id);
    }

    public function store(Request $request)
    {
       $notice=  Notice::updateOrCreate(
            ['id' => $request->notice_id],
            [
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'time' => $request->time,
            ]
        );

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

          $response = $this->sendFirebaseStaffNotification(
    $userdata->fcm_token,
    $title,
    $body
);

            \Log::info('Notice Notification Status', [
                'user_id'   => $userdata->id,
                'fcm_token' => $userdata->fcm_token,
                'response'  => $response,
            ]);
        }
           }
        }


        return response()->json([
            'success' => true,
            'message' => $request->notice_id
                ? 'Notice updated successfully!'
                : 'Notice created successfully!'
        ]);
    }

    public function destroy($id)
    {
        Notice::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notice deleted successfully!'
        ]);
    }
}
