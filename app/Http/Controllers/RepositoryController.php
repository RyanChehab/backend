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
        $repository->user_id = $user->id;
        $repository->save();

        return response()->json([
            'success' => true,
            'message' => 'Repository created successfully!',
            'data' => $repository,
        ], 201);
    }

    // insert the generated img
    public function updateRepository(Request $request,$id){

        $validated = $request->validate([
            's3url' => 'required'
        ]);
        
        try{
            $repository = Repository::findOrFail($id);

            $repository->update([
                'img_url' => $validated['s3url'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Repository updated successfully.',
                'repository' => $repository,
            ], 200);
            
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to update repository: ' . $e->getMessage(),
            ], 500);
        }
    }

    // fetch all repositories
    public function getReaderRepositories(){
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $repositories = Repository::withCount(['bookmarks as bookmark_count' => function ($query) {
                $query->where('bookmarkable_type', 'App\\Models\\Repository');
            }])
            ->orderByDesc('bookmark_count')
            ->get();
            
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

    // fetch all repositories for a specific writer 
    public function getRepositories(){
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            $id = $user->id;
            
            $repositories = Repository::where('user_id', $id)->get();
            
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
