<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\DomainExtension;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home', [
            'services' => Service::active()->get(),
            'projects' => Project::active()->with('category')->where('is_featured', true)->take(6)->get(),
            'testimonials' => Testimonial::active()->take(6)->get(),
            'featuredExtensions' => DomainExtension::active()->where('is_featured', true)->take(5)->get(),
        ]);
    }
}
