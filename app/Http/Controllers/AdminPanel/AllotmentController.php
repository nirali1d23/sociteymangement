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

            $data = Allotment::with(['users', 'flat'])->latest();

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('user_name', function ($row) {
                    return $row->users->name ?? 'N/A';
                })

                ->addColumn('flat_number', function ($row) {
                    return $row->flat->block_no ?? 'N/A';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex">
                            <button data-id="' . $row->id . '" class="btn btn-primary btn-sm editProduct me-2">Edit</button>
                            <button data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteProduct">Delete</button>
                        </div>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        $users = User::select('id', 'name')->get();
        $flats = Flat::select('id', 'block_no')->get();

        return view('admin_panel.admin.alltoment', compact('users', 'flats'));
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
