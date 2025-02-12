<x-mail::message>
Hello {{ $creator->fullname }}, <br />

Your membership invite sent to {{ $user->fullname }} has now been accepted.<br />

Please click below to view.

<x-mail::button :url="$url">
View Accepted Invite
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
