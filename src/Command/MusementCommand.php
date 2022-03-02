<?php
// src/Command/CreateUserCommand.php
namespace App\Command;

use Ampahkwabena\Services\MusementService;
use Ampahkwabena\Services\WeatherService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MusementCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:get-cities-forecast';

    protected function configure(): void
    {
        $this->setDescription('gets the list of the cities from Musement\'s API for each city gets the forecast for the next 2 days using http://api.weatherapi.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ... put here the code to create the user

        $io = new SymfonyStyle($input, $output);

        $output->writeln("Loading ...");

        $weatherService = new WeatherService();
        $musementService = new MusementService($weatherService);

        $processedCites = $musementService->getMusementCities();



        $output->writeln("");


        // outputs a message followed by a "\n"
        $data = $output->writeln($this->showCitiesForecast($processedCites, $output));

        $io->success($data . ' cities processed');

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }

    public function showCitiesForecast($cities, OutputInterface $output): int
    {
        foreach ($cities as $city_forecast) {
            $output->writeln("Processed city {$city_forecast['city']} | {$city_forecast['today_condition']} - {$city_forecast['tomorrow_condition']}");
            $output->writeln("*******************************************************************");
        }
        return count($cities);
    }
}