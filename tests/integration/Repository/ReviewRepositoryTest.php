<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Tests\Integration\DbHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewRepositoryTest extends WebTestCase
{
    use DbHelperTrait;

    /**
     * @var ReviewRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->truncateEntity(Review::class);

        $this->repository = $this->getDoctrineContainer()->getRepository(Review::class);
    }

    public function testGetHotelAvgScore()
    {
        $doctrineManager = $this->getDoctrineManager();
        $scoreArray = [10, 5, 7];

        $doctrineManager->persist(
            $this->createDefaultReview()
                ->setHotelId(2)
                ->setScore(20)
        );
        foreach ($scoreArray as $score) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore($score)
            );
        }
        $doctrineManager->flush();

        self::assertEquals(
            round(array_sum($scoreArray) / count($scoreArray), 4),
            $this->repository->getHotelAvgScore(1)
        );
    }

    public function testGetHotelAvgNoReview()
    {
        self::assertEquals(
            0,
            $this->repository->getHotelAvgScore(1)
        );
    }

    public function testGetHotelAvgScoreWithStartFrom()
    {
        $doctrineManager = $this->getDoctrineManager();
        $scoreArray = [10, 5, 7];

        $doctrineManager->persist(
            $this->createDefaultReview()
                ->setHotelId(2)
                ->setScore(20)
        );
        foreach ($scoreArray as $score) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore($score)
                    ->setCreatedAt(new \DateTime())
            );
        }

        $doctrineManager->persist(
            $this->createDefaultReview()
                ->setHotelId(1)
                ->setScore(30)
                ->setCreatedAt((new \DateTime())->modify("-10years"))
        );
        $doctrineManager->flush();

        self::assertEquals(
            round(array_sum($scoreArray) / count($scoreArray), 4),
            $this->repository->getHotelAvgScore(1, 365)
        );
        self::assertEquals(
            13,
            $this->repository->getHotelAvgScore(1, -1)
        );
        self::assertEquals(
            13,
            $this->repository->getHotelAvgScore(1, 0)
        );
    }

}