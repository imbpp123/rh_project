<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getHotelAvgScore(int $hotelId, int $dayOffset = null)
    {
        $query = $this->createQueryBuilder('r')
            ->select("AVG(r.score) as score")
            ->where('r.hotelId = :hotelId')
            ->setParameter('hotelId', $hotelId)
        ;

        if ($dayOffset > 0) {
            $query->andWhere('r.createdAt BETWEEN :from AND :to')
                ->setParameter('from', (new \DateTime())->modify("-{$dayOffset}days"))
                ->setParameter('to', new \DateTime())
                ;
        }

        return (float) round($query->getQuery()->getScalarResult()[0]['score'], 4);
    }

    public function getDailyAvgScore(int $hotelId, \DateTime $startDate, \DateTime $endDate)
    {
        $query = $this->createQueryBuilder('r')
            ->select("AVG(r.score) as score, DAYOFYEAR(r.createdAt) as key_field")
            ->where("r.hotelId = :hotelId")
            ->andWhere('r.createdAt BETWEEN :from AND :to')
            ->groupBy("key_field")
            ->setParameter("hotelId", $hotelId)
            ->setParameter('from', $startDate)
            ->setParameter('to', $endDate)
        ;
        return $query->getQuery()->getScalarResult();
    }

    public function getWeeklyAvgScore(int $hotelId, \DateTime $startDate, \DateTime $endDate)
    {
        $query = $this->createQueryBuilder('r')
            ->select(["AVG(r.score) as score", "week(r.createdAt) as key_field"])
            ->where("r.hotelId = :hotelId")
            ->andWhere('r.createdAt BETWEEN :from AND :to')
            ->groupBy("key_field")
            ->setParameter("hotelId", $hotelId)
            ->setParameter('from', $startDate)
            ->setParameter('to', $endDate)
        ;
        return $query->getQuery()->getScalarResult();
    }

    public function getMonthlyAvgScore(int $hotelId, \DateTime $startDate, \DateTime $endDate)
    {
        $query = $this->createQueryBuilder('r')
            ->select(["AVG(r.score) as score", "month(r.createdAt) as key_field"])
            ->where("r.hotelId = :hotelId")
            ->andWhere('r.createdAt BETWEEN :from AND :to')
            ->groupBy("key_field")
            ->setParameter("hotelId", $hotelId)
            ->setParameter('from', $startDate)
            ->setParameter('to', $endDate)
        ;
        return $query->getQuery()->getScalarResult();
    }
}