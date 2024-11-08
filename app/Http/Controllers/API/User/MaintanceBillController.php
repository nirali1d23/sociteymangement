<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintancebill;

class MaintanceBillController extends Controller
{
    public function maintancebilllist(Request $request)
    {
        $flat_id = $request->flat_id; // Assuming 'user_id' is coming from the request

        $data = Maintancebill::with(['maintancebilllists' => function ($query) use ($userId) {
                $query->where('flat_id', $flat_id);
            }])
            ->get()
            ->map(function ($bill) {
                // Check if filtered maintancebilllists is not empty
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
