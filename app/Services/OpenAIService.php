<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAiService{

    protected $baseUrl;
    protected $apiKey;

    public function __construct(){

        $this->baseUrl = env('OPENAI_BASE_URL', 'https://api.openai.com');
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function generateImg(string $promt, string $size = '400x300'): array{
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/v1/images/generations", [
            'prompt' => $prompt,
            'size' => $size,
        ]);

        if($response->successful()){
            return $response->json();
        }

        throw new \Exception('Failed to generate image: ' . $response->body());
    }
}