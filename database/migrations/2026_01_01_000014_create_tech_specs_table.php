<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tech_specs', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('key');
            $table->text('value_es')->nullable();
            $table->text('value_de')->nullable();
            $table->text('value_en')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tech_specs');
    }
};
