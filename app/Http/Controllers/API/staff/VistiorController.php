<?php

namespace App\Http\Controllers\API\staff;
use App\Models\Visitor;
use App\Models\preapproval;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class VistiorController extends Controller
{
    public function visitorentry(Request $request)
    {
        Visitor::create([

         'visitor_name' => $request->visitor_name,
         'date' => $request->visitor_date,
         'check_in' => $request->check_in,
         'flat_no'=>$request->flat_no,
         'purpose' => $request->purpose,

        ]);

        return response( [
            'message' => 'Visitor Created Successfully',
            'statusCode' => 200
        ],200);


    }
    public function visitorentrydetails(Request $request)
    {

        $data = Visitor::all();

        return response( [
            'message' => 'Visitor Created Successfully',
            'data' => $data,
            'statusCode' => 200
        ],200);

           
    }
    public function previsitorlist(Request $request)
    {
       $data =  preapproval::all();

       return response( [
        'message' => 'PreVisitor Displayed Successfully',
        'data' => $data,
        'statusCode' => 200
            ],200);
    }
    public function updateprevisitor(Request $request)
    {
          $previstor = preapproval::find($request->previstorid);

          if($previstor)
          {
              
          }
    }
}