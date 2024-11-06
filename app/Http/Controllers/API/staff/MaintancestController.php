<?php

namespace App\Http\Controllers\API\staff;
use App\Models\maintance;
use App\Models\MaintanceProcess;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class MaintancestController extends Controller
{
    public function maintancelist(Request $request)
    {
        $data = Maintenance::with('maintenance_process')->get();

        return response([
            'message' => 'MaintanceRequest Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );
    }

    public function updatemaintance(Request $request)
    {  
        $data = MaintanceProcess::where('maintance_request_id',$request->id)->first();
        if($data!=null)
        {
            $data->status = $request->status;
            $data->save();
            return response([
                'message' => 'MaintanceRequest status updated Successfully..!',
                'data' => $data,
                'statusCode' => 200
                ],200);
        }
            return response([
                'message' => 'No data found..!',
                'statusCode' => 400
            ],400);
    }

    
    
}

