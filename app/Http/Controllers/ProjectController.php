<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::query()->published()->ordered()->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->published, 404);

        return view('projects.show', [
            'project' => $project,
            'nextProject' => $project->nextPublished(),
        ]);
    }
}
