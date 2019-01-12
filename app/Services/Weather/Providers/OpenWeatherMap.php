<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Provider;
use App\Models\Weather;

class OpenWeatherMap extends Provider
{
    /**
     * {@inheritDoc}
     */
    public function current(string $location): ?Weather
    {
        $appId = $this->config->get('app.openWeatherAppId');
        $res = $this->client()->request(
            'GET',
            "https://api.openweathermap.org/data/2.5/weather?q={$location}&APPID={$appId}&units=imperial"
        );
        if ($res->getStatusCode() < 200 || $res->getStatusCode() > 299) {
            return null;
        }
        $data = json_decode($res->getBody(), true);
        return is_array($data)
            ? $this->getModel($data)
            : null;
    }

    /**
     * @param array $data
     * @return Weather
     */
    protected function getModel(array $data): Weather
    {
        return new Weather(
          $data['weather'][0]['main'],
          $data['main']['temp'],
          $data['main']['pressure'],
          $data['main']['humidity']
        );
    }
}
