<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

Class GutenbergService{
    protected $baseUrl =  'https://gutendex.com/books/';

    public function fetchBooks(int $limit = 16):array{

    $response = Http::get($this->baseUrl, [
        'sort' => 'download_count'
    ]);

    if ($response->failed()) {
        throw new Exception('Failed to fetch data from the Gutenberg API.');
    }

    $books = $response->json('results');

    }
}