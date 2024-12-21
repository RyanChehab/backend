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
        
        $categories = [
            'fiction' => 'Fiction',
            'nonfiction' => 'Non-Fiction',
            'mystery' => 'Mystery',
            'fantasy' => 'Fantasy',
            'science' => 'Science',
            'history' => 'History',
            'psychology' => 'Psychology',
            'crime' => 'Crime',
            'literature' => 'Literature',
        ];

        $matchedCategories = [];

        foreach($bookshelves as)
    }
}
