<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedSmallInteger('release_year');
            $table->string('cover_image')->nullable();
            $table->string('spotify_embed_url')->nullable();
            $table->text('description_es')->nullable();
            $table->text('description_de')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
