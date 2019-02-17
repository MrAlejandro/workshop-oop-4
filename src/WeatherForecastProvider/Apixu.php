<?php

namespace App\WeatherForecastProvider;

use Carbon\Carbon;
use RuntimeException;
use App\WeatherMetaInfo;
use App\DataLoader\HttpLoader;

class Apixu extends Forecaster
{
    protected const FORECAST_API_URL = 'https://api.apixu.com/v1/current.json';

    protected $apiKey;

    public function __construct(string $apiKey, HttpLoader $httpLoader = null)
    {
        $this->apiKey = $apiKey;
        parent::__construct($httpLoader);
    }

    public function getForecast(string $cityName): WeatherMetaInfo
    {
        $forecast = $this->getWeatherForecast($cityName);
        $metaInfo = $this->prepareMetaInfo($forecast);
        return $metaInfo;
    }

    protected function getWeatherForecast($cityName)
    {
        $forecastUrl = $this->getUrl($cityName);
        $body = $this->getResponseBody($forecastUrl);

        if (!empty($body['error']['message'])) {
            throw new RuntimeException($body['error']['message']);
        }

        return $body;
    }

    protected function getUrl($cityName)
    {
        $query = [
            'key' => $this->apiKey,
            'q' => $cityName,
        ];

        $url = sprintf('%s?%s', self::FORECAST_API_URL, http_build_query($query));
        return $url;
    }

    protected function getResponseBody($url): array
    {
        $rawBody = $this->httpLoader->getResponseBody($url);
        $body = json_decode($rawBody, true);
        return $body;
    }

    protected function prepareMetaInfo($forecastData): WeatherMetaInfo
    {
        $forecast = $forecastData['current'];
        $weatherData = [
            'date'          => Carbon::create($forecast['last_updated'])->format(WeatherMetaInfo::DATE_FORMAT),
            'temperature'   => round((float) $forecast['temp_c'], 1),
            'windSpeed'     => round((float) $forecast['wind_kph'], 1),
            'windDirection' => (int) $forecast['wind_degree'],
            'airPressure'   => round((float) $forecast['pressure_mb'], 2),
            'humidity'      => (int) $forecast['humidity'],
        ];

        $metaInfo = new WeatherMetaInfo($weatherData);
        return $metaInfo;
    }
}
