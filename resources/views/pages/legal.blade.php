@extends('layouts.app')

@section('title', __("seo.{$page}.title"))
@section('description', __("seo.{$page}.description"))

@section('content')
    <x-page-header :title="__('legal.'.$page.'.title')" :lead="__('legal.updated', ['date' => __('legal.updated_date')])" />

    <section class="section">
        <div class="container max-w-3xl">
            <div class="prose-legal">
                <p class="text-lg">{{ __("legal.{$page}.intro") }}</p>
                @foreach (__("legal.{$page}.sections") as $section)
                    <h2>{{ $section['title'] }}</h2>
                    @isset($section['text'])
                        <p>{{ $section['text'] }}</p>
                    @endisset
                    @isset($section['items'])
                        <ul>
                            @foreach ($section['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endisset
                @endforeach
            </div>
        </div>
    </section>
@endsection
