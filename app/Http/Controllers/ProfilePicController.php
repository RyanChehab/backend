<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilePicController extends Controller
{
    public function upload(Request $request){

        try{
            $request->validate([
                'profile_pic' => 'required|file|mimes:jpg,jpeg,png|max:2048', // 2MB max
            ]);
        }catch(Illuminate\Validation\ValidationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
        

        $file = $request->file('profile_pic');

    }
}
