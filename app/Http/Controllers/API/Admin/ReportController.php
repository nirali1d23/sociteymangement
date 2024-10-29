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
class ReportController extends Controller
{
    public function report(Request $request)
    {
        $type = $request->type;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $startDate = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');
        $responseData = []; 
        
        switch ($type) 
        {
            case 'ammenties':    
                $ammenties = Bookamenities::whereBetween('date', [$startDate, $endDate])->get();
                $responseData = $ammenties->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
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

    public function popuplist(Request $request)
    {
      
         $data = preapproval::where('status',0)->first();
         $data2 = Bookamenities::where('status',0)->first();
         $data3 = maintance::where('status',0)->first();


         $allData = [
            'preapproval' => $data,
            'book_amenities' => $data2,
            'maintenance' => $data3
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
