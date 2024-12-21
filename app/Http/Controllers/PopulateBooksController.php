<?php

namespace App\Http\Controllers;

use App\Models\Book;
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

            foreach ($books as $book){

                if(!in_array('en',$book['languages']?? [])){
                    continue;
                }

                $category = $this->determinCategory($book[bookshelves]);

                Book::updateOrCreate();
            }
        }catch(){

        }
    }

    private function determinCategory(array $bookshelves):string{
        
        $bookshelves = [
            "Best Books Ever Listings",
            "Browsing: Crime/Mystery",
            "Browsing: Fiction",
            "Browsing: Literature",
            "Browsing: Psychiatry/Psychology",
            "Crime Fiction",
            "Harvard Classics"
        ];
        
    }
}
