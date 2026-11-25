<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ValidatesTranslations;
use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectCategoryController extends Controller
{
    use ValidatesTranslations;

    public function index(): View
    {
        return view('admin.project-categories.index', ['items' => ProjectCategory::withCount('projects')->orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.project-categories.form', ['item' => new ProjectCategory]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new ProjectCategory);

        return redirect()->route('admin.project-categories.index')->with('success', __('admin.flash.created'));
    }

    public function edit(ProjectCategory $projectCategory): View
    {
        return view('admin.project-categories.form', ['item' => $projectCategory]);
    }

    public function update(Request $request, ProjectCategory $projectCategory): RedirectResponse
    {
        $this->save($request, $projectCategory);

        return redirect()->route('admin.project-categories.index')->with('success', __('admin.flash.updated'));
    }

    public function destroy(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->delete();

        return redirect()->route('admin.project-categories.index')->with('success', __('admin.flash.deleted'));
    }

    private function save(Request $request, ProjectCategory $category): void
    {
        if (blank($request->input('slug'))) {
            $request->merge(['slug' => Str::slug((string) $request->input('name_en'))]);
        }

        $data = $request->validate([
            'slug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/', Rule::unique('project_categories')->ignore($category)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ] + $this->translatable(['name' => ['required', 'string', 'max:100']]),
            [], $this->translatableAttributes(['name']) + __('admin.fields'));

        $category->fill($data);
        $category->sort_order = (int) ($data['sort_order'] ?? 0);
        $category->save();
    }
}
