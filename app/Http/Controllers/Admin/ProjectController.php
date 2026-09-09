<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['published' => true, 'featured' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        Project::query()->create($request->validated());

        return redirect()->route('admin.projects.index')->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()->route('admin.projects.index')->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project deleted.');
    }
}
