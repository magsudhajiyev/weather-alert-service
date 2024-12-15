{{-- resources/views/emails/weather-alert.blade.php --}}
@component('mail::message')
# Weather Alert for {{ $cityName }}

Hello {{ $userName }},

This is an automated alert to inform you that the current {{ strtolower($alertType) }} level in {{ $cityName }} has exceeded your set threshold.

**Current Conditions:**
- {{ $alertType }}: {{ $currentValue }}{{ $unit }}
- Your threshold: {{ $threshold }}{{ $unit }}
- Current conditions: {{ $description }}

Please take necessary precautions based on these weather conditions.

@component('mail::button', ['url' => config('app.url')])
View Weather Dashboard
@endcomponent

Stay safe!<br>
{{ config('app.name') }}
@endcomponent