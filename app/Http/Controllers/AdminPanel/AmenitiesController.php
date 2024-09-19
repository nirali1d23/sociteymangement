<?php

namespace App\Http\Controllers\AdminPanel;
use DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenities;

class AmenitiesController extends Controller
{
    public function index(Request $request)
    {
       
        if ($request->ajax()) {

  
            $data = Amenities::latest()->get();

  

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


        return view('admin_panel.admin.amenities');

    }



    public function store(Request $request)
    {

       
        Amenities::updateOrCreate([
            'id' => $request->product_id
        ],
        [
            'amenities_name' => $request->amenities,
            'rule' => $request->rule,
            'image' => $request->image,
           
        ]);        
        return response()->json(['success' => true, 'message' => 'Amenities saved successfully.']);
    }


 










}
