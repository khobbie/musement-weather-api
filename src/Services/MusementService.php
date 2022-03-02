<?php

namespace Ampahkwabena\Services;

use Symfony\Component\HttpClient\HttpClient;


class MusementService
{

    private $weatherService;

    private $MUSEMENT_BASE_URL = 'https://api.musement.com/api/v3/cities.json';

    public function __construct(WeatherService $weatherService)
    {

        $this->weatherService = $weatherService;
    }

    public function callMusementApiCities()
    {
        try {

            $client = HttpClient::create([
                'max_redirects' => 3,
            ]);

            $response = $client->request(
                'GET',
                $this->MUSEMENT_BASE_URL
            );

            return $response;
        } catch (\Exception $e) {
            throw new $e->getMessage();
        }
    }



    public function getMusementCities()
    {

        try {

            $response = $this->callMusementApiCities();

            if ($response->getStatusCode() == 200) {

                $cities = json_decode($response->getContent());

                return $this->transformMusementCities($cities);
            } else {
                throw new \Exception('Musement API Server Error 😤');
            }
        } catch (\Exception $e) {
            throw new $e->getMessage();
        }
    }



    public function transformMusementCities($cities)
    {

        $cityForecastsProcessed = "";

        foreach ($cities as $city) {

            $response = (object) $this->weatherService->getTodayAndTomorrowConditionsUsingGeoCoordinates($city->latitude, $city->longitude);

            $today_condition = $response->today_condition;
            $tomorrow_condition = $response->tomorrow_condition;

            $cityForecastsProcessed = $cityForecastsProcessed . sprintf(
                'Processed city %s | %s - %s',
                $city->name,
                $today_condition,
                $tomorrow_condition
            );

            $cityForecastsArray[] = [
                "city" => $city->name,
                "latitude" => $city->latitude,
                "longitude" => $city->longitude,
                "today_condition" => $today_condition,
                "tomorrow_condition" =>  $tomorrow_condition
            ];
        }

        return $cityForecastsArray;
    }
}