<?php

namespace App\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetAverage()
    {
        $client = static::createClient();

        $client->request('GET', '/api/average?hotelId=1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('6.25', $client->getResponse()->getContent());

        $client->request('GET', '/api/average?hotelId=2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('7.5', $client->getResponse()->getContent());
    }

    public function testException()
    {
        $this->expectException(\Exception::class);
        $client = static::createClient();
        $client->request('GET', '/api/average');
    }


    public function testGetReviews()
    {
        $client = static::createClient();

        $client->request('GET', '/api/reviews?hotelId=1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('[{"id":"1","hotel_id":"1","text":"Very nice stay","score":"10"},{"id":"2","hotel_id":"1","text":"Average","score":"5"},{"id":"3","hotel_id":"1","text":"Very nice stay, I enjoyed it a lot.","score":"9"},{"id":"4","hotel_id":"1","text":"Worst experience ever.","score":"1"}]', $client->getResponse()->getContent());

        $client->request('GET', '/api/reviews?hotelId=2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('[{"id":"5","hotel_id":"2","text":"The receptionist was not smiling.","score":"5"},{"id":"6","hotel_id":"2","text":"Very nice stay, the reception was really fast.","score":"10"}]', $client->getResponse()->getContent());
    }

    public function testGetHotels()
    {
        $client = static::createClient();

        $client->request('GET', '/api/hotels');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('[{"id":"1","name":"Hotel Alexanderplatz","address":"Alexanderplatz 1, 10409, Berlin","rooms":"150"},{"id":"2","name":"Hotel Alexanderplatz","address":"Alexanderplatz 1, 10409, Berlin","rooms":"150"},{"id":"3","name":"Hotel Alexanderplatz","address":"Alexanderplatz 1, 10409, Berlin","rooms":"150"},{"id":"4","name":"Hotel Alexanderplatz","address":"Alexanderplatz 1, 10409, Berlin","rooms":"150"},{"id":"5","name":"Hotel Alexanderplatz","address":"Alexanderplatz 1, 10409, Berlin","rooms":"150"}]', $client->getResponse()->getContent());
    }
}
