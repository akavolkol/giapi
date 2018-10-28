<?php

namespace App\Services\Weather;

use App\Models\Weather;
use App\Services\Weather\Providers\OpenWeatherMap;
use Illuminate\Container\Container;

class WeatherService
{
    /**
     * @var Illuminate\Config\Repository
     */
    protected $config;

    public function __construct(Container $container)
    {
        $this->config = $container->config;
    }

    /**
     * @var ProviderContract
     */
    protected $provider;

    /**
     * @param string $location
     * @return Weather|null
     */
    public function current(string $location): ?Weather
    {
        return ($this->provider ?? new OpenWeatherMap($this->config))->current($location);
    }

    /**
     * @param ProviderContract $provider
     */
    public function provider(ProviderContract $provider)
    {
        $this->provider = $provider;
    }
}