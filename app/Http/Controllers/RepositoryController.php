<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller{

    public function createRepository(Request $request){
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'img_url' => 'nullable|url',
            'story_url' => 'nullable|url',
        ]);

        $repository = new Repository();

        return response()->json([
            'success' => true,
            'message' => 'Repository created successfully!',
            'data' => $repository,
        ], 201);
    }

}
