<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->text('short_description');
            $table->text('description');
            $table->string('role')->default('Full Stack Developer');
            $table->json('technologies')->nullable();
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->json('features')->nullable();
            $table->text('technical_details')->nullable();
            $table->text('challenges')->nullable();
            $table->text('outcome')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->boolean('featured')->default(true);
            $table->boolean('published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
