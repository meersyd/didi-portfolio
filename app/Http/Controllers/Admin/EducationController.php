<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EducationRequest;
use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function index(): View
    {
        return view('admin.education.index', [
            'records' => Education::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.education.form', [
            'record' => new Education,
        ]);
    }

    public function store(EducationRequest $request): RedirectResponse
    {
        Education::query()->create($request->validated());

        return redirect()->route('admin.education.index')->with('status', 'Education created.');
    }

    public function edit(Education $education): View
    {
        return view('admin.education.form', [
            'record' => $education,
        ]);
    }

    public function update(EducationRequest $request, Education $education): RedirectResponse
    {
        $education->update($request->validated());

        return redirect()->route('admin.education.index')->with('status', 'Education updated.');
    }

    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();

        return redirect()->route('admin.education.index')->with('status', 'Education deleted.');
    }
}
