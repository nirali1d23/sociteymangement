<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintancebill;

class MaintanceBillController extends Controller
{
    public function maintancebilllist(Request $request)
    {
        $flat_id = $request->flat_id;

        $data = Maintancebill::with(['maintancebilllists' => function ($query) use ($flat_id) {
                $query->where('flat_id', $flat_id);
            }])
            ->get()
            ->map(function ($bill) {
             
                $bill->payment_status = $bill->maintancebilllists->isNotEmpty() ? 1 : 0;
                return $bill;
            });
        
            return response([
            'message' => 'Maintance bill list displayed Successfully..!',  
            'data' => $data,  
            'statusCode' => 200
         ],200);
    }


}
