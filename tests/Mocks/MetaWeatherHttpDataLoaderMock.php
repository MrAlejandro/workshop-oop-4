<?php

namespace App\Tests\Mocks;

use App\DataLoader\HttpLoader;

class MetaWeatherHttpDataLoaderMock implements HttpLoader
{
    public function getResponseBody(string $url): string
    {
        $filePathParts = [__DIR__, '..', 'fixtures', 'MetaWeather'];
        if (strpos($url, 'search') !== false) {
            $city = $this->getCityNameFromUrl($url);
            $filePathParts[] = "{$city}CityLocationResponse.json";
        } else {
            $locationId = $this->getLocationIdFromUrl($url);
            $filePathParts[] = "ResponseForLocation{$locationId}.json";
        }

        $filePath = implode(DIRECTORY_SEPARATOR, $filePathParts);
        return file_get_contents($filePath);
    }

    protected function getCityNameFromUrl(string $url): string
    {
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $query);
        $city = str_replace(' ', '', $query['query']);
        return $city;
    }

    protected function getLocationIdFromUrl(string $url): int
    {
        $urlParts = explode('/', $url);
        $locationId = (int) $urlParts[count($urlParts) - 1];

        return $locationId;
    }
}
