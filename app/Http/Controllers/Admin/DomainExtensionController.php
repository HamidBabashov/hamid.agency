<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainExtension;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DomainExtensionController extends Controller
{
    public function index(): View
    {
        return view('admin.domain-extensions.index', ['items' => DomainExtension::orderBy('sort_order')->orderBy('extension')->get()]);
    }

    public function create(): View
    {
        return view('admin.domain-extensions.form', ['item' => new DomainExtension(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new DomainExtension);

        return redirect()->route('admin.domain-extensions.index')->with('success', __('admin.flash.created'));
    }

    public function edit(DomainExtension $domainExtension): View
    {
        return view('admin.domain-extensions.form', ['item' => $domainExtension]);
    }

    public function update(Request $request, DomainExtension $domainExtension): RedirectResponse
    {
        $this->save($request, $domainExtension);

        return redirect()->route('admin.domain-extensions.index')->with('success', __('admin.flash.updated'));
    }

    public function destroy(DomainExtension $domainExtension): RedirectResponse
    {
        $domainExtension->delete();

        return redirect()->route('admin.domain-extensions.index')->with('success', __('admin.flash.deleted'));
    }

    private function save(Request $request, DomainExtension $extension): void
    {
        $request->merge(['extension' => ltrim(mb_strtolower(trim((string) $request->input('extension'))), '.')]);

        $data = $request->validate([
            'extension' => ['required', 'string', 'max:30', 'regex:/^[a-z0-9]+(\.[a-z0-9]+)?$/', Rule::unique('domain_extensions')->ignore($extension)],
            'register_price' => ['required', 'numeric', 'min:0', 'max:100000'],
            'renew_price' => ['required', 'numeric', 'min:0', 'max:100000'],
            'transfer_price' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [], __('admin.fields'));

        $extension->fill($data);
        $extension->sort_order = (int) ($data['sort_order'] ?? 0);
        $extension->is_featured = $request->boolean('is_featured');
        $extension->is_active = $request->boolean('is_active');
        $extension->save();
    }
}
