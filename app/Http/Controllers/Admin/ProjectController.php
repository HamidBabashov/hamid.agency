<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImages;
use App\Http\Controllers\Admin\Concerns\ValidatesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    use HandlesImages, ValidatesTranslations;

    public function index(): View
    {
        return view('admin.projects.index', ['items' => Project::with('category')->orderBy('sort_order')->latest('id')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.projects.form', ['item' => new Project(['is_active' => true]), 'categories' => ProjectCategory::orderBy('sort_order')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Project);

        return redirect()->route('admin.projects.index')->with('success', __('admin.flash.created'));
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', ['item' => $project, 'categories' => ProjectCategory::orderBy('sort_order')->get()]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->save($request, $project);

        return redirect()->route('admin.projects.index')->with('success', __('admin.flash.updated'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->syncImage(new Request(['remove_image' => true]), $project, 'image', 'projects');
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', __('admin.flash.deleted'));
    }

    private function save(Request $request, Project $project): void
    {
        $data = $request->validate([
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'client' => ['nullable', 'string', 'max:150'],
            'url' => ['nullable', 'url:http,https', 'max:255'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => $this->imageRules(),
        ] + $this->translatable([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
        ]), [], $this->translatableAttributes(['title', 'summary']) + __('admin.fields'));

        unset($data['image']);
        $project->fill($data);
        $project->sort_order = (int) ($data['sort_order'] ?? 0);
        $project->is_featured = $request->boolean('is_featured');
        $project->is_active = $request->boolean('is_active');
        $this->syncImage($request, $project, 'image', 'projects', 1200);
        $project->save();
    }
}
