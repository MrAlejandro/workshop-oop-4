<?php

namespace App\WeatherForecastProvider;

use App\WeatherMetaInfo;
use App\DataLoader\HttpLoader;
use App\DataLoader\HttpDataLoader;

abstract class Forecaster
{
    protected $httpLoader;

    public function __construct(HttpLoader $httpLoader = null)
    {
        $this->httpLoader = $httpLoader ?: new HttpDataLoader();
    }

    abstract public function getForecast(string $cityName): WeatherMetaInfo;
}
