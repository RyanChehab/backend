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
            // downloaded the image
            $response = Http::get($imageUrl);

            if ($response->successful()) {
                // Resize the image
                $resizedImage = Image::make($response->body())->resize(300, 400);
                
                $folder = 'RepositoryCovers';
                $filePath = $folder . '/' .uniqid() . '.png';
                
                // stored img temporarly in local file
                $tempFilePath = storage_path('app/temp/' . uniqid() . '.png');
                $resizedImage->save($tempFilePath);

                // upload to s3
                $disk = Storage::disk('s3');
                $disk->put($filePath,file_get_contents($tempFilePath),'public');
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
