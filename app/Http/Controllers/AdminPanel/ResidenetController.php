<?php
namespace App\Http\Controllers\AdminPanel;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use session;
use Hash;
use App\Models\User;    
class ResidenetController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

  

            $data = User::where('user_type','!=',0)->latest()->get();

  

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


        return view('admin_panel.admin.resident');

    }
    public function store(Request $request)
    {
        User::updateOrCreate([
            'id' => $request->product_id
        ],
        [
            'name' => $request->name,
            'email' => $request->email,
            'password' =>Hash::make($request->password),
            'mobile_no' => $request->mobile_number,
            'user_type' => $request->user_type,
        ]);        
        return response()->json(['success' => true, 'message' => 'User saved successfully.']);
    }
    public function edit($id)
    {
        $product = User::find($id);
        return response()->json($product);
    }
    public function delete($id)
    {
                User::find($id)->delete();
      
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
