@extends('layouts.app')

@section('title', __('seo.portfolio.title'))
@section('description', __('seo.portfolio.description'))

@section('content')
    <x-page-header :eyebrow="__('portfolio.eyebrow')" :title="__('portfolio.title')" :lead="__('portfolio.lead')" />

    <section class="section" x-data="{ active: 'all' }">
        <div class="container">
            @if ($projects->isEmpty())
                <div class="card mx-auto max-w-xl p-10 text-center">
                    <x-icon name="layers" class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600" />
                    <p class="mt-4 text-slate-600 dark:text-slate-400">{{ __('portfolio.empty') }}</p>
                    <a href="{{ lroute('contact') }}" class="btn-primary mt-6">{{ __('site.actions.contact_us') }}</a>
                </div>
            @else
                @if ($categories->count() > 1)
                    <div class="mb-10 flex flex-wrap justify-center gap-2" role="group" aria-label="{{ __('portfolio.filter') }}">
                        <button type="button" @click="active = 'all'" :aria-pressed="active === 'all'"
                                :class="active === 'all' ? 'bg-brand-950 text-white dark:bg-brand-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'"
                                class="rounded-full px-4 py-2 text-sm font-medium transition">{{ __('portfolio.all') }}</button>
                        @foreach ($categories as $category)
                            <button type="button" @click="active = @js($category->slug)" :aria-pressed="active === @js($category->slug)"
                                    :class="active === @js($category->slug) ? 'bg-brand-950 text-white dark:bg-brand-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'"
                                    class="rounded-full px-4 py-2 text-sm font-medium transition">{{ $category->t('name') }}</button>
                        @endforeach
                    </div>
                @endif

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project"
                            x-show="active === 'all' || active === {{ \Illuminate\Support\Js::from($project->category?->slug) }}"
                            x-transition.opacity />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <x-cta />
@endsection
