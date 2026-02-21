<?php

namespace App\Http\Controllers\API\Admin;
use Auth;
use App\Models\Allotment;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Hash;
use App\Models\User;
use App\Models\Bookamenities;
use App\Models\EventFeedback;
use App\Models\maintance;
use App\Models\NoticeComment;
use App\Models\Pollsurvey;
use App\Models\Visitor;
use App\Models\preapproval;
use App\Models\Contactus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AuthController extends Controller
{

    //0 =>admin
//1 =>resident & tenant
//2 =>stafff
    //login of Reisdent,Tenant and staff
    public function login(Request $request)
    {
        $request->validate([

            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();



        if ($user != null) {
            if (!Hash::check($request->password, $user->password)) {


                return response([
                    'message' => 'Incorrect Password..!',

                    'statusCode' => 400
                ], 400);


            }

            if ($user->user_type != $request->user_type) {
                return response([
                    'message' => 'This email id not have permission',
                    'statusCode' => 200
                ], 200);

            }
            if ($request->user_type == '2') {
                $allotment = Allotment::where('user_id', $user->id)
                    ->with('flat')
                    ->first();

                $houseNumber = $allotment && $allotment->flat ? $allotment->flat->house_number : null;
            } else {
                $houseNumber = null;
            }


            $user->fcm_token = $request->fcm_token;
            $user->save();
            return response([
                'message' => 'You can Login',
                'data' => $user,
                'house_no' => $houseNumber,
                'statusCode' => 200
            ], 200);

        }

        return response([
            'message' => 'Email not found',
            'statusCode' => 404
        ], 404);


    }
    //register Resisdent ,Tenant  and worker
    public function register_rtw(Request $request)
    {

        $request->validate([

            'user_type' => 'required',

        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response([
                'message' => 'Use Another email address',
                'statusCode' => 400
            ], 400);
        }
        $save = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'user_type' => $request->user_type,
            'fcm_token' => $request->fcm_token
        ]);

        if ($save) {
            return response([
                'message' => 'User Store Successfully',
                'data' => $save,
                'statusCode' => 200
            ], 200);
        }
        return response([
            'message' => 'Unable to store user..!',
            'statusCode' => 400
        ], 400);
    }
    //import the resisent and allotment
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        if (!empty($import->errors)) {
            return response()->json([
                'status' => false,
                'message' => 'Import failed due to validation errors',
                'errors' => $import->errors
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'Users imported successfully'
        ], 200);
    }

    //change password
    public function changepassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'newpassword' => 'required',
            'oldpassword' => 'required',
            'user_type' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            if (!Hash::check($request->oldpassword, $user->password)) {
                return response([
                    'message' => 'Incorrect Password..!',
                    'statusCode' => 400
                ], 400);
            }
            if ($user->user_type != $request->user_type) {
                return response([
                    'message' => 'This email id not have permission',
                    'statusCode' => 200
                ], 200);

            }

            $user->password = Hash::make($request->newpassword);
            $user->save();

            return response([
                'message' => 'Password changed successfully',
                'data' => $user,
                'statusCode' => 200
            ], 200);


            return response([
                'message' => 'Email not found',
                'statusCode' => 404
            ], 404);
        }
    }
    //set security pin for admin
    public function securitypin(Request $request)
    {
        $request->validate([
            'securitypin' => 'required',
            'oldpin'=> 'required'
        ]);
        $securitypin = $request->securitypin;

        $data = User::find($request->user_id);


        if ($data) {


        if($data->security_pin == $request->oldpin)
            {
            $data->security_pin = $securitypin;
            $data->save();

            return response([
                'message' => 'securitypin changed successfully',
                'data' => $data,
                'statusCode' => 200
            ], 200);


        }

        return response([
            'message' => 'Enter correct pin',
            'statusCode' => 404
        ], 404);

        }



        return response([
            'message' => 'User not found',
            'statusCode' => 404
        ], 404);
    }
    public function checksecuritypin(Request $request)
    {
        $request->validate([
            'securitypin' => 'required',
        ]);
        $securitypin = $request->securitypin;

        $data = User::find($request->user_id);

        if ($data) {

            if ($data->security_pin == $securitypin) {

                return response([
                    'message' => 'Your pin is correct',
                    'data' => $data,
                    'statusCode' => 200
                ], 200);


            }

            return response([
                'message' => 'Pin is Incorrect',
                'statusCode' => 404
            ], 404);



        }
        return response([
            'message' => 'User not found',
            'statusCode' => 404
        ], 404);


    }
    public function edituser(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $user = User::find($request->id);
        if ($user) {
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('mobile_no')) {
                $user->mobile_no = $request->mobile_no;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            $user->save();
            return response([
                'message' => 'User data is updated successfully',
                'data' => $user,
                'statusCode' => 200
            ], 200);
        }
        return response([
            'message' => 'User not found',
            'statusCode' => 404
        ], 404);
    }
    public function deleteuser(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $user->delete();

            return response([
                'message' => 'User data is deleted successfully',
                'statusCode' => 200
            ], 200);
        }

        return response([
            'message' => 'User not found',
            'statusCode' => 404
        ], 404);
    }

    public function get_user(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $user = User::with([
            'allotment.flat.block'
        ])->find($request->user_id);
        if ($user) {
            return response([
                'message' => 'User found successfully',
                'data' => $user,
                'statusCode' => 200
            ], 200);
        }

        return response([
            'message' => 'User not found',
            'statusCode' => 404
        ], 404);
    }

    public function contactus(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $contact = Contactus::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        if ($contact) {
            return response([
                'message' => 'Message sent successfully',
                'data' => $contact,
                'statusCode' => 200
            ], 200);
        }

        return response([
            'message' => 'Unable to send message..!',
            'statusCode' => 400
        ], 400);
    }
    public function submitcontactus(Request $request)
    {
        // (optional but recommended)
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        Contactus::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
        ]);

        return redirect()
            ->route('contact')
            ->with('submitted', true);
    }
}
