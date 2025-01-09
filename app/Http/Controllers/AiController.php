<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class AiController extends Controller{

    protected $openAIService;

    public function __construct(OpenAiService $openAIService){
        $this->openAaIService = $openAIService;
    }

    public function generateImage(Request $request){

        $validated = $request->validate([
            'promt' => 'required|string',
        ]);
        
        try{
            $imageData = $this->openAIService->generateImage($validated['promt']);

            return response()->json([
                'success' => true,
                'data' => $imageData,
            ]);
        }catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
