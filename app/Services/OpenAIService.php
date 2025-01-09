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
        
    }
}