<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Book;

Class BookimageService{
    protected $route = "https://fan-tales-storage.s3.eu-north-1.amazonaws.com/placeHolder/Book-placeholder(2).png";

    public function addPlaceHolderImg(){
        $books=Book::whereNull('img_url')->get();

        foreach($books as $book){
            $book->img_url = $route;
            $book->save();
        }
    }
}