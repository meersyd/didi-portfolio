<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_copies', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title')->nullable();
            $table->text('headline')->nullable();
            $table->text('tagline')->nullable();
            $table->string('location')->nullable();
            $table->string('meta_degree')->nullable();
            $table->string('meta_discipline')->nullable();
            $table->string('about_heading')->nullable();
            $table->text('about_body')->nullable();
            $table->string('currently_heading')->nullable();
            $table->text('currently_body')->nullable();
            $table->string('availability')->nullable();
            $table->json('focus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_copies');
    }
};
