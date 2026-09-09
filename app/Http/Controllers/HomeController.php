<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', $this->portfolioPayload());
    }

    /**
     * @return array<string, mixed>
     */
    protected function portfolioPayload(): array
    {
        return [
            'projects' => Project::query()->published()->ordered()->get(),
            'experiences' => Experience::query()->ordered()->get(),
        ];
    }
}
