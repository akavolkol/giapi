{{ $text }}
@if ($weather !== null)
_______
{{ $user['location'] }}: {{ $weather->temp(\App\Models\Weather::TEMP_TYPE_CELSIUS) }} °C, {{ $weather->main }}
@endif