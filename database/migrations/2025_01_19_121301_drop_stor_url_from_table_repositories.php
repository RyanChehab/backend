<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->dropColumn('story_url'); 
        });
    }

    
    public function down(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->string('story_url')->nullable();
        });
    }
};
