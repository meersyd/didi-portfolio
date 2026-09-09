<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        if ($request->filled('company_website')) {
            return back()->with('status', 'Thanks. I will get back to you shortly.');
        }

        ContactMessage::query()->create($request->safe()->only([
            'name',
            'email',
            'subject',
            'message',
        ]));

        return back()->with('status', 'Thanks. I will get back to you shortly.');
    }
}
