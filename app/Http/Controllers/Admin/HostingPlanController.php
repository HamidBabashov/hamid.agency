<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ValidatesTranslations;
use App\Http\Controllers\Controller;
use App\Models\HostingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostingPlanController extends Controller
{
    use ValidatesTranslations;

    public function index(): View
    {
        return view('admin.hosting-plans.index', ['items' => HostingPlan::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.hosting-plans.form', ['item' => new HostingPlan(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new HostingPlan);

        return redirect()->route('admin.hosting-plans.index')->with('success', __('admin.flash.created'));
    }

    public function edit(HostingPlan $hostingPlan): View
    {
        return view('admin.hosting-plans.form', ['item' => $hostingPlan]);
    }

    public function update(Request $request, HostingPlan $hostingPlan): RedirectResponse
    {
        $this->save($request, $hostingPlan);

        return redirect()->route('admin.hosting-plans.index')->with('success', __('admin.flash.updated'));
    }

    public function destroy(HostingPlan $hostingPlan): RedirectResponse
    {
        $hostingPlan->delete();

        return redirect()->route('admin.hosting-plans.index')->with('success', __('admin.flash.deleted'));
    }

    private function save(Request $request, HostingPlan $plan): void
    {
        $data = $request->validate([
            'price_monthly' => ['required', 'numeric', 'min:0', 'max:100000'],
            'price_yearly' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ] + $this->translatable([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'features' => ['nullable', 'string', 'max:5000'],
        ]), [], $this->translatableAttributes(['name', 'description', 'features']) + __('admin.fields'));

        $plan->fill($data);
        $plan->sort_order = (int) ($data['sort_order'] ?? 0);
        $plan->is_featured = $request->boolean('is_featured');
        $plan->is_active = $request->boolean('is_active');
        $plan->save();
    }
}
