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
         Schema::create('movies', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->integer('duration'); // в минутах
        $table->integer('year');
        $table->string('age_rating', 10);
        $table->string('poster_image');
        $table->string('trailer_url');
        $table->string('seo_title')->nullable();
        $table->text('seo_description')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
