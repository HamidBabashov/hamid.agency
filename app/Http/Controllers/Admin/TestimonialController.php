<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImages;
use App\Http\Controllers\Admin\Concerns\ValidatesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    use HandlesImages, ValidatesTranslations;

    public function index(): View
    {
        return view('admin.testimonials.index', ['items' => Testimonial::orderBy('sort_order')->latest('id')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['item' => new Testimonial(['is_active' => true, 'rating' => 5])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', __('admin.flash.created'));
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', ['item' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $this->save($request, $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', __('admin.flash.updated'));
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->syncImage(new Request(['remove_photo' => true]), $testimonial, 'photo', 'testimonials');
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', __('admin.flash.deleted'));
    }

    private function save(Request $request, Testimonial $testimonial): void
    {
        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'photo' => $this->imageRules(),
        ] + $this->translatable([
            'position' => ['nullable', 'string', 'max:150'],
            'content' => ['required', 'string', 'max:2000'],
        ]), [], $this->translatableAttributes(['position', 'content']) + __('admin.fields'));

        unset($data['photo']);
        $testimonial->fill($data);
        $testimonial->sort_order = (int) ($data['sort_order'] ?? 0);
        $testimonial->is_active = $request->boolean('is_active');
        $this->syncImage($request, $testimonial, 'photo', 'testimonials', 300);
        $testimonial->save();
    }
}
