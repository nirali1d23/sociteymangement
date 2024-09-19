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

  
            $data = Bookamenities::latest()->get();

  

            return Datatables::of($data)

                    ->addIndexColumn()

                    ->addColumn('action', function($row)
                    {
                        $btn = '<div class="d-flex justify-content-center">';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary editProduct me-2">Edit</a>';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger  deleteProduct">Delete</a>';
                        $btn .= '</div>';

                                                    return $btn;

                                            })

                    ->rawColumns(['action'])

                    ->make(true);

        }


        return view('admin_panel.admin.bookamenities');

    }

    public function updatestatus(Request $request)
{
    $amenity = Bookamenities::find($request->id);
    if ($amenity) {
        $amenity->status = $request->status;
        $amenity->save();
        return response()->json(['success' => 'Status updated successfully']);
    }
    return response()->json(['error' => 'Amenity not found'], 404);
}

}
