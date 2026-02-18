<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;
use App\Models\Notice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
       
        if ($request->ajax()) {

  
            $data = Notice::latest()->get();

  

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


        return view('admin_panel.admin.notice');

    }

    public function store(Request $request)
    {
        $notice = new Notice();
        $notice->title = $request->input('title');
        $notice->description = $request->input('description');
        $notice->start_date = $request->input('start_date');
        $notice->time = $request->input('time');
        $notice->save();

        return redirect()->route('notice')->with('success', 'Notice created successfully.');
    }
}
