<?php

namespace App;

use RuntimeException;
use App\WeatherForecastProvider\Apixu;
use App\WeatherForecastProvider\MetaWeather;

class ForecastProviders
{
    protected $providers;
    protected $defaultProvider = 'metaweather';

    public function __construct()
    {
        $this->providers = [
            'apixu' => function () {
                if (empty($_SERVER['APIXU_API_KEY'])) {
                    throw new RuntimeException('Cannot find api key. Check the documentation.');
                }
                return new Apixu($apiKey ?? $_SERVER['APIXU_API_KEY']);
            },
            'metaweather' => function () {
                return new MetaWeather();
            },
        ];
    }

    public function getProvider(string $providerCode = null)
    {
        if ($providerCode && empty($this->providers[$providerCode])) {
            throw new RuntimeException('Cannot find specified provider');
        }

        $code = $providerCode ?? $this->defaultProvider;
        $providerCallback = $this->providers[$code];
        return $providerCallback();
    }
}
