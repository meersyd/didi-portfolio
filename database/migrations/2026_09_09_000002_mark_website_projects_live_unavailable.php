<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $projects = DB::table('projects')->select('id', 'category')->get();

        foreach ($projects as $project) {
            $category = strtolower((string) $project->category);

            if (str_contains($category, 'mobile')) {
                continue;
            }

            DB::table('projects')->where('id', $project->id)->update([
                'live_unavailable' => true,
            ]);
        }
    }

    public function down(): void
    {
        // Keep recruiter notes in place; toggling off is an admin action.
    }
};
