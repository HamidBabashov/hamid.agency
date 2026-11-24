<x-mail::message>
# {{ __('mail.order.heading') }}

<x-mail::table>
| | |
|:--|:--|
| **{{ __('admin.fields.number') }}** | {{ $order->id }} |
| **{{ __('mail.fields.service') }}** | {{ __('site.service_types.'.$order->service_type) }} |
| **{{ __('mail.fields.name') }}** | {{ $order->name }} |
| **{{ __('mail.fields.email') }}** | {{ $order->email }} |
| **{{ __('mail.fields.phone') }}** | {{ $order->phone ?: '—' }} |
@if ($order->company)
| **{{ __('mail.fields.company') }}** | {{ $order->company }} |
@endif
@foreach ($order->detailRows() as $row)
| **{{ $row['label'] }}** | {{ $row['value'] }} |
@endforeach
| **{{ __('mail.fields.language') }}** | {{ strtoupper($order->locale) }} |
| **{{ __('mail.fields.date') }}** | {{ $order->created_at->format('d.m.Y H:i') }} |
</x-mail::table>

@if ($order->message)
**{{ __('mail.fields.message') }}:**

{{ $order->message }}
@endif

<x-mail::button :url="route('admin.orders.show', $order)">
{{ __('mail.order.view') }}
</x-mail::button>

<small>{{ __('mail.reply_hint') }}</small>
</x-mail::message>
