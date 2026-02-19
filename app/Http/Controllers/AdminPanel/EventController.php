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

                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary editProduct me-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger  deleteProduct">Delete</a>';
                    $btn .= '</div>';

                    return $btn;

                })
->addColumn('action', function($row) {

    $btn = '<div class="d-flex justify-content-center gap-2">';

    $btn .= '<a href="javascript:void(0)"
                data-id="'.$row->id.'"
                class="btn btn-info btn-sm viewFeedback">
                Feedback
             </a>';

    $btn .= '<a href="javascript:void(0)"
                data-id="'.$row->id.'"
                class="btn btn-primary btn-sm editProduct">
                Edit
             </a>';

    $btn .= '<a href="javascript:void(0)"
                data-id="'.$row->id.'"
                class="btn btn-danger btn-sm deleteProduct">
                Delete
             </a>';

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
        if ($request->hasFile('image')) {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); // Pass both the file and directory
        }



        $event = Event::updateOrCreate(
            ['id' => $request->event_id],
            [
                'event_name' => $request->event_name,
                'area' => $request->area,
                'date' => $request->date,
                'time' => $request->time,
                'day' => $request->day,
                'instruction' => $request->instruction,
                'image' => $image,
            ]
        );


        $data = User::where('user_type', 2)->get();
        foreach ($data as $token) {
            if ($token->fcm_token != null) {
                $fcmToken = $token->fcm_token;
                $title = "🎉 New Event Alert!";
                $body = "✨ Don't miss out! Join us for an unforgettable experience. Stay tuned for more details! 🌟";
                $this->sendFirebaseStaffNotification($fcmToken, $title, $body);
            }
        }
        return response([
            'message' => 'Event Created Successfully..!',
            'statusCode' => 200
        ], 200);
    }
    public function edit($id)
    {
        return Event::findOrFail($id);
    }
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // If you later store image, you can unlink here
        // if ($event->image && file_exists(public_path($event->image))) {
        //     unlink(public_path($event->image));
        // }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully!'
        ]);
    }
    public function feedback($id)
    {
        $event = Event::with(['eventfeedback.user'])->findOrFail($id);

        $data = $event->eventfeedback->map(function ($fb) {
            return [
                'user_name' => $fb->user->name ?? '-',
                'feedback' => $fb->feedback ?? '-',
                'date' => $fb->created_at->format('d-m-Y')
            ];
        });

        return response()->json($data);
    }


}
