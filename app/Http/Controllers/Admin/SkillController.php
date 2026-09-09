<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        return view('admin.skills.index', [
            'skills' => Skill::query()->ordered()->get()->groupBy('category'),
        ]);
    }

    public function create(): View
    {
        return view('admin.skills.form', [
            'skill' => new Skill(['sort_order' => 0, 'category' => 'Frontend']),
        ]);
    }

    public function store(SkillRequest $request): RedirectResponse
    {
        Skill::query()->create($request->validated());

        return redirect()->route('admin.skills.index')->with('status', 'Skill created.');
    }

    public function edit(Skill $skill): View
    {
        return view('admin.skills.form', [
            'skill' => $skill,
        ]);
    }

    public function update(SkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->validated());

        return redirect()->route('admin.skills.index')->with('status', 'Skill updated.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('status', 'Skill deleted.');
    }
}
