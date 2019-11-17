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

        foreach ($scoreArray as $score) {
            $doctrineManager->persist($this->createDefaultReview()->setScore($score));
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

}