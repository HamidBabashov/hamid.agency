<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $items = ContactMessage::query()
            ->status($request->query('status'))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->query('q').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.messages.index', ['items' => $items]);
    }

    public function show(ContactMessage $message): View
    {
        return view('admin.messages.show', ['item' => $message]);
    }

    public function update(Request $request, ContactMessage $message): RedirectResponse
    {
        $message->update($request->validate([
            'status' => ['required', Rule::in(ContactMessage::STATUSES)],
        ], [], __('admin.fields')));

        return back()->with('success', __('admin.flash.updated'));
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', __('admin.flash.deleted'));
    }
}
