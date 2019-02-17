<?php

namespace App\WeatherForecastProvider;

use App\WeatherMetaInfo;

interface Forecaster
{
    public function getForecast(string $cityName): WeatherMetaInfo;
}
