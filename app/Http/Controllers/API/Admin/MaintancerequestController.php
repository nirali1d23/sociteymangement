<?php

namespace App\Http\Controllers\API\Admin;
use App\Models\maintance;
use App\Models\User;
use App\Models\MaintanceProcess;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class MaintancerequestController extends Controller
{
    public function displaymaintancerequest(Request $request)
    {
        $data = Maintenance::when(request('status') == 1, function ($query) {
            $query->with(['maintenance_process' => function ($query) {
                $query->with('staff:id,name'); 
            }]);
        })
        ->get();
    
    $result = $data->map(function ($item) {
        if ($item->status == 1 && isset($item->maintenance_process)) {
            return [
                'maintenance_id' => $item->id,
                'maintenance_details' => $item->description,
                'staff_id' => $item->maintenance_process->staff->id ?? null,
                'staff_name' => $item->maintenance_process->staff->name ?? 'No staff assigned'
            ];
        } else {
            return [
                'maintenance_id' => $item->id,
                'maintenance_details' => $item->description,
                'status' => $item->status
            ];
        }
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    }
    public function assigntostaff(Request $request)
    {
        MaintanceProcess::updateOrCreate(
            [
            'maintance_request_id' => $request->maintance_id,
                'staff_id' => $request->staff_id
            ],
            [
                'status' => $request->status
            ]
        );
    
        return response([
            'message' => 'MaintanceRequest Assigned Successfully..!',
            'statusCode' => 200
        ], 200);
    }
    public function stafflist()
    {
        $data = User::where('user_type' , '3')->get();

        return response([
            'message' => 'staff list displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
           ],200 );
    }
}
