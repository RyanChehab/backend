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

    }
}
