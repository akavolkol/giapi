<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Provider;
use GuzzleHttp\Client;
use App\Models\Weather;

class OpenWeatherMap extends Provider
{
    public function current(string $location): ?Weather
    {
        $client = new Client();
        $appId = $this->config->get('app.openWeatherAppId');
        $res = $client->request(
            'GET',
            "http://api.openweathermap.org/data/2.5/weather?q={$location}&APPID={$appId}"
        );
        $res->getStatusCode();
        $data = json_decode($res->getBody(), true);
        return is_array($data)
            ? $this->getModel($data)
            : null;
    }

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