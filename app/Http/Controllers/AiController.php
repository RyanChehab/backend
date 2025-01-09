<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class AiController extends Controller{

    protected $openAIService;

    public function __construct(OpenAiService $openAIService){
        $this->openAaIService = $openAIService;
    }
}
