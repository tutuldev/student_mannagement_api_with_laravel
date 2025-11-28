<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Login logic will be implemented here
        // return $request->all();
        $validate= Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);
        if($validate->fails()){
            return response()->json([
              'errors' => $validate->errors(),
            ], 422);
        }
        $reqData = $request->only('email', 'password');
        if(Auth::attempt($reqData)){
            $user = Auth::user();
            $data['token-type'] = 'Bearer';
            $data['access_token'] = $user->createToken('userToken')->accessToken;
            $data['user'] = $user;

            return response()->json($data, 200);
        }else{
            $data['loginError'] = 'Email or Password is incorrect';

            return response()->json([
                'errors' => $data,
            ], 401);
        }
    }

       public function register(Request $request)
    {

        // return $request->all();
        $validate= Validator::make($request->all(), [
            'name'=> 'required|min:4',
            'phone'=> 'required|max:14|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        if($validate->fails()){
            return response()->json([
              'errors' => $validate->errors(),
            ], 422);
        }
        $reqData = $request->only('name','phone','email', 'password');
            $reqData['password'] = Hash::make($request->password);
            $user= User::create($reqData);
            Auth::login($user);
            $data['token-type'] = 'Bearer';
            $data['access_token'] = $user->createToken('userToken')->accessToken;
            $data['user'] = $user;
            return response()->json($data, 200);

    }
    // public function test()
    // {
    // //    return 'tutul vai'; //html response
    // return response()->json('w3 coders'); //json response
    // }
    public function logout()
    {
         Auth::user()->token()->revoke();
         return response()->json(['message' => 'Successfully logged out'], 200);
    //    return 'tutul vai'; //html response
    // return response()->json('w3 coders'); //json response
    }
}
