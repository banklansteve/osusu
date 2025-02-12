<x-mail::message>
Hello {{ $user->fullname }} <br/>

You have been invited to join the savings group <b>{{ $saving->title }}</b> by {{ $creator->fullname }}.<br/>

To review the details and accept the invite, simply click the link below.
We’re excited to have you on board and look forward to seeing you in the group!<br/>

Please click on the link below to review and attend to the request.

<x-mail::button :url="$url">
    View Invite
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
