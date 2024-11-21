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

        $flat_no = Flat::find($request->flat_id);
$houses = $flat_no->houses;

// Assuming `month` and `year` are passed as request parameters
$month = $request->month;
$year = $request->year;

$houses_with_status = $houses->map(function($house) use ($month, $year) {
    $status = Maintancebilllist::where('flat_id', $house->id)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->exists() ? 1 : 0;

    $house->status = $status;
    return $house;
});

        return response([
            'message' => 'House list fetched successfully',
            'data' => $houses_with_status,
            'statusCode' => 200
        ], 200);   
    
    }
    public function paymaintance(Request $request)
    {
       $data =  Maintancebilllist::create([
 
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
