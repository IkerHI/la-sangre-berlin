<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concerts', function (Blueprint $table) {
            $table->id();
            $table->string('bandsintown_id')->unique()->nullable();
            $table->dateTime('date');
            $table->string('venue_name');
            $table->string('city');
            $table->string('country');
            $table->string('ticket_url')->nullable();
            $table->enum('status', ['upcoming', 'past'])->default('upcoming');
            $table->unsignedInteger('views')->default(0);
            $table->string('title_es')->nullable();
            $table->string('title_de')->nullable();
            $table->string('title_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concerts');
    }
};
