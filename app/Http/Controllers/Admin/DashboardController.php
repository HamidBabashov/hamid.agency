<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'new_orders' => Order::where('status', Order::STATUS_NEW)->count(),
                'new_messages' => ContactMessage::where('status', ContactMessage::STATUS_NEW)->count(),
                'projects' => Project::count(),
                'testimonials' => Testimonial::count(),
            ],
            'latestOrders' => Order::latest()->take(5)->get(),
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}
