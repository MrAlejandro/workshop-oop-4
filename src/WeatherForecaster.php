<?php

namespace App;

use App\WeatherForecastProvider\MetaWeather;
use App\WeatherForecastProvider\Forecaster;

class WeatherForecaster
{
    protected $forecaster;

    public function __construct(Forecaster $forecaster = null)
    {
        $this->forecaster = $forecaster ?: new MetaWeather();
    }

    public function getForecast(string $cityName): WeatherMetaInfo
    {
        return $this->forecaster->getForecast($cityName);
    }
}
