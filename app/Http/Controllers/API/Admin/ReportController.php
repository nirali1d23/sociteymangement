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
        switch ($type) {
            case 'ammenties': 
           
                $ammenties = Bookamenities::whereBetween('date', [$startDate, $endDate])->get();
                $responseData[] = $ammenties->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
                        'user_id' => $item->user_id,
                        'user_name' => $item->user->name ?? 'N/A', // User name (if user exists)
                        'amenities_id' => $item->amenities_id,
                        'amenities_name' => $item->amenity->amenities_name ?? 'N/A', // Amenity name (if amenity exists)
                    ];
                });
                break;

            case 'maintance_bil':


                $data = Maintancebilllist::whereBetween('date', [$startDate, $endDate])->get();
                $responseData[] = $data->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
                        'flat_id' => $item->flat_id,
                        'House_number' => $item->flat->house_number ?? 'N/A', // User name (if user exists)
                        'status' => $item->status ?? 'N/A', // User name (if user exists)
                       
                    ];
                });
                break;


            case 'visitor':
                
                $data = Visitor::whereBetween('date', [$startDate, $endDate])->get();
                $responseData[] = $data->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'date' => $item->date,
                        'visitor_name' => $item->visitor_name,
                        'flat_id' => $item->flat_no,
                        'check_in' => $item->check_in,
                        'purpose' => $item->purpose, // User name (if user exists)
                       
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
}
