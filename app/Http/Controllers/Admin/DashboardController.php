<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'projectCount' => Project::query()->count(),
            'publishedCount' => Project::query()->published()->count(),
            'unreadCount' => ContactMessage::query()->unread()->count(),
            'experienceCount' => Experience::query()->count(),
            'skillCount' => Skill::query()->count(),
            'educationCount' => Education::query()->count(),
            'recentMessages' => ContactMessage::query()->latest()->take(5)->get(),
        ]);
    }
}
