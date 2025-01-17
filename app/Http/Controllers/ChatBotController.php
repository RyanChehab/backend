<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function chat(Request $request){

        $message = $request->input('message');

        if(!$message){
            return response()->json(['error' => 'Message is required'], 400);
        }
        
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        try{
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message],
                ], 
            ]);

            $reply = $response['choices'][0]['message']['content'];
            return response()->json(['reply' => $reply], 200);
            
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch response from OpenAI'], 500);
        }
    }
}
