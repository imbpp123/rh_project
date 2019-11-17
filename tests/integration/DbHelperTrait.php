<?php

namespace App\Tests\Integration;

use App\Entity\Hotel;
use App\Entity\HotelChain;
use App\Entity\Review;

trait DbHelperTrait
{

    protected function truncateEntity(string $classname)
    {
        $entityManager = $this->getDoctrineManager();
        $connection = $entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $entityMetadata = $entityManager->getClassMetadata($classname);
        $sql = $dbPlatform->getTruncateTableSql($entityMetadata->getTableName());
        $connection->executeUpdate($sql);
    }

    protected function getDoctrineContainer()
    {
        return static::$container->get('doctrine');
    }

    protected function getDoctrineManager()
    {
        return $this->getDoctrineContainer()->getManager();
    }

    protected function createDefaultReview()
    {
        $entity = new Review();
        $entity->setHotelId(1);
        $entity->setText("Lorem Ipsum");
        $entity->setScore(7);

        return $entity;
    }

    protected function createDefaultChain()
    {
        $entity = new HotelChain();
        $entity->setName("Lorem Ipsum");

        return $entity;
    }

    protected function createDefaultHotel()
    {
        $entity = new Hotel();
        $entity->setName("Lorem Ipsum?");
        $entity->setAddress("Some address some where");
        $entity->setChainId(1);
        $entity->setRooms(2);

        return $entity;
    }
}