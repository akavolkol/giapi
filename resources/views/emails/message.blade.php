{{ $text }}
@if ($weather !== null)
_______
{{ $user['location'] }}: {{ round($weather->temp(\App\Models\Weather::TEMP_TYPE_CELSIUS), 0) }} °C, {{ $weather->main }}
@endif
