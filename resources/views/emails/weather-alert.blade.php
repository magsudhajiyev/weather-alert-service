<!-- resources/views/emails/weather-alert.blade.php -->
@component('mail::message')
# Weather Alert for {{ $alertData->cityName }}

{{ $alertData->getMessageContent() }}

@component('mail::button', ['url' => route('weather')])
View Weather Dashboard
@endcomponent

Stay safe!<br>
{{ config('app.name') }}
@endcomponent