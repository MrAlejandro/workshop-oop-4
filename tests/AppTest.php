<?php

namespace App\Tests;

use RuntimeException;
use App\WeatherMetaInfo;
use App\WeatherForecaster;
use PHPUnit\Framework\TestCase;
use App\WeatherForecastProvider\Apixu;
use App\WeatherForecastProvider\MetaWeather;
use App\Tests\Mocks\ApixuHttpDataLoaderMock;
use App\Tests\Mocks\MetaWeatherHttpDataLoaderMock;

class AppTest extends TestCase
{
    /** @var \App\WeatherForecastProvider\Forecaster */
    protected $metaWeatherForecaster;

    /** @var \App\WeatherForecastProvider\Forecaster */
    protected $apixuForecaster;

    public function setUp()
    {
        $metaWeatherHttpLoaderMock = new MetaWeatherHttpDataLoaderMock();
        $meatWeather = new MetaWeather($metaWeatherHttpLoaderMock);
        $this->metaWeatherForecaster = new WeatherForecaster($meatWeather);

        $apixuHttpLoaderMock = new ApixuHttpDataLoaderMock();
        $apixu = new Apixu('mydummyapikey', $apixuHttpLoaderMock);
        $this->apixuForecaster = new WeatherForecaster($apixu);
    }

    public function testForecastFromMetaWeather()
    {
        $expected = new WeatherMetaInfo([
            'date'          => '2019-02-17 03:35:44',
            'temperature'   => 5.6,
            'windSpeed'     => 7.7,
            'windDirection' => 321,
            'airPressure'   => 1016.92,
            'humidity'      => 58,
        ]);

        $this->assertEquals($expected, $this->metaWeatherForecaster->getForecast('New York'));
    }

    public function testMetaWetaherExceptionForUnknownLocation()
    {
        $this->expectException(RuntimeException::class);
        $unknownCityName = 'Unknown';
        $this->metaWeatherForecaster->getForecast($unknownCityName);
    }

    public function testForecastFromApixu()
    {
        $expected = new WeatherMetaInfo([
            'date'          => '2019-02-16 23:30:00',
            'temperature'   => 0.6,
            'windSpeed'     => 0.0,
            'windDirection' => 332,
            'airPressure'   => 1017.0,
            'humidity'      => 49,
        ]);

        $forecast = $this->apixuForecaster->getForecast('New York');
        $this->assertEquals($expected, $forecast);
    }

    public function testApixuExceptionForUnknownLocation()
    {
        $this->expectException(RuntimeException::class);
        $unknownCityName = 'Unknown';
        $this->apixuForecaster->getForecast($unknownCityName);
    }
}
