<?php

namespace App\Http\Controllers\API\User;

use App\Models\Bookamenities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AmenitiesController extends Controller
{
    public function requestamenitiesbooking(Request $request)
    {

        Bookamenities::create([


            'user_id' => $request->user_id,
            'amenities_id' => $request->amenities_id,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description
        ]);

        return response([
        
            'message' => 'Amenities Booked Successfully..!',
            
            'statusCode' => 200
         
         ],200);
             
         
    }
}
