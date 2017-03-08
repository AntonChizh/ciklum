<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Weather\CurrentWeatherService;
use App\Exceptions\Weather\WeatherException;

class ShowCurrentWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:current
                            {city : The name of the city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show current weather by city name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $city = $this->argument('city');

        $weatherService = new CurrentWeatherService();
        try {
            $weather = $weatherService->getByCity($city);
        } catch (WeatherException $e) {
            $this->error($e->getMessage());
            return;
        }

        $this->info('Weather for city '.$city);
        dump($weather->toArray());
    }
}
