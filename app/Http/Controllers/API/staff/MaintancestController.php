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

        $request->validate([

            'staff_id' => 'required',
          
]);
$data = MaintanceProcess::where('staff_id', $request->staff_id)
            // ->where('status', 1)
            ->with('maintenance')
            ->get()
            ->map(function ($item) 
            {
               
                $imageUrl = $item->maintenance && $item->maintenance->image 
                            ? url('images/' . $item->maintenance->image) 
                            : null;
                
                if ($item->maintenance) {
                    $item->maintenance->image = $imageUrl;
                }
                
                return $item;
            });

return response([
    'message' => 'MaintanceRequest Displayed Successfully....!',
    'data' => $data,
    'statusCode' => 200
], 200);

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

