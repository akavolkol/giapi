<?php

namespace App\Services\Weather;

use Illuminate\Config\Repository;

abstract class Provider implements ProviderContract
{
    /**
     * @var Repository
     */
    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
}