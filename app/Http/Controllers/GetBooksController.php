<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GetBooksController extends Controller{

    public function getFeaturedBooks():JsonResponse{
        $featuredBooks = Book::where('featured', 1)->select('id','gutenberg_id','title','author','img_url','category', 'url_text')->get()->mapWithKeys(function ($book) {
            return [$book->id => [
                'gutenberg_id' => $book->gutenberg_id,
                'title' => $book->title,
                'author' => $book->author,
                'img_url' => $book->img_url,
                'url_text' => $book->url_text,
            ]];
        });
        
        return response()->json($featuredBooks);
    }

    public function getBookByCategory()
{
    try {
        $books = Book::all(); 
        $result = [];

        foreach ($books as $book) {
            // Split categories
            $categories = explode(',', $book->category ?? '');
            $filteredCategories = collect($categories)
                // Remove whitespace
                ->map(fn($category) => trim($category)) 
                
                ->filter(fn($category) => $category !== 'Literature' && $category !== '') 
                ->values();

            // Populate the result array with books under each valid category
            foreach ($filteredCategories as $category) {
                if (!isset($result[$category])) {
                    $result[$category] = [];
                }

                $result[$category][] = [
                    'title' => $book->title,
                    'gutenberg_id' => $book->gutenberg_id,
                    'img_url' => $book->img_url,
                    'author' => $book->author,
                ];
            }
        }

        // If result is empty, return a message
        if (empty($result)) {
            return response()->json(['message' => 'No books found for the given criteria'], 404);
        }

        return response()->json($result, 200);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch books', 'details' => $e->getMessage()], 500);
    }
}

    public function showbook($gutenberg_id){
        // get the book 
        $book = Book::where('gutenberg_id', $gutenberg_id)->first();

        if(!$book){
            return response()->json(['error' => 'Book not found'],404);
        }
        // get the book api 
        $url = $book->url_text;

        $client = new Client();
        
        try{
            $response = $client->get(trim($url),[
                'stream' => true, //stream the book
                'read_timeout'=> 0,
            ]);
            $rawContent = $response->getBody()->getContents();
        }catch(\Exception $e){
            return response()->json(['error' => 'Failed to retrieve book content', 'details' => $e->getMessage()], 500);
        }

    
    return response()->json(['content' => $rawContent]);

    }

}
