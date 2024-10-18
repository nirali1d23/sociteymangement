<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Notice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ImageUpload;
use Symfony\Component\HttpFoundation\File\File;

class NoticeController extends Controller
{
    use ImageUpload;

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

        $data = Notice::orderBy('created_at', 'desc')->get()->map(function($item) {
            // Modify the image field to include the full URL
            $item->image = url('images/' . $item->image);
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

    


    
    
}
