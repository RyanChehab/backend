<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books',function(Blueprint $table){
            $table->id();
            $table->integer('gutenberg_id')->unique(); // ID from Gutenberg
            $table->string('title');
            $table->string('author'); 
            $table->string('category')->nullable(); 
            $table->text('url_text');
            $table->text('url_img');
            $table->integer('downloads')->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
