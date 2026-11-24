<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HostingPlan;
use Illuminate\View\View;

class HostingController extends Controller
{
    public function index(): View
    {
        return view('pages.hosting', ['plans' => HostingPlan::active()->get()]);
    }
}
