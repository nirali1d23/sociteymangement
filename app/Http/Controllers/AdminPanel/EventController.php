<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
class EventController extends Controller
{
    public function index(Request $request)
    {
              
        if ($request->ajax()) {

  
            $data = Event::latest()->get();

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


        return view('admin_panel.admin.event');

    }
}
