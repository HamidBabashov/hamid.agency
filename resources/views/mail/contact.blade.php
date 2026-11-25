<x-mail::message>
# {{ __('mail.contact.heading') }}

<x-mail::table>
| | |
|:--|:--|
| **{{ __('mail.fields.name') }}** | {{ $contactMessage->name }} |
| **{{ __('mail.fields.email') }}** | {{ $contactMessage->email }} |
| **{{ __('mail.fields.phone') }}** | {{ $contactMessage->phone ?: '—' }} |
| **{{ __('mail.fields.service') }}** | {{ $contactMessage->service_type ? __('site.service_types.'.$contactMessage->service_type) : '—' }} |
| **{{ __('mail.fields.language') }}** | {{ strtoupper($contactMessage->locale) }} |
| **{{ __('mail.fields.date') }}** | {{ $contactMessage->created_at->format('d.m.Y H:i') }} |
</x-mail::table>

**{{ __('mail.fields.message') }}:**

{{ $contactMessage->message }}

<x-mail::button :url="route('admin.messages.show', $contactMessage)">
{{ __('mail.contact.view') }}
</x-mail::button>

<small>{{ __('mail.reply_hint') }}</small>
</x-mail::message>
