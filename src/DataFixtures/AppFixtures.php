<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\HotelChain;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $chainArray = $this->loadChain($manager);
        $hotelArray = $this->loadHotels($manager, $chainArray);
        $this->loadReviews($manager, $hotelArray);
    }

    public function loadChain(ObjectManager $manager): array
    {
        $chainArray = [];
        for ($i = 0; $i < 3; $i++) {
            $chain = new HotelChain();
            $chain->setName($this->faker->company);
            $manager->persist($chain);
            $manager->flush();

            $chainArray[] = $chain->getId();
        }

        return $chainArray;
    }

    public function loadHotels(ObjectManager $manager, array $chain): array
    {
        $hotelArray = [];

        for ($i = 0; $i < 5; $i++) {
            $hotel = new Hotel();
            $hotel->setName($this->faker->company);
            $hotel->setAddress($this->faker->address);
            $hotel->setRooms($this->faker->numberBetween(1, 5));
            $hotel->setChainId($this->faker->randomElement($chain));
            $manager->persist($hotel);
            $manager->flush();

            $hotelArray[] = $hotel->getId();
        }
        return $hotelArray;
    }

    public function loadReviews(ObjectManager $manager, array $hotel): void
    {
        for ($i = 0; $i < 500; $i++) {
            $review = new Review();
            $review->setHotelId($this->faker->randomElement($hotel));
            $review->setScore($this->faker->numberBetween(0, 10));
            $review->setText($this->faker->text);
            $manager->persist($review);
        }
        $manager->flush();
    }
}
