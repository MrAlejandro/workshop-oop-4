### Cli usage:
`get-weather [--service=<service>] <cityName>`
* available services: apixu, metaweather.
* wrap `cityName` in quotes if it contains spaces.
* `apixu` provider requires `APIXU_API_KEY` environment variable to be set.

Example: `bin/get-weather --service metaweather 'New York'`

### Lib usage:
```
$forecaster = new WeatherForecaster($forecastProvider);
$forecast = $forecaster->getForecast($cityName);
```
