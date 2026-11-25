<form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
    <div>
        <label for="f-q" class="sr-only">{{ __('admin.actions.search') }}</label>
        <input id="f-q" type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('admin.filters.search_placeholder') }}" class="input w-64">
    </div>
    <select name="status" class="input w-auto" aria-label="{{ __('admin.fields.status') }}">
        <option value="">{{ __('admin.filters.all_statuses') }}</option>
        @foreach (\App\Models\Order::STATUSES as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ __('admin.statuses.'.$status) }}</option>
        @endforeach
    </select>
    @if ($withService ?? false)
        <select name="service" class="input w-auto" aria-label="{{ __('admin.fields.service_type') }}">
            <option value="">{{ __('admin.filters.all_services') }}</option>
            @foreach (\App\Models\Service::KEYS as $key)
                <option value="{{ $key }}" @selected(request('service') === $key)>{{ __('site.service_types.'.$key) }}</option>
            @endforeach
        </select>
    @endif
    <button class="btn-primary">{{ __('admin.actions.filter') }}</button>
    @if (request()->hasAny(['q', 'status', 'service']))
        <a href="{{ url()->current() }}" class="btn-outline">{{ __('admin.actions.reset') }}</a>
    @endif
</form>
