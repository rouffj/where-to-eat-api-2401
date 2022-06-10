<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\Address;

class InMemoryRestaurantRepository implements RestaurantRepositoryInterface
{

    public function findOneById($id): ?Restaurant
    {
        $restaurants = array_filter($this->findAll(), function(Restaurant $restaurant) use ($id) {
            return $restaurant->getId() == $id;
        });

        // Return the first restaurant only
        return reset($restaurants);
    }

    public function findAll()
    {
        $r1 = new Restaurant(1);
        $r1
            ->setName('Hoki Sushi')
            ->setLikes(5)
            ->setDislikes(1);

        $r2 = new Restaurant(2);
        $r2
            ->setName('Le 5 Sens')
            ->setLikes(25)
            ->setDislikes(2);

        $r3 = new Restaurant(3);
        $r3
            ->setName('231 East Street')
            ->setLikes(12)
            ->setDislikes(3);

        $addr1 = new Address(1);
        $addr1
            ->setStreet('2 Place de la Renaissance')
            ->setZipCode('92270')
            ->setCity('Bois-Colombes');

        $addr2 = new Address(2);
        $addr2
            ->setStreet('12 Place de la Renaissance')
            ->setZipCode('92270')
            ->setCity('Bois-Colombes');

        $addr3 = new Address(3);
        $addr3
            ->setStreet('2 Rue De La Pepiniere')
            ->setZipCode('75008')
            ->setCity('Paris');
            
        $r1->setAddress($addr1);
        $r2->setAddress($addr2);
        $r3->setAddress($addr3);

        return [$r1, $r2, $r3];

        return [$r1, $r2, $r3];
    }
}