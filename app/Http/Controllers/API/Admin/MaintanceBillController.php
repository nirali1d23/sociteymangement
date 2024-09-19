<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\Maintancebill;
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

   
}
