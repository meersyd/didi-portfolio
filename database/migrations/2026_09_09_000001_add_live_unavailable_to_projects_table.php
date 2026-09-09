<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('live_unavailable')->default(false)->after('live_url');
        });

        DB::table('projects')
            ->whereIn('slug', ['xcellorate', 'bondacare', 'bake-by-mel'])
            ->update(['live_unavailable' => true]);
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('live_unavailable');
        });
    }
};
