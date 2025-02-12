<x-mail::message>

Hello,
{{ $userName }} has sent a membership request to join your savings group <strong>{{ $savingName }}</strong>. 
To review, please click the button below.

<x-mail::button :url="$savingUrl">
Review
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
