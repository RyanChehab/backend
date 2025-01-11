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
        $repository->title = $validatedData['title'];
        $repository->description = $validatedData['description'] ?? null;
        $repository->img_url = $validatedData['img_url'] ?? null;
        $repository->story_url = $validatedData['story_url'] ?? null;
        $repository->user_id = $user->id;
        $repository->save();

        return response()->json([
            'success' => true,
            'message' => 'Repository created successfully!',
            'data' => $repository,
        ], 201);
    }

    public function updateRepository(Request $request){
        $validated = $request->validate([
            's3url' => 'required'
        ]);
    }

    public function getRepositories(){
        try {
            $user = auth()->user();

            $repositories = $user->repositories;
    
            return response()->json([
                'success' => true,
                'repositories' => $repositories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
