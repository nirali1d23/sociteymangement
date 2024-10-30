<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Maintancebill;
use App\Models\Maintancebilllist;
use App\Models\Flat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class MaintanceBillController extends Controller
{
    public function store(Request $request)
    {
        $store =  new Maintancebill;
        $store->due_date = $request->due_date;
        $store->total_amount = $request->total_amount;
        $store->member_type = $request->member_type;
        $store->billing_period = $request->billing_period;
        $store->save();

        return response( [
            'message' => 'Maintance  Bill created  Successfully..!',
            'data' => $store,
            'statusCode' => 200
        ],200 );
    }
    public function maintancebilldisplay(Request $request)
    {    
        $flat_no = Flat::find($request->flat_id);
        $houses = $flat_no->houses;    
        $houses_with_status = $houses->map(function($house) 
        {
            $status = Maintancebilllist::where('flat_id', $house->id)->exists() ? 1 : 0;
            $house->status = $status;    
            return $house;
        });
        return response([
            'message' => 'House list fetched successfully',
            'data' => $houses_with_status,
            'statusCode' => 200
        ], 200);   
    
    }
    public function paymaintance(Request $request)
    {
       $data =  Maintancebilllist::create([
 
             'flat_id' => $request->flat_id,
             'date' => $request->date,
             'payment_method' => $request->payment_method,
             'status' =>1


        ]);

        return response( [
            'message' => 'Maintance  Bill Payed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200 );
    }
}
