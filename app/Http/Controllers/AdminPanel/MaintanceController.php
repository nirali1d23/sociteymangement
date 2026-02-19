<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\maintance;
use App\Models\MaintanceProcess;
use App\Models\User;

class MaintanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = maintance::with([
                'user.allotment.flat.block'
            ])->latest();

            return DataTables::of($data)

                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? '-';
                })

                ->addColumn('flat_no', function ($row) {

                    $allotment = $row->user->allotment ?? null;

                    if (!$allotment || !$allotment->flat) {
                        return '-';
                    }

                    $house = $allotment->flat;
                    $block = $house->block ?? null;

                    if (!$block) {
                        return $house->house_number;
                    }

                    return $block->block_no . '-' . $house->house_number;
                })

                ->make(true);
        }

        return view('admin_panel.admin.maintance');
    }


    public function staffList()
    {
        return User::where('user_type', 3)->select('id', 'name')->get();
    }
    public function assigntostaff(Request $request)
    {
        $maintance = maintance::find($request->maintance_id);

        if ($maintance) {
            $maintance->status = 1;
            $maintance->save();
        }

        MaintanceProcess::updateOrCreate(
            [
                'maintance_request_id' => $request->maintance_id
            ],
            [
                'staff_id' => $request->staff_id,
                'status' => 1
            ]
        );

        // $user = User::where('id',$request->staff_id)
        //             ->where('user_type',3)
        //             ->first();

        // if ($user && $user->fcm_token) {
        //     $this->sendFirebaseStaffNotification(
        //         $user->fcm_token,
        //         "New Maintenance Task 🚀",
        //         "A new maintenance request has been assigned to you."
        //     );
        // }

        return response()->json([
            'message' => 'Maintenance Assigned Successfully',
            'statusCode' => 200
        ]);
    }

}

