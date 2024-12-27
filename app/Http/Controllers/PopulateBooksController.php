<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\GutenbergService;
use App\Services\BookimageService;  

class PopulateBooksController extends Controller{
    
    protected $gutenbergService;
    protected $BookimageService;

    public function __construct(GutenbergService $gutenbergService, BookimageService $BookimageService){
        $this->gutenbergService = $gutenbergService;
        $this->BookimageService = $BookimageService;
    }

    public function populate(){
        try{
            $books = $this->gutenbergService->fetchBooks();

            foreach ($books as $book){

                if(!in_array('en',$book['languages']?? [])){
                    continue;
                }

                $category = $this->determinCategory($book['bookshelves']);

                $author = $book['authors'][0]['name']?? 'Unknown';

                $url = $this->getUrl($book['formats']);

                // $img_url = $this->getImgUrl($book['formats']);

                Book::updateOrCreate(
                    ['gutenberg_id' => $book['id']],
                [
                    'title' => $book['title'],
                    'author' => $author,
                    'category' => $category,
                    'url_text' => $url,
                    // 'url_img' => $book['formats'],
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

    private function getUrl(array $formats):string {

        $urlText = null;

        foreach ($formats as $formatKey => $formatUrl){
            if(str_starts_with($formatKey, 'text/plain')){
                $urlText = $formatUrl; 
                break;
            }
        }

        return $urlText;
    }
#############################################################################
                                    // Add imgs

    public function addPlaceHolderimg(){

        $this->BookimageService->addPlaceHolderImg();

        return response()->json(['message'=>'Books updated with imgs']);

    }
}
