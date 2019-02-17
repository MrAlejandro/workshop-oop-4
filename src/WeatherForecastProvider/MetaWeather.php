<?php

namespace App\WeatherForecastProvider;

use Carbon\Carbon;
use RuntimeException;
use App\WeatherMetaInfo;

class MetaWeather extends ForecastProvider
{
    protected const LOCATION_API_URL = 'https://www.metaweather.com/api/location/search/';
    protected const FORECAST_API_URL = 'https://www.metaweather.com/api/location/';

    public function getForecast(string $cityName): WeatherMetaInfo
    {
        $locationId = $this->getLocationId($cityName);
        $forecast = $this->getWeatherForecast($locationId);
        $metaInfo = $this->prepareMetaInfo($forecast);
        return $metaInfo;
    }

    protected function getLocationId($cityName)
    {
        $locationUrl = self::LOCATION_API_URL . "?query={$cityName}";
        $body = $this->getResponseBody($locationUrl);
        $firstCityData = $body[0] ?? [];

        if (empty($firstCityData['woeid'])) {
            throw new RuntimeException('Cannot find provided city.');
        }

        return $firstCityData['woeid'];
    }

    protected function getResponseBody($url): array
    {
        $rawBody = $this->httpLoader->getResponseBody($url);
        $body = json_decode($rawBody, true);
        return $body;
    }

    protected function getWeatherForecast($locationId)
    {
        $forecastUrl = self::FORECAST_API_URL . $locationId;
        $body = $this->getResponseBody($forecastUrl);
        return $body;
    }

    protected function prepareMetaInfo($forecast): WeatherMetaInfo
    {
        $firstStation = $forecast['consolidated_weather'][0];
        $weatherData = [
            'date'          => Carbon::create($firstStation['created'])->format(WeatherMetaInfo::DATE_FORMAT),
            'temperature'   => round((float) $firstStation['the_temp'], 1),
            'windSpeed'     => round((float) $firstStation['wind_speed'], 1),
            'windDirection' => (int) $firstStation['wind_direction'],
            'airPressure'   => round((float) $firstStation['air_pressure'], 2),
            'humidity'      => (int) $firstStation['humidity'],
        ];

        $metaInfo = new WeatherMetaInfo($weatherData);
        return $metaInfo;
    }
}
