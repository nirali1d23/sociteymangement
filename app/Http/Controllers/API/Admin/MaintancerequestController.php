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
        //  $data = maintance::all();
        //  return response([
        //     'message' => 'MaintanceRequest Displayed Successfully..!',
        //     'data' => $data,
        //     'statusCode' => 200
        //    ],200 );
        
    
    // Fetch maintenance data
    $data = Maintance::when(request('status') == 1, function ($query) {
        // If status is 1, load the related maintance_process and staff
        $query->with(['maintance_process' => function ($query) {
            $query->with('staff:id,name'); 
        }]);
    })
->get();

// Transform the data based on the presence of status 1
$result = $data->map(function ($item) {
if ($item->status == 1 && isset($item->maintance_process)) {
    // If status is 1 and maintance_process is loaded, include staff info
    return [
        'maintenance_id' => $item->id,
        'maintenance_details' => $item->details,
        'staff_id' => $item->maintance_process->staff->id ?? null,
        'staff_name' => $item->maintance_process->staff->name ?? 'No staff assigned'
    ];
} else {
    // If status is not 1, return the normal maintenance data
    return [
        'maintenance_id' => $item->id,
        'maintenance_details' => $item->details,
        'status' => $item->status
    ];
}
});

// Return the response
return response([
'message' => 'Maintenance Requests Displayed Successfully..!',
'data' => $result,
'statusCode' => 200
], 200);

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
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
