<?php

namespace App\Http\Controllers\API\User;
use App\Models\Event;
use App\Models\EventFeedback;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventtController extends Controller
{
    public function eventdisplay(Request $request)
    { 
       
         $data = Event::all();
         if($data!=null)
         {
         return response([
            'message' => 'Event Display Successfully..!',
            'statusCode' => 200
         ],200);
        }
        return response([
            'message' => 'No Event found!',
            'statusCode' => 404
         ],404);
    }
    public function eventfeedback(Request $request)
    {
        EventFeedback::create([
        'user_id' => $request->user_id,
        'event_id' =>$request->event_id,
        'feedback' =>$request->feedback
        ]);
        return response([
            'message' => 'Feedback submitted Successfully..!',
            'statusCode' => 200
         
         ],200);
    }
}
