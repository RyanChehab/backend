<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilePicController extends Controller
{
    public function upload(Request $request){

        $request->validate([
            'profile_pic' => 'required|file|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        $file = $request->file('profile_pic');
        
    }
}
