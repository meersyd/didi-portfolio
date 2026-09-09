<?php

namespace Tests\Unit;

use App\Models\Project;
use Tests\TestCase;

class ProjectFeaturesTest extends TestCase
{
    public function test_titled_features_keep_commas_in_the_body(): void
    {
        $project = new Project([
            'features' => [
                '* **Four Skill Games** — Bug Hunter, Flexbox Meow, and Server Panic.',
                '* **Role-Based Platform** — Dedicated dashboards for students, lecturers, and admins.',
            ],
        ]);

        $this->assertSame([
            [
                'title' => 'Four Skill Games',
                'body' => 'Bug Hunter, Flexbox Meow, and Server Panic.',
            ],
            [
                'title' => 'Role-Based Platform',
                'body' => 'Dedicated dashboards for students, lecturers, and admins.',
            ],
        ], $project->featureItems());
    }

    public function test_plain_sentences_stay_intact(): void
    {
        $project = new Project([
            'features' => [
                'Daily check-ins, streaks, and spin rewards.',
                'Mentors can review attempts without touching the database.',
            ],
        ]);

        $this->assertSame([
            ['title' => null, 'body' => 'Daily check-ins, streaks, and spin rewards.'],
            ['title' => null, 'body' => 'Mentors can review attempts without touching the database.'],
        ], $project->featureItems());
    }
}
