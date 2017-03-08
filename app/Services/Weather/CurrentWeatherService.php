<?php

namespace App\Services\Weather;

use App\Models\CurrentWeather;
use Carbon\Carbon;

class CurrentWeatherService extends WeatherService
{
    /**
     * @param string $city
     * @return CurrentWeather|null
     */
    public function getByCity($city)
    {
        $weather = $this->selectByCity($city);

        if (!$weather) {
            $weather = $this->insertByCity($city);
        }

        return $weather;
    }

    /**
     * @param string $city
     * @return CurrentWeather|null
     */
    protected function selectByCity($city)
    {
        return CurrentWeather::where([
            ['city_name', '=', $city],
            ['dt', '>=', $this->getCurrentDt()],
        ])->orderBy('dt', 'desc')
            ->first();
    }

    /**
     * @param string $city
     * @return CurrentWeather
     */
    protected function insertByCity($city)
    {
        $weather = $this->getFromApi('weather', ['q' => $city]);

        $cityId = $weather['id'];
        $cityName = $weather['name'];
        $countryCode = $weather['sys']['country'];
        $lon = $weather['coord']['lon'];
        $lat = $weather['coord']['lat'];
        $dt = Carbon::createFromTimestamp($weather['dt']);

        unset(
            $weather['coord'],
            $weather['base'],
            $weather['dt'],
            $weather['sys'],
            $weather['id'],
            $weather['name'],
            $weather['cod']
        );

        $weather = CurrentWeather::firstOrCreate([
            'city_name' => $cityName,
            'dt' => $dt,
        ], [
            'city_id' => $cityId,
            'country_code' => $countryCode,
            'lon' => $lon,
            'lat' => $lat,
            'weather' => $weather,
        ]);

        return $weather;
    }
}
