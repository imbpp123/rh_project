<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Hotel;
use App\Entity\HotelChain;
use App\Tests\Integration\DbHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChainControllerTest extends WebTestCase
{
    use DbHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->truncateEntity(HotelChain::class);
        $this->truncateEntity(Hotel::class);
    }

    public function testGetListAction()
    {
        $chain1 = $this->createDefaultChain();
        $chain2 = $this->createDefaultChain();

        $doctrineManager = $this->getDoctrineManager();
        $doctrineManager->persist($chain1);
        $doctrineManager->persist($chain2);
        $doctrineManager->flush();

        $client = static::createClient();

        $client->request('GET', '/api/v2/chain');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = static::$container->get('serializer')->serialize([$chain1, $chain2], 'json');
        $this->assertEquals($data, $client->getResponse()->getContent());

    }

    public function testGetListActionNoChain()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v2/chain');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("[]", $client->getResponse()->getContent());
    }

    public function testGetHotelListAction()
    {
        $doctrineManager = $this->getDoctrineManager();

        $chain1 = $this->createDefaultChain();
        $chain2 = $this->createDefaultChain();

        $doctrineManager->persist($chain1);
        $doctrineManager->persist($chain2);
        $doctrineManager->flush();

        $hotel1 = $this->createDefaultHotel()->setChainId($chain1->getId());
        $hotel2 = $this->createDefaultHotel()->setChainId($chain1->getId());
        $hotel3 = $this->createDefaultHotel()->setChainId($chain2->getId());

        $doctrineManager->persist($hotel1);
        $doctrineManager->persist($hotel2);
        $doctrineManager->persist($hotel3);
        $doctrineManager->flush();

        $client = static::createClient();
        $client->request('GET', '/api/v2/chain/' . $chain1->getId() . "/hotel");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = static::$container->get('serializer')->serialize([$hotel1, $hotel2], 'json');
        $this->assertEquals($data, $client->getResponse()->getContent());
    }

    public function testGetHotelListActionNoHotel()
    {
        $doctrineManager = $this->getDoctrineManager();

        $chain1 = $this->createDefaultChain();
        $chain2 = $this->createDefaultChain();

        $doctrineManager->persist($chain1);
        $doctrineManager->persist($chain2);
        $doctrineManager->flush();

        $client = static::createClient();
        $client->request('GET', '/api/v2/chain/' . $chain1->getId() . "/hotel");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals("[]", $client->getResponse()->getContent());
    }

    public function testGetHotelListActionNoChain()
    {
        $client = static::createClient();
        $client->request('GET', "/api/v2/chain/1/hotel");
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}