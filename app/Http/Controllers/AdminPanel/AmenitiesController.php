<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenities;
use App\Traits\ImageUpload;

class AmenitiesController extends Controller
{
           use ImageUpload;

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Amenities::latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm editProduct me-1">Edit</a>
                        <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteProduct">Delete</a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin_panel.admin.amenities');
    }

    public function edit($id)
    {
        return Amenities::findOrFail($id);
    }

    public function store(Request $request)
    {


            if ($request->hasFile('image')) 
        {
            $image_1 = $request->file('image');
            $image = $this->uploadImage($image_1, 'image'); // Pass both the file and directory
        }
   
        Amenities::updateOrCreate(
            ['id' => $request->product_id],
            [
                'amenities_name' => $request->amenities,
                'rule' => $request->rule,
                'image' => $image ?? null, // Handle image upload if provided
               
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->product_id
                ? 'Amenities updated successfully!'
                : 'Amenities created successfully!'
        ]);
    }

    public function destroy($id)
    {
        Amenities::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Amenities deleted successfully!'
        ]);
    }
    
}