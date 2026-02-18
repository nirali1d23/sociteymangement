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
        $flats = Flat::all();
        $users = User::select('id', 'name')->where('user_type', 2)->get();

        return view('admin_panel.admin.alltoment', compact('flats', 'users'));
    }

    public function data()
    {
        return DataTables::of(
            Allotment::with(['users', 'flat.block'])
        )
            ->addColumn('user_name', fn($row) => $row->users->name)
            ->addColumn('block_number', fn($row) => $row->flat->block->block_no)
                ->addColumn('house_number', fn($row) => $row->flat->house_number)

            ->make(true);
    }


    // 🔹 Get houses by block
    public function getHouses($flat_id)
    {
        return House::where('flat_id', $flat_id)
            ->select('id', 'house_number')
            ->get();
    }
    // 🔹 Store allotment (CREATE ONLY)
   public function store(Request $request)
{
    $request->validate([
        'user_id'  => 'required',
        'house_id' => 'required'
    ]);

    Allotment::create([
        'user_id' => $request->user_id,
        'flat_id' => $request->house_id, // house id stored here
    ]);

    return response()->json(['success' => true]);
}
}
