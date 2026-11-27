<x-mail::message>
# {{ __('account.mail.received.heading', ['name' => $order->name]) }}

{{ __('account.mail.received.text', ['number' => $order->id, 'service' => __('site.service_types.'.$order->service_type)]) }}

@if ($rows = $order->detailRows())
<x-mail::table>
| | |
|:--|:--|
@foreach ($rows as $row)
| **{{ $row['label'] }}** | {{ $row['value'] }} |
@endforeach
</x-mail::table>
@endif

@if ($order->user_id)
<x-mail::button :url="lroute('account.orders.show', ['order' => $order->id], $order->locale)">
{{ __('account.mail.view_order') }}
</x-mail::button>
@endif

{{ __('account.mail.signature') }}
</x-mail::message>
