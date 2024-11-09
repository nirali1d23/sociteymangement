<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\preapproval;
use App\Models\Visitor;

class VistiorController extends Controller
{
    public function prebookvistior(Request $request)
    {
      $data =   preapproval::create([
             'visitor_name' => $request->visitor_name,
             'date' => $request->date,
             'flat_no' => $request->flat_no,
             'contact_number' => $request->contact_number,
             'vehicle_number' => $request->vehicle_number,
             'purpose' => $request->purpose,
        ]);

        return response( [
            'message' => 'Prebook request created  Successfully..!',
             'data' =>$data,
            'statusCode' => 200
        ],200 );
    }
    public function visitorlist(Request $request)
    { 
         $data = Visitor::where('flat_no',$request->flat_no)->where('status',1)->get();


         return response( [
            'message' => 'Visitor list displayed Successfully..!',
             'data' =>$data,
            'statusCode' => 200
        ],200 );
          

    }
    public function pendingvistorlist(Request $request)
    {
        $data = Visitor::where('flat_no',$request->flat_no)->where('status',0)->get();
        return response( [
            'message' => 'Visitor list displayed Successfully..!',
             'data' =>$data,
            'statusCode' => 200
        ],200 );

    }
    public function approvevistior(Request $request)
    {
        $data = Visitor::find($request->id);
        if($data)
        {

            $data->status = $request->status;
            $data->save();

            return response( [
                'message' => 'Visitor Status changed Successfully..!',
                 'data' =>$data,
                'statusCode' => 200
            ],200 );

        }

        return response( [
            'message' => 'No Visitor found..!',
             'data' =>$data,
            'statusCode' => 400
        ],400 );
    }

    

}
