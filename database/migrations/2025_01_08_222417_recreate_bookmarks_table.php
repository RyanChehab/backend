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
        Schema::dropIfExists('bookmarks');

        Schema::create('bookmarks', function(Blueprint $table){

        $table->id();
            $table->unsignedBigInteger('userable_id');
            $table->string('userable_type');
            $table->unsignedBigInteger('bookmarkable_id');
            $table->string('bookmarkable_type');
            $table->timestamps();

            $table->unique(['userable_id', 'userable_type', 'bookmarkable_id', 'bookmarkable_type'], 'unique_bookmark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
