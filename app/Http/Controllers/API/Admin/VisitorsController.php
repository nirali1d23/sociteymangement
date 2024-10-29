<?php


namespace App\Http\Controllers\API\Admin;
use App\Models\preapproval;
use App\Models\Visitor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorsController extends Controller
{
    public function prebookingrequestlist(Request $request)
    {

        $data = preapproval::all();

        return response([
        
            'message' => 'Prebooking Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
         
         ],200);
        
    }
    public function visitorlist(Request $request)
    {
     
         $data = Visitor::all();

         return response([
        
            'message' => 'visitorlist Displayed Successfully..!',
            'data' => $data,
            'statusCode' => 200
         
         ],200);

          
    }
    public function approvalprebooking(Request $request)
    {
        $booking = preapproval::find($request->booking_id);

        if($booking!=null)
        {
             $booking->status =  $request->status;
             $booking->save();

             return response([
        
                'message' => 'Status changed Successfully..!',
                'data' => $booking,
                'statusCode' => 200
             
             ],200);

        }
    }




}
