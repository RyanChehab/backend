<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GetBooksController extends Controller{
    public function getFeaturedBooks():JsonResponse{
        $featuredBooks = Book::where('featured', 1)->get();

        return response()->json($featuredBooks);
    }
}
