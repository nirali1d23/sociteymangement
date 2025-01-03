<?php
namespace App\Http\Controllers\API\Admin;
use App\Models\maintance;
use App\Traits\FirebaseNotificationTrait;

use App\Models\User;
use App\Models\Allotment;
use App\Models\MaintanceProcess;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class MaintancerequestController extends Controller
{ 
    use FirebaseNotificationTrait;
    public function displaymaintancerequest(Request $request)
    {

        $data = maintance::when(request('status') == 1, function ($query) {
            $query->with(['maintenance_process' => function ($query) {
                $query->with('staff:id,name'); 
            }]);
        })
        ->get();
    
        $result = $data->map(function ($item) 
        
        {

        if ($item->status == 1 && isset($item->maintenance_process)) 
        {
            return [
                'maintenance_id' => $item->id,
                'maintenance_details' => $item->description,
                'status' =>  $item->maintenance_process->status,
                'image' =>    url('image/' . $item->image),
                'staff_id' => $item->maintenance_process->staff->id ?? null,
                'staff_name' => $item->maintenance_process->staff->name ?? 'No staff assigned'
            ];
        } 
        else 
        {
            return [
                'maintenance_id' => $item->id,
                'maintenance_details' => $item->description,
                'status' => $item->status
            ];
        }
        });
        
        return response([
                'message' => 'staff list displayed Successfullyy..!',
                'data' => $result,
                'statusCode' => 200
            ],200 );
    
    }
    public function assigntostaff(Request $request)
    {
        
       
      $d =   maintance::find($request->maintance_id);
     
      if($d)
         {
        $d->status = 1;
        $d->save();
    
        }
        MaintanceProcess::updateOrCreate(
            [
                'maintance_request_id' => $request->maintance_id,
                'staff_id' => $request->staff_id
            ],
            [
                'status' => $request->status
            ]
        );
        $user = User::where('id',$request->staff_id)->where('user_type','3')->first();
        $fcmToken = $user->fcm_token;
        if($fcmToken)
        {
            $title = "Exciting New Task Assign for You! 🎉";
            $body = "Hey there! You've been assigned a new task that's perfect for you. Let's make it amazing together! 💪✨";
            
         
    
        $this->sendFirebaseNotification($fcmToken, $title, $body);
        }


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
    public function maintancestatus(Request $request)
    {
        $data = MaintanceProcess::where('maintance_request_id',$request->id)->with('staff')->first();
        if ($data) 
        {
            return response([
                'message' => 'Status displayed successfully..! ',
                'data' => [
                    'id' => $data->id,
                    'status' => $data->status, 
                    'staff_name' => $data->staff ? $data->staff->name : 'No staff assigned',
                ],
                'statusCode' => 200
            ], 200);
        }
        return response([
            'message' => 'Data Not Fond..!',
            'data' => $data,
            'statusCode' => 404,
            'status' => 0,

           ],404 );



    }
}
