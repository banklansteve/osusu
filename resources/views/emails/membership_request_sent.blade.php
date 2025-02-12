<x-mail::message>

Your request to be a member of the saving group <b>{{ $savingName }}</b> has been received.
You will receive an email when the saving creator has reviewed your request.

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
