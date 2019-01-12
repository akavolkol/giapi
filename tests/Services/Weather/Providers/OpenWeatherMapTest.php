<?php

namespace Tests\Services\Weather;

use Tests\TestCase;
use App\Services\Weather\Providers\OpenWeatherMap;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use App\Models\Weather;

class OpenWeatherMapTest extends TestCase
{
    public function testSuccessfulObtainingData()
    {
        $response = new Response(
            200,
            [],
            json_encode([
                'base' => 'stations',
                'clouds' => [
                    'all' => 75
                ],
                'cod'=> 200,
                'coord'=> [
                    'lat'=> 50.45,
                    'lon'=> 30.52
                ],
                'dt'=> 1547289000,
                'id'=> 703448,
                'main'=> [
                    'humidity'=> 85,
                    'pressure'=> 1002,
                    'temp'=> 24.19,
                    'temp_max'=> 24.8,
                    'temp_min'=> 23
                ],
                'name'=> 'Kyiv',
                'sys'=> [
                    'country'=> 'UA',
                    'id'=> 8903,
                    'message'=> 0.0023,
                    'sunrise'=> 1547272469,
                    'sunset'=> 1547302704,
                    'type'=> 1
                ],
                'visibility'=> 7000,
                'weather'=> [
                    [
                        'description'=> 'light snow',
                        'icon'=> '13d',
                        'id'=> 600,
                        'main'=> 'Snow'
                    ]
                ],
                'wind'=> [
                    'deg'=> 260,
                    'speed'=> 6
                ]
            ])
        );
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('request')->will($this->returnValue($response));
        $provider = $this
            ->getMockBuilder(OpenWeatherMap::class)
            ->setConstructorArgs([$this->app()->config])
            ->setMethods(['client'])
            ->getMock();
        $provider->method('client')->will($this->returnValue($clientMock));
        $weather = $provider->current('Kyiv');
        $this->assertEquals(get_class($weather), Weather::class);
        $this->assertEquals($weather->temperature(), 24.19);
    }

    public function testUnsuccessfulObtainingData()
    {
        $response = new Response(503);
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('request')->will($this->returnValue($response));
        $provider = $this
            ->getMockBuilder(OpenWeatherMap::class)
            ->setConstructorArgs([$this->app()->config])
            ->setMethods(['client'])
            ->getMock();
        $provider->method('client')->will($this->returnValue($clientMock));
        $weather = $provider->current('Kyiv');
        $this->assertNull($weather);
    }
}
