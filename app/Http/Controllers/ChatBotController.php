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
    }
}
