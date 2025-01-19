<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

Class BookimageService{
    protected $route = "https://fan-tales-storage.s3.eu-north-1.amazonaws.com/placeHolder/Book-placeholder(2).png";

    public function addPlaceHolderImg(){
        $books=Book::whereNull('img_url')->get();

        foreach($books as $book){
            $book->img_url = $this->route;
            $book->save();
        }
    }

}