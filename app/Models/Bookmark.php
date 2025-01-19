<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model{
    protected $fillable = [
        'userable_id',
        'userable_type',
        'bookmarkable_id',
        'bookmarkable_type'
    ];

    public function user(){
        $this->belongsTo(User::class);
    }
    // polymorphic relationship to the bookmarkable item  
    public function bookmarkable(){

        return $this->morphTo();
        
    }
                      
}
