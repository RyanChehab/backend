<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model{
    protected $fillable = ['user_id', 'bookmarkable_id', 'bookmarkable_type'];

    
    public function bookmarkable(){

        return $this->morphTo();
        
    }

    // each bookamrk belong to 1 user 
    public function user(){

    return $this->belongsTo(User::class);
    
    }                             
}
