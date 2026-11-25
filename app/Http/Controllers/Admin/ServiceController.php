<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImages;
use App\Http\Controllers\Admin\Concerns\ValidatesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use HandlesImages, ValidatesTranslations;

    public const ICONS = ['monitor', 'smartphone', 'server', 'globe', 'code', 'shield', 'chart', 'palette'];

    private const TRANSLATABLE = ['title', 'excerpt', 'body', 'features', 'meta_title', 'meta_description'];

    public function index(): View
    {
        return view('admin.services.index', ['items' => Service::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['item' => new Service(['is_active' => true]), 'icons' => self::ICONS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $service = new Service;
        $this->save($request, $service);

        return redirect()->route('admin.services.index')->with('success', __('admin.flash.created'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['item' => $service, 'icons' => self::ICONS]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->save($request, $service);

        return redirect()->route('admin.services.index')->with('success', __('admin.flash.updated'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->syncImage(new Request(['remove_image' => true]), $service, 'image', 'services');
        $service->delete();
        Cache::forget('sitemap.xml');

        return redirect()->route('admin.services.index')->with('success', __('admin.flash.deleted'));
    }

    private function save(Request $request, Service $service): void
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('services')->ignore($service)],
            'slug_az' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/', Rule::unique('services')->ignore($service)],
            'slug_en' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/', Rule::unique('services')->ignore($service)],
            'icon' => ['required', Rule::in(self::ICONS)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => $this->imageRules(),
        ] + $this->translatable([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:20000'],
            'features' => ['nullable', 'string', 'max:5000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:300'],
        ]), [], $this->translatableAttributes(self::TRANSLATABLE) + __('admin.fields'));

        unset($data['image']);
        $service->fill($data);
        $service->sort_order = (int) ($data['sort_order'] ?? 0);
        $service->is_active = $request->boolean('is_active');
        $this->syncImage($request, $service, 'image', 'services');
        $service->save();
        Cache::forget('sitemap.xml');
    }
}
