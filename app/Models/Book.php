<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Book extends Model{
    use HasFactory;

    protected $fillable = [
        'gutenberg_id',
        'title',
        'author',
        'category',
        'url_text',
        'url_img',
        'downloads',
        'featured',
        'used',
    ];

    // initiate the polymorph rlnt
    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
