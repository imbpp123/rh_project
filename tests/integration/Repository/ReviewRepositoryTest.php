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

    public function testGetMonthlyAvgScore()
    {
        $doctrineManager = $this->getDoctrineManager();

        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(10)
                    ->setCreatedAt(new \DateTime("2019-10-01 10:00:00"))
            );
        }
        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(5)
                    ->setCreatedAt(new \DateTime("2019-11-02 10:00:00"))
            );
        }
        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(5)
                    ->setCreatedAt(new \DateTime("2019-12-03 10:00:00"))
            );
        }
        $doctrineManager->flush();

        $result = $this->repository->getMonthlyAvgScore(
            1,
            new \DateTime("2019-09-30 00:00:00"),
            new \DateTime("2019-12-05 00:00:00")
        );

        self::assertEquals(3, count($result));
        foreach ($result as $row) {
            if ($row['key_field'] == 10) {
                self::assertEquals("10.0000", $row['score']);
            }
            if ($row['key_field'] == 11) {
                self::assertEquals("5.0000", $row['score']);
            }
            if ($row['key_field'] == 12) {
                self::assertEquals("5.0000", $row['score']);
            }
        }
    }

    public function testGetWeeklyAvgScore()
    {
        $doctrineManager = $this->getDoctrineManager();

        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(10)
                    ->setCreatedAt(new \DateTime("2019-10-01 10:00:00"))
            );
        }
        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(5)
                    ->setCreatedAt(new \DateTime("2019-11-02 10:00:00"))
            );
        }
        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(5)
                    ->setCreatedAt(new \DateTime("2019-12-03 10:00:00"))
            );
        }
        $doctrineManager->flush();

        $result = $this->repository->getWeeklyAvgScore(
            1,
            new \DateTime("2019-08-30 00:00:00"),
            new \DateTime("2019-12-05 00:00:00")
        );

        self::assertEquals(3, count($result));
        foreach ($result as $row) {
            if ($row['key_field'] == 39) {
                self::assertEquals("10.0000", $row['score']);
            }
            if ($row['key_field'] == 43) {
                self::assertEquals("5.0000", $row['score']);
            }
            if ($row['key_field'] == 48) {
                self::assertEquals("5.0000", $row['score']);
            }
        }
    }

    public function testGetDailyAvgScore()
    {
        $doctrineManager = $this->getDoctrineManager();

        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(10)
                    ->setCreatedAt(new \DateTime("2019-12-01 10:00:00"))
            );
        }
        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(5)
                    ->setCreatedAt(new \DateTime("2019-12-02 10:00:00"))
            );
        }
        for ($i = 0; $i < 2; $i++) {
            $doctrineManager->persist(
                $this->createDefaultReview()
                    ->setHotelId(1)
                    ->setScore(5)
                    ->setCreatedAt(new \DateTime("2019-12-03 10:00:00"))
            );
        }
        $doctrineManager->flush();

        $result = $this->repository->getDailyAvgScore(
            1,
            new \DateTime("2019-11-30 00:00:00"),
            new \DateTime("2019-12-05 00:00:00")
        );

        self::assertEquals(3, count($result));
        foreach ($result as $row) {
            if ($row['key_field'] == 335) {
                self::assertEquals("10.0000", $row['score']);
            }
            if ($row['key_field'] == 336) {
                self::assertEquals("5.0000", $row['score']);
            }
            if ($row['key_field'] == 337) {
                self::assertEquals("5.0000", $row['score']);
            }
        }
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