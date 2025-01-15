<?php

namespace App\Http\Controllers;


use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FictionController extends Controller{
    
    public function storeFiction(Request $request,$id){

        $validated = $request->validate([
            'content'=> 'required|string',
        ]);

        try{
            $filename = "repository_{$id}.txt";
            $filePath = "Fictions/{$filename}";

            Storage::disk('s3')->put($filePath, $validated['content']);

            return response()->json([
                'success' => true,
                'message' => 'Fiction stored successfully!',
                'url' => Storage::disk('s3')->url($filePath), // Public URL of the file
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Failed to store fiction: ' . $e->getMessage(),
            ], 500);                
        }

    }

    public function getFiction($id){
        try {
            // Define the file path
            $fileName = "repository_{$id}.txt";
            $filePath = "Fictions/{$fileName}";
    
            // Check if the file exists
            if (!Storage::disk('s3')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fiction not found.',
                ], 404);
            }
    
            // Get the fiction
            $content = Storage::disk('s3')->get($filePath);
    
            return response()->json([
                'success' => true,
                'content' => $content,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fiction: ' . $e->getMessage(),
            ], 500);
        }
    }

    
    // delete repo
    public function deleteRepo($id){
        $repo = Repository::findOrFail($id);

        $repo->delete();

        if($repo){
            return response()->json([
                'success'=>true,
                'message'=> 'deleted repo',
            ],200);
        }
    }

}
