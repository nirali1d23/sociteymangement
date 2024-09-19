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
   public function login(Request $request)
   {
        $request->validate([

                 'email' => 'required|email',
                 'password' => 'required',

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
            'user_type' => $request->user_type
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


   


    



    


}   
