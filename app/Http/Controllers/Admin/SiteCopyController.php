<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteCopyRequest;
use App\Models\SiteCopy;
use Illuminate\Http\RedirectResponse;
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

        $copy->update($data);

        if ($request->boolean('remove_resume')) {
            $copy->clearStoredResume();
        }

        if ($request->hasFile('resume')) {
            $binary = file_get_contents($request->file('resume')->getRealPath());

            if ($binary !== false && $binary !== '') {
                $copy->storeResumePayload($binary);
            }
        }

        return redirect()->route('admin.pages.edit')->with('status', 'Site copy updated. Portfolio resume is in sync.');
    }
}
