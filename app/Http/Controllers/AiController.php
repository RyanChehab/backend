<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Http;


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
                
                $folder = 'RepositoryCovers';
                $filePath = $folder . '/' .uniqid() . '.png';

                // stored img temporarly in local file
                $tempFilePath = storage_path('app/temp/' . uniqid() . '.png');

                $response->save($tempFilePath);

                // upload to s3
                $disk = Storage::disk('s3');
                $disk->put($filePath,file_get_contents($tempFilePath),'public');

                // get the public url from s3
                $s3url = $disk->url($filePath);

            // when image uploaded get the url of the object from aws
            return response()->json([
                'success' => true,
                's3url' => $s3url,
            ],201);

            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to download the generated image.',
            ], 500);

        }catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
