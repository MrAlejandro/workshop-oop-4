<?php

namespace App\Tests\Mocks;

use App\DataLoader\HttpLoader;

class ApixuHttpDataLoaderMock implements HttpLoader
{
    public function getResponseBody(string $url): string
    {
        $city = $this->getCityNameFromUrl($url);
        $filePathParts = [__DIR__, '..', 'fixtures', 'Apixu', "{$city}CityForecastResponse.json"];
        $filePath = implode(DIRECTORY_SEPARATOR, $filePathParts);
        return file_get_contents($filePath);
    }

    protected function getCityNameFromUrl(string $url): string
    {
        $urlParts = parse_url($url);
        parse_str($urlParts['query'], $query);
        $city = str_replace(' ', '', $query['q']);
        return $city;
    }
}
