<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Event;
use App\Models\User;
use App\Models\EventFeedback;
use Kreait\Firebase;
use Google_Client;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Traits\FirebaseNotificationTrait;
use App\Http\Controllers\Controller;
use App\Traits\ImageUpload;
use Symfony\Component\HttpFoundation\File\File;

class EventController extends Controller
{
    use ImageUpload;
    use FirebaseNotificationTrait;
    public function create(Request $request)
    {
        if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'images'); // Pass both the file and directory
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

        // $data = User::all();
        // foreach($data as  $token)
        // {
        //     if($token->fcm_token !=null)
        //     {

        //       $fcmToken = $token->fcm_token;
        //       $title = "Test Notification";
        //      $body = "This is a test notification";
        //      return $this->sendFirebaseNotification($fcmToken, $title, $body);
        //     }
        // }

        return response( [
            'message' => 'Event Created Successfully..!',
            'statusCode' => 200
        ],200 );
    }
    public function display(Request $request)
    {
         
        $query = Event::query();

        // Apply name filter if provided
        if ($request->has('event_name') && !empty($request->event_name)) {
            $query->where('event_name', 'like', '%' . $request->event_name . '%');
        }
    
        // Apply date filter if provided
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('date', $request->date);
        }
    
        // Get the filtered data and map the image URLs
        $data = $query->get()->map(function($item) {
            $item->image = url('images/' . $item->image);
            return $item;
        });
    
        // Return the response with the filtered data
        return response([
            'message' => 'Event Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ], 200);
    }

    public function eventfeedbacklist(Request $request)
    {
        $data = EventFeedback::with(['event', 'user'])->get();
        return response( [
            'message' => 'Event Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200 );
    }

    public function edit(Request $request)
    {

          $data = Event::find($request->id);
          if($data)
          {
            if ($request->hasFile('image')) 
            {
                $image_1 = $request->file('image');
                $image = $this->uploadImage($image_1, 'image');
                 $data->image = $image;
            }

            if($request->has('event_name'))
            {
                $data->event_name = $request->event_name;

            }
            if($request->has('date'))
            {
                $data->date = $request->date;

            }
            if($request->has('area'))
            {
                $data->area = $request->area;

            }
            if($request->has('time'))
            {
                $data->time = $request->time;

            }
            if($request->has('day'))
            {
                $data->day = $request->day;

            }
            if($request->has('instruction'))
            {
                $data->instruction = $request->instruction;

            }

            $data->save();
            return response( [
                'message' => 'Event Updated Successfully..!',
                'statusCode' => 200
            ],200 );

          }

          return response( [
            'message' => 'Event Not Found..!',
            'statusCode' => 400
        ],400 );
         
        
    }
    public function delete(Request $request)
    {
        $data = Event::find($request->id);

         if($data)
         {
             $data->delete();

             return response( [
                'message' => 'Event Deleted Successfully..!',
                'statusCode' => 200
            ],200 );

         }

         return response( [
            'message' => 'Event Not Found..!',
            'statusCode' => 400
        ],400 );


    }
}
