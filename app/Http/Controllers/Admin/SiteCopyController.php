<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteCopyRequest;
use App\Models\SiteCopy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteCopyController extends Controller
{
    public function edit(): View
    {
        return view('admin.pages.form', [
            'copy' => SiteCopy::current(),
        ]);
    }

    public function update(SiteCopyRequest $request): RedirectResponse
    {
        $copy = SiteCopy::current();
        $data = $request->safe()->except(['resume', 'remove_resume']);

        if ($request->boolean('remove_resume')) {
            $this->deleteStoredResume($copy);
            $data['resume_path'] = null;
        }

        if ($request->hasFile('resume')) {
            $this->deleteStoredResume($copy);
            $data['resume_path'] = $request->file('resume')->storeAs('resumes', 'resume.pdf', 'local');
        }

        $copy->update($data);

        return redirect()->route('admin.pages.edit')->with('status', 'Site copy updated.');
    }

    protected function deleteStoredResume(SiteCopy $copy): void
    {
        if (filled($copy->resume_path) && Storage::disk('local')->exists($copy->resume_path)) {
            Storage::disk('local')->delete($copy->resume_path);
        }
    }
}
