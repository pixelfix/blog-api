@component('mail::message')
# Welcome to Pixel Fix Blog

Thank you for registering. Please enter the code below in order to verify your account.
<br/>
<br/>
Code: {{ $code }}

@component('mail::button', ['url' => config('app.url')])
Visit
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
