<?php

namespace App\Services\Weather;

use App\Models\Weather;

interface ProviderContract
{
    public function current(string $location): ?Weather;
}