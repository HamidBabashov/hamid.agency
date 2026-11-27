@auth
    <div x-data="{ open: false }" class="relative" @click.outside="open = false" @keydown.escape="open = false">
        <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="true" aria-label="{{ __('account.nav.menu') }}"
                class="flex items-center gap-2 rounded-full p-1 pr-1 text-sm font-medium text-slate-700 transition hover:bg-slate-900/5 sm:pr-3 dark:text-slate-200 dark:hover:bg-white/10">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-gradient-to-br from-accent-300 to-accent-500 text-sm font-bold text-brand-950">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            <span class="hidden max-w-[8rem] truncate sm:block">{{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</span>
        </button>
        <div x-cloak x-show="open" x-transition.opacity class="absolute right-0 mt-3 w-60 rounded-2xl border border-slate-200/80 bg-white p-1.5 shadow-2xl shadow-slate-900/10 dark:border-white/10 dark:bg-slate-900" role="menu">
            <div class="px-3 py-2.5">
                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
            </div>
            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
            <a href="{{ lroute('account') }}" role="menuitem" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/5"><x-icon name="cart" class="h-4 w-4" />{{ __('account.nav.orders') }}</a>
            <a href="{{ lroute('account.profile') }}" role="menuitem" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/5"><x-icon name="user" class="h-4 w-4" />{{ __('account.nav.profile') }}</a>
            @if (auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" role="menuitem" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/5"><x-icon name="shield" class="h-4 w-4" />{{ __('account.nav.admin') }}</a>
            @endif
            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
            <form method="POST" action="{{ lroute('logout') }}">
                @csrf
                <button type="submit" role="menuitem" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10"><x-icon name="logout" class="h-4 w-4" />{{ __('account.nav.logout') }}</button>
            </form>
        </div>
    </div>
@else
    <a href="{{ lroute('login') }}" class="hidden items-center gap-1.5 rounded-full px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-900/5 sm:inline-flex dark:text-slate-200 dark:hover:bg-white/10">
        <x-icon name="user" class="h-4 w-4" />{{ __('account.nav.login') }}
    </a>
@endauth
