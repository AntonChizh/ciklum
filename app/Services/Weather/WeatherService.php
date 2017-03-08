<?php

namespace App\Services\Weather;

use App\Exceptions\Weather\WeatherException;
use Carbon\Carbon;

abstract class WeatherService
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $currentDtModify;

    /**
     * Weather constructor.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.openweathermap.url');
        $this->apiKey = config('services.openweathermap.key');
        $this->currentDtModify = config('services.openweathermap.current');
    }

    /**
     * @param string $url
     * @param array $params
     * @return mixed
     * @throws WeatherException
     */
    protected function getFromApi($url, array $params = [])
    {
        $params['appid'] = $this->apiKey;
        $fullUrl = rtrim($this->baseUrl, '/').'/'.$url.'?'.http_build_query($params);
        $json = $this->getContent($fullUrl);
        $data = json_decode($json, $fullUrl);

        if ($data['cod'] != 200) {
            throw new WeatherException($data['message'], $data['cod']);
        }

        return $data;
    }

    /**
     * @param string $url
     * @return mixed
     */
    protected function getContent($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);

        return $out;
    }

    /**
     * @return Carbon
     */
    protected function getCurrentDt()
    {
        return Carbon::now()->modify($this->currentDtModify);
    }
}
