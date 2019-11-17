<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadHotels($manager);
        $this->loadReviews($manager);
        $manager->flush();
    }

    public function loadHotels($manager)
    {
        $hotel = new Hotel();
        $hotel->setId(1);
        $hotel->setName('Hotel Alexanderplatz');
        $hotel->setAddress('Alexanderplatz 1, 10409, Berlin');
        $hotel->setRooms(150);

        $manager->persist($hotel);

        $hotel = new Hotel();
        $hotel->setId(2);
        $hotel->setName('Hotel Alexanderplatz');
        $hotel->setAddress('Alexanderplatz 1, 10409, Berlin');
        $hotel->setRooms(150);

        $manager->persist($hotel);

        $hotel = new Hotel();
        $hotel->setId(3);
        $hotel->setName('Hotel Alexanderplatz');
        $hotel->setAddress('Alexanderplatz 1, 10409, Berlin');
        $hotel->setRooms(150);

        $manager->persist($hotel);

        $hotel = new Hotel();
        $hotel->setId(4);
        $hotel->setName('Hotel Alexanderplatz');
        $hotel->setAddress('Alexanderplatz 1, 10409, Berlin');
        $hotel->setRooms(150);

        $manager->persist($hotel);

        $hotel = new Hotel();
        $hotel->setId(5);
        $hotel->setName('Hotel Alexanderplatz');
        $hotel->setAddress('Alexanderplatz 1, 10409, Berlin');
        $hotel->setRooms(150);

        $manager->persist($hotel);
    }

    public function loadReviews($manager)
    {
        // hotel 1
        $review = new Review();
        $review->setHotelId(1);
        $review->setText('Very nice stay');
        $review->setScore(10);
        $manager->persist($review);

        $review = new Review();
        $review->setHotelId(1);
        $review->setText('Average');
        $review->setScore(5);
        $manager->persist($review);

        $review = new Review();
        $review->setHotelId(1);
        $review->setText('Very nice stay, I enjoyed it a lot.');
        $review->setScore(9);
        $manager->persist($review);

        $review = new Review();
        $review->setHotelId(1);
        $review->setText('Worst experience ever.');
        $review->setScore(1);
        $manager->persist($review);

        // hotel 2
        $review = new Review();
        $review->setHotelId(2);
        $review->setText('The receptionist was not smiling.');
        $review->setScore(5);
        $manager->persist($review);

        $review = new Review();
        $review->setHotelId(2);
        $review->setText('Very nice stay, the reception was really fast.');
        $review->setScore(10);
        $manager->persist($review);
    }
}
