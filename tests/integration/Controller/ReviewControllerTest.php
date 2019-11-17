<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Hotel;
use App\Entity\HotelChain;
use App\Entity\Review;
use App\Tests\Integration\DbHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewControllerTest extends WebTestCase
{
    use DbHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->truncateEntity(Review::class);
    }

    public function testGetListAction()
    {
        $doctrineManager = $this->getDoctrineManager();

        $review1 = $this->createDefaultReview()->setHotelId(1);
        $review2 = $this->createDefaultReview()->setHotelId(2);

        $doctrineManager->persist($review1);
        $doctrineManager->persist($review2);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/review');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = static::$container->get('serializer')->serialize([$review1, $review2], 'json');
        $this->assertEquals($data, $client->getResponse()->getContent());
    }

    public function testGetListActionNoReview()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v2/review');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals("[]", $client->getResponse()->getContent());
    }

}