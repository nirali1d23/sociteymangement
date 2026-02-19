<?php

namespace App\Http\Controllers\AdminPanel;

use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookamenities;

class BookedamenitiesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Bookamenities::select(
                    'bookamenities.id',
                    'bookamenities.date',
                    'bookamenities.status',
                    'users.name as user_name',
                    'amenities.amenities_name as amenities_name'
                )
                ->join('users', 'users.id', '=', 'bookamenities.user_id')
                ->join('amenities', 'amenities.id', '=', 'bookamenities.amenities_id')
                ->latest()
                ->get();

            return DataTables::of($data)->make(true);
        }

        return view('admin_panel.admin.bookamenities');
    }

    public function updatestatus(Request $request)
    {
        $amenity = Bookamenities::find($request->id);

        if ($amenity) {
            $amenity->status = $request->status;
            $amenity->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Record not found'], 404);
    }
}
