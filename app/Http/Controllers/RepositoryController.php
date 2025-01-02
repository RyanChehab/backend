<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RepositoryController extends Controller{

    public function createRepository(Request $request){
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'img_url' => 'nullable|url',
            'story_url' => 'nullable|url',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        $repository = new Repository();

        return response()->json([
            'success' => true,
            'message' => 'Repository created successfully!',
            'data' => $repository,
        ], 201);
    }

}
