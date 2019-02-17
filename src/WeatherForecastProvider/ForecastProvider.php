<?php

namespace App\WeatherForecastProvider;

use App\DataLoader\HttpLoader;
use App\DataLoader\HttpDataLoader;

abstract class ForecastProvider implements Forecaster
{
    protected $httpLoader;

    public function __construct(HttpLoader $httpLoader = null)
    {
        $this->httpLoader = $httpLoader ?: new HttpDataLoader();
    }
}
