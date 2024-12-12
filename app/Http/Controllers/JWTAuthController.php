<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JWTAuthController extends Controller{

    public function login(Request $request){
        //Validating the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => JWTAuth::user(),
        ]);
    }

    public function signup(Request $request){
        $request->validate([
         'name' => 'required|string|max:255',
         'username' => 'required|string|max:255',
         'email' => 'required|string|email|max:255',
         'password' => 'required|string|min:6',
        ]);

        $emailExists = User::where('email',$request->email)->exists();
       
        if($emailExists){
         return response()->json([
             "message" => 'User already registered'
         ],409);
        }

        $user = new User();

       $user->name =$request->name;
       $user->username =$request->username;
       $user->email =$request->email;
       $user->password = Hash::make($request->password);

       $user->save();
       //generating jwt 
       $token = JWTAuth::fromUser($user);

       return response()->json([
        'message' => 'User successfully registered',
        'user' => $user,
        'token' => $token,
    ], 201); 

    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
    
}
