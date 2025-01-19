<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AiController extends Controller{

    protected $openAIService;

    public function __construct(OpenAiService $openAIService){
        $this->openAIService = $openAIService;
    }

    public function generateAndStoreImage(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'prompt' => 'required|string',
    ]);

    try {
        // Initialize the OpenAI service
        $openAiService = new OpenAiService();

        // Generate the image using the provided prompt
        $imageUrls = $openAiService->generateImg($validated['prompt']);

        // Validate the response
        if (empty($imageUrls) || !isset($imageUrls[0])) {
            throw new \Exception('No image URL returned from OpenAI.');
        }

        // Get the first image URL
        $imageUrl = $imageUrls[0];

        // Download the image content
        $response = Http::get($imageUrl);
        if (!$response->successful()) {
            throw new \Exception('Failed to download the generated image.');
        }

        // Define S3 folder and file path
        $folder = 'RepositoryCovers';
        $fileName = uniqid() . '.png';
        $filePath = $folder . '/' . $fileName;
        
        // Upload the image to S3
        $disk = Storage::disk('s3');
        $uploaded = $disk->put($filePath, $response->body());

        if (!$uploaded) {
            throw new \Exception('Failed to upload the image to S3.');
        }

        // Get the public S3 URL
        $s3url = $disk->url($filePath);

        // Return the permanent S3 URL
        return response()->json([
            'success' => true,
            's3url' => $s3url,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

}