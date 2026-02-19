<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\preapproval;
use App\Traits\FirebaseNotificationTrait;



class PrevisitorController extends Controller
{
        use FirebaseNotificationTrait;

    public function index(Request $request)
    {

        if ($request->ajax()) {



            $data = preapproval::latest()->get();



            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary editProduct me-2">Edit</a>';
                    $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger  deleteProduct">Delete</a>';
                    $btn .= '</div>';

                    return $btn;
                })

                ->rawColumns(['action'])

                ->make(true);

        }


        return view('admin_panel.admin.previsitor');

    }
    public function updateStatus(Request $request)
    {
        $visitor = preapproval::find($request->id);

        if ($visitor) {
            $visitor->status = $request->status;
            $visitor->save();



              $user = User::find($visitor->user_id);
     
             $fcmToken = $user->fcm_token;
             if($fcmToken)
             {
               
                 if($request->status == '1')
                 {
 
                  $title = "Your Pre-Visitor Booking is Confirmed!✅";
                  $body = "Good news! Your pre-visitor booking for  visitor has been approved by our admin team. We're excited to welcome your visitor!";
                 }
 
                  else
                  {
                     $title = "Update on Your Pre-Visitor Booking Request 🚫";
                      $body = "We regret to inform you that your pre-visitor booking for visitor on could not be approved. For further assistance, please reach out to our support team. We're here to help!";
                  }
         
                 $this->sendFirebaseStaffNotification($fcmToken, $title, $body);
             }
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Visitor not found'], 404);
    }
}
