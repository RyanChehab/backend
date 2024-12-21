<?php

namespace App\Http\Controllers;

use App\Models\books;
use Illuminate\Http\Request;
use App\Services\GutenbergService;

class PopulateBooksController extends Controller{
    
    protected $gutenbergService;

    public function __construct(GutenbergService $gutenbergService){

        $this->gutenbergService = $gutenbergService;
    
    }

    public function populate(){
        try{
            $books = $this->$gutenbergService->fetchBooks();
            
        }
    }
}
