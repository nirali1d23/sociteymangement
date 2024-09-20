<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\maintance;
use App\Models\MaintanceProcess;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class MaintancerequestController extends Controller
{
    public function displaymaintancerequest(Request $request)
    {
         $data = maintance::all();
         return response([
            'message' => 'MaintanceRequest Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );
    }
    public function assigntostaff(Request $request)
    {
        MaintanceProcess::create([
   
             'maintance_request_id' => $request->maintance_id,
             'staff_id' => $request->staff_id,
              'status' => $request->status
        ]);

        return response([
            'message' => 'MaintanceRequest Assign Successfully..!',
            'statusCode' => 200
           ],200 );
    }
}
