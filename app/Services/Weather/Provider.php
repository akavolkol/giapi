<?php

namespace App\Services\Weather;

use Illuminate\Config\Repository;
use GuzzleHttp\Client;

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

    public function client(): Client
    {
        return new Client();
    }
}
