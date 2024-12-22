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
            $books = $this->gutenbergService->fetchBooks();

            foreach ($books as $book){

                if(!in_array('en',$book['languages']?? [])){
                    continue;
                }

                $category = $this->determinCategory($book['bookshelves']);

                $author = $book['authors'][0]['name']?? 'Unknown',


                Book::updateOrCreate(
                    ['gutenberg_id' => $book['id']],
                [
                    'title' => $book['title'],
                    'author' => $author,
                    'category' => $category,
                    'full_text_url' => $book['formats'],
                    'image_url' => $book['formats'],
                    'downloads' => $book['download_count'],
                    'featured' => true, 
                ]
                );
            }

            return response()->json(['message' => 'Books table populated successfully!'], 200);

        }catch (\Exception $e) {
            return response()->json(['errorrr' => $e->getMessage()], 500);
        }
    }

    private function determinCategory(array $bookshelves):string{
        
        $keywords = [
            'fiction',
            'nonfiction',
            'mystery',
            'fantasy',
            'science',
            'history',
            'psychology',
            'crime',
            'literature',
        ];

        $matchedCategories = [];

        foreach($bookshelves as $shelf){
            foreach ($keywords as $keyword) {
                if (stripos($shelf,$keyword)!== false){
                    $matchedCategories[] = ucfirst($keyword);
                }
            }
        }
        return implode(', ', array_unique($matchedCategories));
    }  

    private function getUrl(array $formats){
        foreach ($formats as $formatKey => $fromatUrl){
            if(str_starts_with($formatKey, 'text/plain')){
                return $formatUrl;
            }
        }
    }

}
