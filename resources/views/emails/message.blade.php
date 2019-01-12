{{ $text }}
@if ($weather !== null)
_______
{{ $user['location'] }}: {{ round($weather->temperature(\App\Models\Weather::TEMP_TYPE_CELSIUS), 0) }} °C, {{ $weather->main }}
@endif
