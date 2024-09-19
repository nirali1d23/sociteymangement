<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ImageUpload;
use Symfony\Component\HttpFoundation\File\File;

class EventController extends Controller
{
    use ImageUpload;

    public function create(Request $request)
    {
        
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

        
        return response( [
            'message' => 'Event Created Successfully..!',
            'statusCode' => 200
        ],200 );
    }

    public function display(Request $request)
    {
        $data = Event::get()
        ->map(function($item) {
            
            $item->image = url('images/' . $item->image);
            return $item;
        });
        return response( [
            'message' => 'Event Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200 );
    }
}
