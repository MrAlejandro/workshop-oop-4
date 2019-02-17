<?php

namespace App;

class WeatherMetaInfo
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    protected $date;
    protected $temperature;
    protected $windSpeed;
    protected $windDirection;
    protected $airPressure;
    protected $humidity;

    public function __construct(array $weatherData)
    {
        $this->date = $weatherData['date'];
        $this->temperature = $weatherData['temperature'];
        $this->windSpeed = $weatherData['windSpeed'];
        $this->windDirection = $weatherData['windDirection'];
        $this->airPressure = $weatherData['airPressure'];
        $this->humidity = $weatherData['humidity'];
    }

    public function __get($attrName)
    {
        return $this->{$attrName} ?? '';
    }
}
