<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

Class GutenbergService{
    protected $baseUrl =  'https://gutendex.com/books/';

    public function fetchBooks(int $limit = 20):array{
    
    $books =[];
    $page = 1;

    while (count($books) < $limit){
        $response = Http::get($this->baseUrl, [
            'sort' => 'download_count',
            'page' => $page,
        ]);

    if ($response->failed()) {
        throw new \Exception('Failed to fetch data from the Gutenberg API.');
    }

    $data = $response->json();
    $books = array_merge($books, $data['results']);

    }

    $page++;
  
    return array_slice($books,0,$limit);
    }
}