{{-- Giriş/qeydiyyat səhifələri üçün iki sütunlu kart --}}
@props(['title', 'lead' => null])
<section class="relative overflow-hidden bg-slate-50 pb-20 pt-28 sm:pt-36 dark:bg-ink">
    <div class="aurora-blob -right-40 top-0 h-96 w-96 bg-brand-400/20" aria-hidden="true"></div>
    <div class="aurora-blob -left-40 bottom-0 h-96 w-96 bg-accent-400/10" aria-hidden="true"></div>
    <div class="container relative">
        <div class="mx-auto grid max-w-5xl overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-2xl shadow-slate-900/10 lg:grid-cols-5 dark:border-white/10 dark:bg-slate-900/60" data-reveal>
            <div class="relative hidden overflow-hidden bg-ink p-10 text-white lg:col-span-2 lg:flex lg:flex-col">
                <x-aurora />
                <a href="{{ lroute('home') }}" class="relative flex items-center gap-2.5 text-lg font-extrabold tracking-tight">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-900 text-sm text-accent-300">H</span>
                    <span>Hamid<span class="text-accent-400">Agency</span></span>
                </a>
                <ul class="relative mt-auto space-y-4">
                    @foreach (__('account.register.benefits') as $benefit)
                        <li class="flex items-start gap-3 text-sm text-slate-200">
                            <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-accent-400/20 text-accent-300"><x-icon name="check" class="h-3.5 w-3.5" /></span>{{ $benefit }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="p-7 sm:p-10 lg:col-span-3 lg:p-12">
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">{{ $title }}</h1>
                @if ($lead)
                    <p class="mt-2 text-slate-600 dark:text-slate-400">{{ $lead }}</p>
                @endif
                <div class="mt-8">
                    @if (session('success'))
                        <x-alert class="mb-6">{{ session('success') }}</x-alert>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</section>
