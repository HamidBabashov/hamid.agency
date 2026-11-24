<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about', ['services' => Service::active()->get()]);
    }

    public function privacy(): View
    {
        return view('pages.legal', ['page' => 'privacy']);
    }

    public function terms(): View
    {
        return view('pages.legal', ['page' => 'terms']);
    }
}
