<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookamenities;
class BookingamenitiesController extends Controller
{
    public function display(Request $request)
    {
         $data = Bookamenities::with('amenity')->orderBy('created_at', 'desc')->get();
         return response( [
            'message' => 'Bookeaemenies Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200);
        
    }
    public function changestatusbookaementies(Request $request)
    {
          $data = Bookamenities::find($request->bookaemnitites_id);

          if($data!=null)
          {
            $data->status = $request->status;
            $data->save();

            return response( [
                'message' => 'status updated Successfully..!',
                'data' => $data,
                'statusCode' => 200
            ],200 );

          }
    }
}
