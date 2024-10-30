<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\maintance;
use App\Traits\ImageUpload;
class MaintanceController extends Controller
{
    public function requestmaitnace(Request $request)
    {
        $request->validate([

            'user_id' => 'required',
            'description' => 'required',
            'image' => 'required',
    
            ]);
    
            
        if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); 
        }

         $data = new maintance;

         $data->user_id = $request->user_id;
         $data->description = $request->description;
         $data->image = $request->image;
         $data->status = 0;

         $data->save();


         return response([
            'message' => 'Maintance Request Registered Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );


 
    }
}
