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

        $store = new Maintancebill;
        $store->due_date = $request->due_date;
        $store->total_amount = $request->total_amount;
        $store->title = $request->title;
        $store->created_at = $request->created_at;
        $store->save();
        $data = User::where('user_type', 2)->get();
        foreach ($data as $token) {
            if ($token->fcm_token != null) {
                $fcmToken = $token->fcm_token;
                $title = "📋 New Maintenance Bill Generated!";
                $body = "💡 Your latest maintenance bill is ready. Kindly review the details and make your payment promptly. Thank you! 🏡";
                $this->sendFirebaseNotification($fcmToken, $title, $body);
            }
        }
        return response([
            'message' => 'Maintance  Bill created  Successfully..!',
            'data' => $store,
            'statusCode' => 200
        ], 200);
    }
    public function maitnacebilldropdown(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $title = [];
        $maintenanceBill = Maintancebill::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();
        foreach ($maintenanceBill as $item) {
            $title[] = [
                "title" => $item->title,
                "id" => $item->id
            ];
        }
        return response([
            'message' => 'bill list given',
            'data' => $title,
            'statusCode' => 200
        ], 200);
    }
    public function maintancebilldisplay(Request $request)
    {


        $flat_id = $request->block_id;
        $flat_no = Flat::find($flat_id);
        $houses = $flat_no->houses;


        $month = $request->month;
        $year = $request->year;
        $maintenanceBill = Maintancebill::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)->where('id', $request->maintance_id)
            ->first();

        if ($maintenanceBill) {


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
                'block' => $flat_no,

                'statusCode' => 200
            ], 200);
        }

        return response([
            'message' => 'Maitance Bill not Found',
            'statusCode' => 404
        ], 404);

    }
    //maintance bill payment ->user site
    public function paymaintance(Request $request)
    {
        $data = Maintancebilllist::create([
            'maintance_bill_id' => $request->maintance_bill_id,
            'flat_id' => $request->flat_id,
            'date' => $request->date,
            'payment_method' => $request->payment_method,
            'status' => 1
        ]);
        return response([
            'message' => 'Maintance  Bill Payed Successfully..!',
            'data' => $data,
            'statusCode' => 200
        ], 200);
    }



}
