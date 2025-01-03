<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenities;
use App\Models\Bookamenities;
use App\Models\Maintancebilllist;
use App\Models\Visitor;
use App\Models\preapproval;
use App\Models\maintance;
use App\Models\MaintanceProcess;
class ReportController extends Controller
{
    public function report(Request $request)
    {
        $type = $request->type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        // $startDate = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
        // $endDate = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');
        $responseData = []; 
        
        switch ($type) 
        {
            case 'ammenties':    
                $ammenties = Bookamenities::whereBetween('date', [$startDate, $endDate])->get();
                $responseData = $ammenties->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
                        'time' => $item->time,
                        'description' => $item->description ?? 'N/A',
                        'user_id' => $item->user_id,
                        'user_name' => $item->user->name ?? 'N/A', 
                        'amenities_id' => $item->amenities_id,
                        'amenities_name' => $item->amenity->amenities_name ?? 'N/A', 
                    ];
                });
                break;
            case 'maintance_bil':
                $data = Maintancebilllist::whereBetween('date', [$startDate, $endDate])->get();
                $responseData = $data->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
                        'flat_id' => $item->flat_id,
                        'House_number' => $item->flat->house_number ?? 'N/A', 
                        'status' => $item->status ?? 'N/A',
                       
                    ];
                });
                break;
            case 'visitor':
                $data = Visitor::whereBetween('date', [$startDate, $endDate])->get();
                $responseData = $data->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
                        'visitor_name' => $item->visitor_name,
                        'flat_id' => $item->flat_no,
                        'check_in' => $item->check_in,
                        'purpose' => $item->purpose, 
                       
                    ];
                });
                break;
            default:
                return response()->json([
                    'message' => 'Invalid type specified',
                    'statusCode' => 400
                ], 400);
        }
        return response()->json([
            'message' => 'Data displayed successfully!',
            'data' => $responseData,
            'statusCode' => 200
        ], 200);
    }
    public function popuplistadmin(Request $request)
    {
      
         $data = preapproval::where('status',0)->first();
         $data2 = Bookamenities::where('status',0)->first();
         $data3 = maintance::where('status',0)->first();

         $allData = [
            'preapproval' => $data,
            'book_amenities' => $data2, 
            'maintenance' => $data3
        ];
         
        

         if($data !==null || $data2 !== null || $data3 !== null) 
        {
            return response([
                'message' => 'data Displayed Successfully..!',
                'data' => $allData,
                'statusCode' => 200
            ],200 );

        }
        
         

         return response([
            'message' => 'No Data found..!',
            'data' => $data,
            'statusCode' => 400
        ],400 );
         
    }
    public function popupliststaff(Request $request)
    {
       $data =  MaintanceProcess::where('staff_id', $request->staff_id)->where('status',1)->first();
        if($data)
        {
            return response([
                'message' => 'data Displayed Successfully..!',
                'data' => $data,
                'statusCode' => 200
            ],200 );
        
        }
        return response([
            'message' => 'No Data Found..!',
            'data' => $data,
            'statusCode' => 400
        ],400 );
    }
    public function popuplistuser(Request $request)
    {
        $data = preapproval::where('status',1)->where('user_id',$request->user_id)->first();
         $data2 = Bookamenities::where('status',1)->where('user_id',$request->user_id)->first();

         $allData = [
            'preapproval' => $data,
            'book_amenities' => $data2, 
           
        ];
         
        
            return response([
                'message' => 'data Displayed Successfully..!',
                'data' => $allData,
                'statusCode' => 200
            ],200 );
        
         

         return response([
            'message' => 'No Data found..!',
            'data' => $data,
            'statusCode' => 400
        ],400 );

    }
}
