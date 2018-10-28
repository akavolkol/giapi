<?php

namespace App\Models;

class Weather
{
    const TEMP_TYPE_FAHRENHEIT = 0;

    const TEMP_TYPE_CELSIUS = 1;

    /**
     * description of a weather
     * @var string
     */
    public $main;

    /**
     * temperature in Fahrenheit unit
     * @var float
     */
    public $temp;

    /**
     * @var float
     */
    public $pressure;

    /**
     * @var float
     */
    public $humidity;

    /**
     * Weather constructor.
     * @param string $main
     * @param float $temp
     * @param float $pressure
     * @param float $humidity
     */
    public function __construct(string $main, float $temp, float $pressure, float $humidity)
    {
        $this->main = $main;
        $this->temperature = $temp;
        $this->pressure = $pressure;
        $this->humidity = $humidity;
    }

    /**
     * @param int $type an unit type
     * @return float
     */
    public function temp($type = self::TEMP_TYPE_FAHRENHEIT): float
    {
        if ($type === self::TEMP_TYPE_CELSIUS) {
            return ($this->temp - 32) / 1.8;
        }
        return $this->temp;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'main' => $this->main,
            'temp' => $this->temp,
            'pressure' => $this->pressure,
            'humidity' => $this->humidity,
        ];
    }
}