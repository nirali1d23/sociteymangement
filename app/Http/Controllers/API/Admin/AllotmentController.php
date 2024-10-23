<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Allotment;
use App\Models\Flat;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AllotmentController extends Controller
{
    public function block_list(Request $request)
    { 
         $data = Flat::all();
         if($data!=null)
         {
            return response( [
                'message' => 'Blcok list show Successfully',
                'data' => $data,
                'statusCode' => 200
            ],200);
         }

    }
    public function houselist(Request $request)
     {
          $flat_no = Flat::find($request->flat_id);
          $houses = $flat_no->houses;
          return response( [
            'message' => 'House list show Successfully',
            'data' => $houses,
            'statusCode' => 200
        ],200);
     }
    public function store(Request $request)
    {
         $allotment = new  Allotment;
         $allotment->user_id = $request->user_id; 
         $allotment->flat_id = $request->flat_id; 
         $allotment->save();

         return response( [
            'message' => 'User alloteted  Successfully',
     
            'statusCode' => 200
        ],200);


    }

    public function userlist(Request $request)
    {
         $data = User::where('user_type','3')->get();
         if($data!=null)
         {
            return response( [
                'message' => 'User list show Successfully',
                'data' => $data,
                'statusCode' => 200
            ],200);
         }


    }
}
