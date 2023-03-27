<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    
    public function login(Request $request)
    {
        $login = Auth::Attempt($request->all());
        if ($login) {
            $user = Auth::user();
            $user->api_token = Str::random(100);
            $user->save();
            // $user->makeVisible('api_token');

            return response()->json([
                'response_code' => 200,
                'message' => 'Login Berhasil',
                'conntent' => $user
            ]);
        }else{
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!'
            ]);
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:2','max:255'],
            'password' => ['required','min:5'],
            'fullname' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors(),  'response_code' => 500,]);
          }

          if(User::where('username',$request->username)->first()) {
            return response()->json([
                'response_code' => 409,
                'success' => false,
                'message' => 'Username sudah ada'
            ], 409);
        }else{
         $user =   User::create([
                'username'=>$request->username,
                'fullname'=>$request->fullname,
                'password'=>bcrypt($request->password)
            ]);
            return response()->json([
                'response_code' => 200,
                'success' => true,
                'message' => 'Berhasil melakukan daftar',
                'data'=> $user
            ], 200);

        }
        
     
    }
    public function user_list(){

        $user = User::all();
        if($user!=null) {
            return response()->json([
                'response_code' => 200,
                'success' => true,
                'data'=> $user
            ], 200);

    }else{
        return response()->json([
            'response_code' => 200,
            'success' => false,
            'data'=> $user
        ], 200);


    }
}
}
