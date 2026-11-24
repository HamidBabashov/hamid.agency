<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $projects = Project::active()->with('category')->get();

        return view('pages.portfolio', [
            'projects' => $projects,
            // Yalnız layihəsi olan kateqoriyalar filtrdə göstərilir
            'categories' => ProjectCategory::orderBy('sort_order')
                ->whereIn('id', $projects->pluck('project_category_id')->filter()->unique())
                ->get(),
        ]);
    }
}
