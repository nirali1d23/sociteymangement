<?php
namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\User;
use App\Models\Flat;
use DataTables;

class AllotmentController extends Controller
{
  public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Flat::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('flat_number', function ($row) {
                    return $row->flat_number ?? 'N/A';
                })

                ->addColumn('floor_number', function ($row) {
                    return $row->floor_number ?? 'N/A';
                })

                ->addColumn('block_number', function ($row) {
                    return $row->block_no ?? 'N/A';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <a href="javascript:void(0)" 
                           data-id="'.$row->id.'" 
                           class="btn btn-sm btn-primary editFlat">Edit</a>

                        <a href="javascript:void(0)" 
                           data-id="'.$row->id.'" 
                           class="btn btn-sm btn-danger deleteFlat">Delete</a>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin_panel.admin.flat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'flat_id' => 'required'
        ]);

        Allotment::updateOrCreate(
            ['id' => $request->product_id],
            [
                'user_id' => $request->user_id,
                'flat_id' => $request->flat_id,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return response()->json(Allotment::find($id));
    }

    public function destroy($id)
    {
        Allotment::find($id)->delete();
        return response()->json(['success' => true]);
    }
}
