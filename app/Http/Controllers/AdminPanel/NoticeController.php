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
            return DataTables::of(Notice::latest())
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-primary btn-sm editProduct" data-id="'.$row->id.'">Edit</button>
                        <button class="btn btn-danger btn-sm deleteProduct" data-id="'.$row->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin_panel.admin.notice');
    }

    public function edit($id)
    {
        return Notice::findOrFail($id);
    }

    public function store(Request $request)
    {
        Notice::updateOrCreate(
            ['id' => $request->notice_id],
            [
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'time' => $request->time,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->notice_id
                ? 'Notice updated successfully!'
                : 'Notice created successfully!'
        ]);
    }

    public function destroy($id)
    {
        Notice::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notice deleted successfully!'
        ]);
    }
}
