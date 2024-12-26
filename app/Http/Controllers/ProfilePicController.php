<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilePicController extends Controller{
    public function upload(Request $request){

        try{
            $request->validate([
                'profile_pic' => 'required|file|mimes:jpg,jpeg,png|max:2048', // 2MB max
            ]);
    
            $user = JWT::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
            
            $file = $request->file('profile_pic');

            $filePath = 'ProfilePics/'. uniqid() . '.' .$file->getClientOriginalExtension();

            // identify the type of storage
            $disk = Storage::disk('s3');
            $disk->put($filePath,file_get_contents($file));

            // get the url from cloud
            $url = $disk->url($filePath);

            // Update database
            $user->avatar_url = $url;
            $user->save(); 

            return response()->json([
                'success' => true,
                'message' => 'Profile picture uploaded successfully',
                'url' => $url,
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
        // Handle validation errors
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        // Handle unexpected errors
        return response()->json([
            'success' => false,
            'message' => 'An error occurred during upload',
            'error' => $e->getMessage(),
        ], 500);
    }
    }
}
