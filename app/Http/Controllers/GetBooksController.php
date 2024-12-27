<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GetBooksController extends Controller{
    public function getFeaturedBooks():JsonResponse{
        $featuredBooks = Book::where('featured', 1)->select('id','title','author','img_url','category', 'url_text')->get();

        return response()->json($featuredBooks);
    }
}
