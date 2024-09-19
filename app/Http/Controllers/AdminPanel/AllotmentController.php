<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;

class AllotmentController extends Controller
{
    public function index(Request $request)
    {
       

       
        if ($request->ajax()) {

  
            $data = Allotment::latest()->get();

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

                ->addColumn('user_name', function($row) {
                    return $row->users->name ?? 'N/A'; // Access user name directly
                })  
                ->addColumn('flat_number', function($row) {
                    return $row->flat->flat_number ?? 'N/A'; // Access user name directly
                })                         
                    ->rawColumns(['action'])

                    ->make(true);

        }


        return view('admin_panel.admin.alltoment');

    }
}
