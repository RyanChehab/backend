<?php

namespace App\Services;

use Exception;
use OpenAI;


class OpenAiService{

    public function generateImg(string $prompt, string $size = '1024x1024'){
        $key = env('OPENAI_API_KEY');
        $client = OpenAI::client($key);

        try{
            $result = $client->images()->create([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => $size,
                'response_format' => 'url',
            ]);
    
            $urls = array_map(fn($data) => $data['url'], $result['data']);
    
            return $urls;
        }catch(\Exception $e){
            throw new \Exception('img generation failed: '. $e->getMessage());
        }
        
    }
}