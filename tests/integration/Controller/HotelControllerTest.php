<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Hotel;
use App\Entity\Review;
use App\Tests\Integration\DbHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HotelControllerTest extends WebTestCase
{
    use DbHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->truncateEntity(Hotel::class);
        $this->truncateEntity(Review::class);
    }

    public function testGetListAction()
    {
        $entity1 = $this->createDefaultHotel();
        $entity2 = $this->createDefaultHotel();

        $doctrineManager = $this->getDoctrineManager();
        $doctrineManager->persist($entity1);
        $doctrineManager->persist($entity2);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = static::$container->get('serializer')->serialize([$entity1, $entity2], 'json');
        $this->assertEquals($data, $client->getResponse()->getContent());
    }

    public function testGetListByUuidAction()
    {
        $entity1 = $this->createDefaultHotel();
        $entity2 = $this->createDefaultHotel();

        $doctrineManager = $this->getDoctrineManager();
        $doctrineManager->persist($entity1);
        $doctrineManager->persist($entity2);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel?uuid=' . $entity1->getUuid());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = static::$container->get('serializer')->serialize([$entity1], 'json');
        $this->assertEquals($data, $client->getResponse()->getContent());
    }

    public function testGetListActionNoData()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals("[]", $client->getResponse()->getContent());
    }

    public function testGetReviewListAction()
    {
        $doctrineManager = $this->getDoctrineManager();

        $hotel1 = $this->createDefaultHotel();
        $hotel2 = $this->createDefaultHotel();

        $doctrineManager->persist($hotel1);
        $doctrineManager->persist($hotel2);
        $doctrineManager->flush();

        $review1 = $this->createDefaultReview()->setHotelId($hotel1->getId());
        $review2 = $this->createDefaultReview()->setHotelId($hotel1->getId());

        $doctrineManager->persist($review1);
        $doctrineManager->persist($review2);
        $doctrineManager->persist($this->createDefaultReview()->setHotelId($hotel2->getId()));
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel/' . $hotel1->getId() . '/review');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = static::$container->get('serializer')->serialize([$review1, $review2], 'json');
        $this->assertEquals($data, $client->getResponse()->getContent());
    }

    public function testGetReviewListActionNoReview()
    {
        $doctrineManager = $this->getDoctrineManager();

        $hotel1 = $this->createDefaultHotel();

        $doctrineManager->persist($hotel1);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel/' . $hotel1->getId() . "/review");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals("[]", $client->getResponse()->getContent());
    }

    public function testGetReviewListActionNoHotel()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel/1/review');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGetScoreAverageAction()
    {
        $doctrineManager = $this->getDoctrineManager();

        $hotel1 = $this->createDefaultHotel();
        $hotel2 = $this->createDefaultHotel();

        $doctrineManager->persist($hotel1);
        $doctrineManager->persist($hotel2);
        $doctrineManager->flush();

        $review1 = $this->createDefaultReview()->setHotelId($hotel1->getId())->setScore(10);
        $review2 = $this->createDefaultReview()->setHotelId($hotel1->getId())->setScore(1);
        $review3 = $this->createDefaultReview()->setHotelId($hotel2->getId())->setScore(2);
        $review4 = $this->createDefaultReview()->setHotelId($hotel2->getId())->setScore(8);

        $doctrineManager->persist($review1);
        $doctrineManager->persist($review2);
        $doctrineManager->persist($review3);
        $doctrineManager->persist($review4);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel/' . $hotel1->getId() . '/average');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            ($review1->getScore() + $review2->getScore()) / 2,
            $client->getResponse()->getContent()
        );
        $this->assertEquals(3600, $client->getResponse()->getMaxAge());
    }

    public function testGetScoreAverageActionNoReview()
    {
        $doctrineManager = $this->getDoctrineManager();

        $hotel1 = $this->createDefaultHotel();
        $hotel2 = $this->createDefaultHotel();

        $doctrineManager->persist($hotel1);
        $doctrineManager->persist($hotel2);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel/' . $hotel1->getId() . '/average');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            0,
            $client->getResponse()->getContent()
        );
    }

    public function testGetScoreAverageActionNoHotel()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v2/hotel/1/average');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}