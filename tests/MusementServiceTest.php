<?php

// tests/Service/NewsletterGeneratorTest.php
namespace Ampahkwabena\Test;

use Ampahkwabena\Services\MusementService;
use Ampahkwabena\Services\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MusementServiceTest extends KernelTestCase
{
    public function testMusementService()
    {

        self::bootKernel();

        $weatherService = new WeatherService();
        $musementService = new MusementService($weatherService);

        $musementServiceResponse = $musementService->callMusementApiCities();

        // Musement cities API response status code 
        $this->assertSame(200, $musementServiceResponse->getStatusCode());

        // Musement cities API Response Body (array)
        $this->assertNotEmpty($musementServiceResponse->toArray());
    }
}