<?php

namespace App;

use Docopt;

class App
{
    public function run()
    {
        $args = Docopt::handle($this->getDoc(), ['version' => 'Weather Forecaster 0.1.0']);
        $forecastProvider = (new ForecastProviders())->getProvider($args->args['--service']);
        $forecaster = new WeatherForecaster($forecastProvider);
        $forecast = $forecaster->getForecast($args->args['<cityName>']);
        return $forecast;
    }

    protected function getDoc()
    {
        return <<<DOC
Get geographical location data

Usage:
    get-weather [--service=<service>] <cityName>
    get-weather (-h|--help)
    
Options:
    -h --help                     Show this screen
    --service=<service>           Specify service code [available: apixu, metaweather (default)]
DOC;
    }
}
