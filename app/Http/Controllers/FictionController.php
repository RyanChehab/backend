<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FictionController extends Controller{
    
    public function storeFiction(Request $request,$id){

        $validated = $request->validate([
            'content'=> 'required|string',
        ]);

        
    }
}
