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
                'gutenber_id' => $book->gutenberg_id,
                'title' => $book->title,
                'author' => $book->author,
                'img_url' => $book->img_url,
            ]];
        });
        
        return response()->json($featuredBooks);
    }

    public function showbook($gutenberg_id){
        // get the book 
        $book = Book::where('gutenberg_id', $gutenberg_id)->first();

        if(!$book){
            return response()->json(['error' => 'Book not found'],404);
        }
        // get the book api 
        $url = $book->url_text;
        
        try{

        }catch(\Exception $e){
            return response()->json(['error' => 'Failed to retrieve book content', 'details' => $e->getMessage()], 500);
        }
    }

}
