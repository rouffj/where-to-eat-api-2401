<?php

namespace App\Command;

use App\Entity\Address;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:import:restaurants',
    description: 'Add a short description for your command',
)]
class ImportRestaurantsCommand extends Command
{
    private $httpClient;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('address', InputArgument::OPTIONAL, 'Restaurants nearby the given address')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$address = $input->getArgument('address')) {
            $address = $io->ask('Could you provide an address to locate restaurants?', '5 rue de la paix, 75008 Paris', function($answer) {
                dump($answer);
                if (false !== strpos(strtolower($answer), 'toulouse')) {
                    throw new \InvalidArgumentException('Invalid city');
                }

                return $answer;
            });
        }

        $io->title('Importing restaurants nearby: ' . $address);

        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';
        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'query' => '15 rue des minimes, 92400 Courbevoie',
                'key' => $_ENV['GMAPS_KEY'],
                'type' => 'restaurant',
            ]
        ]);
        $body = $response->toArray();

        $rows = [];
        $bar = $io->createProgressBar(count($body['results']));
        $bar->display();
        foreach ($body['results'] as $restaurantData) {
            $matches = [];
            preg_match_all('/(.*),\s(\d+)\s(.*),\s(.*)/xi', $restaurantData['formatted_address'], $matches);

            $address = new Address();
            $address->setCity($matches[3][0]);
            $address->setStreet($matches[1][0]);
            $address->setZipCode($matches[2][0]);

            $restaurant = new Restaurant();
            $restaurant->setLikes(0)->setDislikes(0);
            $restaurant->setAddress($address);
            $restaurant->setName($restaurantData['name']);

            $rows[] = [$restaurant->getName(), $restaurantData['formatted_address']];

            $this->entityManager->persist($restaurant);

            usleep(50000);
            $bar->advance();
        }
        $bar->finish();
        $bar->clear();

        //$this->entityManager->flush();

        $io->table(['NAME', 'ADDRESS'], $rows);
        
        return Command::SUCCESS;
    }
}
