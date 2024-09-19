<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Amenities;
use App\Traits\ImageUpload;
use Symfony\Component\HttpFoundation\File\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AmenitiesController extends Controller
{
    use ImageUpload;

    public function create(Request $request)
    {
        if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); 
        }
        $store = new Amenities;
        $store->amenities_name = $request->amenities_name;
        $store->rule= $request->rule;
        $store->image = $image;

        $store->save();

        return response( [
            'message' => 'Amenities Created Successfully..!',
            'statusCode' => 200
        ], 200 );

    }

    public function display(Request $request)
    {
        $data = Amenities::get()->map(function($item)
    {
        $item->image = url('images/' . $item->image);
        return $item;
    });

        return response([
        
           'message' => 'Amenities Displayed Successfully..!',
           'data' => $data,
           'statusCode' => 200
        
        ],200);
    }
}

