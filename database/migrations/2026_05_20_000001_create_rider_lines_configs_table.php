<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rider_lines_configs', function (Blueprint $table) {
            $table->id();
            $table->string('config')->unique(); // duo | pieter | full
            $table->json('channels');
            $table->text('tech_notes_es')->nullable();
            $table->text('tech_notes_de')->nullable();
            $table->text('tech_notes_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rider_lines_configs');
    }
};
