<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthController extends Controller{

    public function login(Request $request){
        //Validating the request
        try{
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
                'blocked' => 'false',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => $e->errors()
            ]);
        };
        
        // comparing credentials
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::where('email',$request->email)->first(); 
        if($user && $user->blocked){
            return response()->json([
                'message' => 'user blocked'
            ],403);
        }

        // responding
        return response()->json([
            'message' => 'Login Successfull',
            'token' => $token,
            'user' => JWTAuth::user(),
        ],201);
    }

######################################################################
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
            return response()->json(['error' => $e->errors()], 422);
        }
        
        // checking if user already registered 
        $emailExists = User::where('email',$request->email)->exists();

        // add checking method if username exists onchange
        // $username_taken
        
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

######################################################################

    public function logout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
        }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['message' => 'Token was invalid, but logout was successful'], 200);
        }catch(Illuminate\JWTAuth\Exception $e){
            return response()->json(['message'=>'Token is missing, but logout was successfull']);
        }catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired, but logout was successful'], 200);
        }
        
        return response()->json(['message' => 'Successfully logged out'],200);
    }

######################################################################

    public function delete_user(Request $request){
        try{
            $request->validate([
                'email' => 'required|string|email|max:255',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'errors' => $e->errors()
            ],500);
        };

        $user = User::where('email',$request->email)->first();

        if ($user){
            $user->delete();
            return response()->json([
                "success"=> true,
                'message'=>'user deleted successfully'
            ],200);
        }else{
            return response()->json([
                'message'=>'user not found'
            ],401);
        }
    }

######################################################################

    public function block_user(Request $request){
        $request->validate([
            'email'=> 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

            if(!$user){
                return response()->json([
                    'message'=>'User not found'
                ],404);

            }else{
                $user->blocked= true;
                $user->save();
                return response()->json([
                    'message'=>"User {$user->name} blocked"
                ],200);
            }
    }

######################################################################

public function Unblock_user(Request $request){
    $request->validate([
        'email'=> 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if(!$user){
        return response()->json([
            'message'=>'User not found'
        ],404);

    }else{
        $user->blocked= false;
        $user->save();
        return response()->json([
            'message'=>"User {$user->name} unblocked"
        ],200);
    }
}

######################################################################

    public function AddAdmin(Request $request){

        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error' => $e->errors()], 422);
        }

        // checking if user already registered 
        $emailExists = User::where('email',$request->email)->exists();

        if($emailExists){
        return response()->json([
            "message" => 'Admin already registered'
        ],409);
        }

        // creating admin acc
        $user = new User();

        $user->name =$request->name;
        $user->username ="admin";
        $user->email =$request->email;
        $user->user_type =$request->user_type;
        $user->password = Hash::make($request->password);

        $user->save();

        $users = User::all();
        return response()->json([
        'message' => 'Admin created successfully',
        'user' => $user,
        'users' => $users,
        ], 201);
    }
    
######################################################################

    public function resetPassword(Request $request){
        // $request->validate([
        //     'email'=>'required|email'
        // ]);
        $user = new User();

        $user = User::where('email',$request->email)->first();
        
        if(!$user){
            return response()->json(['message'=>"User not found"]);
        }

        $token = bin2hex(random_bytes(32)); 

        $expiresAt = Carbon::now()->addMinutes(15);

        // ~ 
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email], // Match email
            [
                'token' => $token,
                'expires_at' => $expiresAt,
                'used' => false
            ]
        );

        // reset base link
        $resetLink = url('/reset-password?token=' . $token);
        
        // Send the reset link via email
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($resetLink));
            return response()->json(['message' => 'Password reset link sent successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        }

    public function getAllUsers(){
        
        $users = User::all();

        return response()->json([
            'message' => 'success',
            'data' => $users,
        ],200); 
    }
}
