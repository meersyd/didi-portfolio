<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('about', [
            'skills' => Skill::query()->ordered()->get()->groupBy('category'),
            'education' => Education::query()->ordered()->get(),
            'experiences' => Experience::query()->ordered()->get(),
        ]);
    }
}
