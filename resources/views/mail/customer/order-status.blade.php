<x-mail::message>
# {{ __('account.mail.status.heading', ['number' => $order->id]) }}

{{ __('account.mail.status.text', ['status' => __('account.statuses.'.$order->status)]) }}

{{ __('account.status_hints.'.$order->status) }}

@if ($order->user_id)
<x-mail::button :url="lroute('account.orders.show', ['order' => $order->id], $order->locale)">
{{ __('account.mail.view_order') }}
</x-mail::button>
@endif

{{ __('account.mail.signature') }}
</x-mail::message>
