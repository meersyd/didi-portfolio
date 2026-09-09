<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
            $table->text('short_description')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('role')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
            $table->text('short_description')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->string('role')->nullable(false)->change();
        });
    }
};
