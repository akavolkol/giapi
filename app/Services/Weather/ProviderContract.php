<?php

namespace App\Services\Weather;

use App\Models\Weather;

interface ProviderContract
{
    /**
     * @param string $location
     * @return Weather|null
     */
    public function current(string $location): ?Weather;
}
