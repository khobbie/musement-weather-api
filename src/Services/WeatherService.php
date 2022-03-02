<?php

namespace Ampahkwabena\Services;

use Symfony\Component\HttpClient\HttpClient;



class WeatherService
{
    private $WEATHER_API_KEY;
    private $WEATHER_BASE_URL =  "http://api.weatherapi.com/v1";

    private $DAYS = 2;

    private $client;

    public function __construct()
    {

        $this->WEATHER_API_KEY = "7ab0fbc9c89f466d829131323222702";
    }

    public function getTodayAndTomorrowConditionsUsingGeoCoordinates($lat, $lon): array
    {
        try {

            $this->client = HttpClient::create([
                'max_redirects' => 3,
            ]);

            $response = $this->client->request(
                'GET',
                $this->WEATHER_BASE_URL . "/forecast.json?key={$this->WEATHER_API_KEY}&q={$lat},{$lon}&days={$this->DAYS}"
            );

            if ($response->getStatusCode() == 200) {


                $results = json_decode($response->getContent());

                $forecastData = $results->forecast->forecastday;

                $today_condition = $forecastData[0]->day->condition->text;

                $tomorrow_condition = $forecastData[1]->day->condition->text;

                return [
                    "today_condition" => $today_condition,
                    "tomorrow_condition" => $tomorrow_condition
                ];
            } else {
                throw new \Exception('Weather API Server Error 😤');
            }
        } catch (\Exception $e) {
            throw new $e->getMessage();
        }
    }
}