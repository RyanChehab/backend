<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

class AiController extends Controller{

    protected $openAIService;

    public function __construct(OpenAiService $openAIService){
        $this->openAIService = $openAIService;
    }

    public function generateImage(Request $request){

        $validated = $request->validate([
            'prompt' => 'required|string',
        ]);
        
        try{
            $imageData = $this->openAIService->generateImg($validated['prompt']);

            // accessing the image url generated
            $imageUrl = $imageData[0];
            $response = Http::get($imageUrl);

            if ($response->successful()) {
                // Resize the image
                $resizedImage = Image::make($response->body())->resize(300, 400);
    
                
            }

            return response()->json([
                'success' => true,
                'data' => $imageData,
            ],201);
        }catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
