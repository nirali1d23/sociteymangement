<?php

namespace App\Http\Controllers\API\Admin;
use Auth;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        $request->validate([

                    'email' => 'required|email',
                    'password' => 'required',
                    'user_type' => 'required',
        ]);
        $user = User::where('email',$request->email)->first();



            if($user!=null)
            {
            if (!Hash::check( $request->password, $user->password ) ) 
            
            {


                return response( [
                    'message' => 'Incorrect Password..!',
                    'statusCode' => 400
                ],400 );
                    
                
            }


            if($user->user_type != $request->user_type)
                    {
                    return response( [
                        'message' => 'This email id not have permission',
                        'statusCode' => 200
                    ], 200 );
                        
                    }
            return response( [
                'message' => 'You can Login',
                'statusCode' => 200
            ], 200 );

            }

            return response( [
            'message' => 'Email not found',
            'statusCode' => 404
        ], 404 );



    }
   //register Resisdent ,Tenant  and worker
    public function register_rtw(Request $request)
    {

        $request->validate([

        'user_type' => 'required',

        ]);

        $user = User::where('email',$request->email)->first();

        if($user)
        {
            return response( [
                'message' => 'Use Another email address',
                'statusCode' => 400
            ],400);
        }
        $save =   User::create([
            
            'name' => $request->name,
            'email' => $request->email,
            'password'=>hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'user_type' => $request->user_type,
            'fcm_token' => $request->fcm_token
        ]);

        if($save)
        {
            return response( [
                'message' => 'User Store Successfully',
                'data' => $save,
                'statusCode' => 200
            ],200);
        }
        return response( [
            'message' => 'Unable to store user..!',
            'statusCode' => 400
        ],400 );
    }    
    public function import(Request $request) 
    {
        $file = $request->file('file');
        Excel::import(new UsersImport, $file);
        
        return response( [
            'message' => 'User Store Successfully',
            'statusCode' => 200
        ],200);
    }
    public function changepassword(Request $request)
    {
            $request->validate([

            'email' => 'required|email',
            'newpassword' => 'required',
            'oldpassword' => 'required',
            'user_type' => 'required',
                    ]);
                    
             $user = User::where('email',$request->email)->first();
            if($user!=null)
            {
                if (!Hash::check($request->oldpassword, $user->password)) 
                {
                    return response( [
                        'message' => 'Incorrect Password..!',
                        'statusCode' => 400
                    ],400 );
                }
                if($user->user_type != $request->user_type)
                        {
                        return response( [
                            'message' => 'This email id not have permission',
                            'statusCode' => 200
                        ], 200 );
                            
                        }

                    $user->password = Hash::make($request->newpassword);
                    $user->save();
                    
                return response( [
                    'message' => 'Password changed successfully',
                    'data' => $user,
                    'statusCode' => 200
                ], 200 );
            

                return response( [
                    'message' => 'Email not found',
                    'statusCode' => 404
                ], 404 );
                }
    }
    public function securitypin(Request $request)
    {
       $securitypin =  $request->securitypin;

       $data = User::find($request->user_id);

       if($data)
    
        {
             $data->securitypin = $securitypin;
             $data->save();

             return response( [
                'message' => 'securitypin changed successfully',
                'data' => $data,
                'statusCode' => 200
            ], 200 );
        

        }

        
        return response( [
            'message' => 'User not found',
            'statusCode' => 404
        ], 404 );
    }

    public function checksecuritypin(Request $request)
    {
        $securitypin =  $request->securitypin;

       $data = User::find($request->user_id);

       if($data)
       {

          if($data->securitypin == $securitypin)
          {

            return response( [
                'message' => 'Your pin is correct',
                'data' => $data,
                'statusCode' => 200
            ], 200 );
        
             
          }

          return response( [
            'message' => 'Pin is Incorrect',
            'statusCode' => 404
        ], 404 );


          
       }
            return response( [
                'message' => 'User not found',
                'statusCode' => 404
                ], 404 );

    
    }

    public function edituser(Request $request)
    {
        $request->validate([

            'id' => 'required',
    
            ]);

       $user = User::find($request->id);

        if($user)
         {
              if($request->has('name'))
              {
                 $user->name = $request->name;
              }
              if($request->has('mobile_no'))
              {
                 $user->mobile_no = $request->mobile_no;
              }
              $user->save();


              return response( [
                'message' => 'User data is updated successfully',
                'data' => $user,
                'statusCode' => 200
            ], 200 );


         }

         return response( [
            'message' => 'User not found',
            'statusCode' => 404
        ], 404 );
    }
}   
