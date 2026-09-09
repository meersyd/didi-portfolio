<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_copies', function (Blueprint $table) {
            $table->string('resume_path')->nullable()->after('focus');
        });

        $public = public_path('resume.pdf');

        if (! is_file($public)) {
            return;
        }

        $directory = storage_path('app/private/resumes');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        copy($public, $directory.'/resume.pdf');

        DB::table('site_copies')->update(['resume_path' => 'resumes/resume.pdf']);
    }

    public function down(): void
    {
        Schema::table('site_copies', function (Blueprint $table) {
            $table->dropColumn('resume_path');
        });
    }
};
