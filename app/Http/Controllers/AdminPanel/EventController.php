<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;
use App\Traits\FirebaseNotificationTrait;
use App\Http\Controllers\Controller;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
class EventController extends Controller
{

       use ImageUpload;
    use FirebaseNotificationTrait;
    
    public function index(Request $request)
    {
              
        if ($request->ajax()) {

  
            $data = Event::latest()->get();

            return Datatables::of($data)

                    ->addIndexColumn()

                    ->addColumn('action', function($row)
                    {
                        $btn = '<div class="d-flex justify-content-center">';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary editProduct me-2">Edit</a>';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger  deleteProduct">Delete</a>';
                        $btn .= '</div>';

                                                    return $btn;

                                            })

                
                    ->rawColumns(['action'])

                    ->make(true);

        }


        return view('admin_panel.admin.event');

    }

 
      public function store(Request $request)
    {
        // $validated = $request->validate([
         
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif', // Image validation
        // ]);
        if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); // Pass both the file and directory
        }
        $create = new Event;
        $create->event_name = $request->event_name;
        $create->date = $request->date;
        $create->area = $request->area;
        $create->time = $request->time;
        $create->day = $request->day;
        $create->instruction= $request->instruction;
        $create->image= $image;
        $create->save();
        $data = User::where('user_type',2)->get();
        foreach($data as  $token)
        {
            if($token->fcm_token !=null)
            {
                $fcmToken = $token->fcm_token;
                $title = "🎉 New Event Alert!";
                $body = "✨ Don't miss out! Join us for an unforgettable experience. Stay tuned for more details! 🌟";   
                 $this->sendFirebaseStaffNotification($fcmToken, $title, $body);
            }
        }
        return response( [
            'message' => 'Event Created Successfully..!',
            'statusCode' => 200
        ],200 );
    }
}
