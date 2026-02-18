<?php
namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\Flat;
use App\Models\House;
use App\Models\User;
use DataTables;

class AllotmentController extends Controller
{

public function index()
{
    $flats = House::select('id','house_number')->get();
    $users = User::select('id','name')->get();

    return view('admin_panel.admin.alltoment', compact('flats','users'));
}

public function data()
{
    return DataTables::of(
        Allotment::with(['users','flat'])
    )
    ->addColumn('user_name', fn($row) => $row->users->name)
    ->addColumn('flat_number', fn($row) => $row->flat->house_number)
    ->make(true);
}


    // 🔹 Get houses by block
    public function getHouses($block_id)
    {
        return House::where('flat_id', $block_id)->get();
    }

    // 🔹 Store allotment (CREATE ONLY)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'flat_id' => 'required'
        ]);

        $allotment = new Allotment;
        $allotment->user_id = $request->user_id;
        $allotment->flat_id = $request->flat_id;
        $allotment->save();

        return response()->json(['success' => true]);
    }
}
