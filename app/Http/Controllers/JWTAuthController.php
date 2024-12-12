<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthController extends Controller{

    public function login(Request $request){
        //Validating the request
        try{
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'errors' => $e->errors()
            ]);
        };
        
        // comparing credentials
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // responding
        return response()->json([
            'token' => $token,
            'user' => JWTAuth::user(),
        ],201);
    }

    public function signup(Request $request){
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'user_type'=>'required',
                'password' => 'required|string|min:6',
               ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['errors' => $e->errors()], 422);
        }
        
        // checking if user already registered 
        $emailExists = User::where('email',$request->email)->exists();
        
        if($emailExists){
        return response()->json([
            "message" => 'User already registered'
        ],409);
        }

        // new user creation
        $user = new User();

        $user->name =$request->name;
        $user->username =$request->username;
        $user->email =$request->email;
        $user->user_type =$request->user_type;
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

    public function delete_user(Request $request){
        try{
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'errors' => $e->errors()
            ]);
        };

        $user = User::where('email',$request->email)->first();

        if (Hash::check($request->password, $user->password)){
            $user->delete();
            return response()->json([
                'message'=>'user deleted successfully'
            ]);
        }

    }

    public function block_user(Request $request){
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|string'
        ]);

    }
    
}
