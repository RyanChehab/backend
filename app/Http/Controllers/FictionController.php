<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FictionController extends Controller{
    
    public function storeFiction(Request $request,$id){

        $validated = $request->validate([
            'content'=> 'required|string',
        ]);

        try{
            $filename = 'repository_{$id}.txt';
            $filePath = "Fiction/{$filename}";

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
}
