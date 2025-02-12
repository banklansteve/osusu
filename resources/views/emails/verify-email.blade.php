<x-mail::message>
{{-- Verify your Email address. --}}

Thank you for joining {{ config('app.name' )}}! We're super excited to have you on board.
Please verify your email by clicking the button below.

<x-mail::button :url="$url" style="background: #735DA5; color: #fff; border: none; border-radius: 6px; padding: 8px 24px; text-align: center; text-decoration: none; cursor: pointer; font-size: 16px; display: inline-block;">
 Verify Email
</x-mail::button>

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
