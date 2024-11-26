<?php
namespace App\Http\Controllers\API\Admin;
use App\Models\Maintancebill;
use App\Models\Maintancebilllist;
use App\Models\Flat;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FirebaseNotificationTrait;

class MaintanceBillController extends Controller
{
    use FirebaseNotificationTrait;

    public function store(Request $request)
    {
        $store =  new Maintancebill;
        $store->due_date = $request->due_date;
        $store->total_amount = $request->total_amount;
        $store->member_type = $request->member_type;
        $store->billing_period = $request->billing_period;
        $store->save();

        $data = User::all();
        foreach($data as  $token)
        {
            if($token->fcm_token !=null)
            {
                $fcmToken = $token->fcm_token;
                $title = "📋 New Maintenance Bill Generated!";
                $body = "💡 Your latest maintenance bill is ready. Kindly review the details and make your payment promptly. Thank you! 🏡";
                 $this->sendFirebaseNotification($fcmToken, $title, $body);
            }
        }

        return response( [
            'message' => 'Maintance  Bill created  Successfully..!',
            'data' => $store,
            'statusCode' => 200
        ],200 );
    }
    public function maintancebilldisplay(Request $request)
    {    
        
        // $flat_no = Flat::find($request->flat_id);
        // $houses = $flat_no->houses;    
        // $houses_with_status = $houses->map(function($house) 
        // {
        //     $status = Maintancebilllist::where('flat_id', $house->id)->exists() ? 1 : 0;
        //     $house->status = $status;    
        //     return $house;
        // });
    $flat_id = $request->block_id;
            $flat_no = Flat::find($flat_id);
    $houses = $flat_no->houses;


$month = $request->month;
$year = $request->year;
$maintenanceBill = Maintancebill::whereMonth('date', $month)
    ->whereYear('date', $year)
    ->first();
    dd($maintenanceBill);

$maintenanceBillId = $maintenanceBill ? $maintenanceBill->id : null;

$houses_with_status = $houses->map(function ($house) use ($month, $year) {
    $maintenanceBill = Maintancebill::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->whereHas('maintancebilllists', function ($query) use ($house) {
            $query->where('flat_id', $house->id);
        })
        ->first();




    $house->status = $maintenanceBill ? 1 : 0;
   

    return $house;
});


        return response([
            'message' => 'House list fetched successfully',
            'maintenance_bill_id' => $maintenanceBillId,  
            'data' => $houses_with_status,
        
                      'statusCode' => 200
        ], 200);   
    
    }
    public function paymaintance(Request $request)
    {
       $data =  Maintancebilllist::create([
             'maintance_bill_id' => $request->maintance_bill_id,
             'flat_id' => $request->flat_id,
             'date' => $request->date,
             'payment_method' => $request->payment_method,
             'status' =>1


        ]);

        return response( [
            'message' => 'Maintance  Bill Payed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ],200 );
    }
}
