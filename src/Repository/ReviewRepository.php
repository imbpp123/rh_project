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

    public function getHotelAvgScore(int $hotelId)
    {
        $queryAvgScore = $this->createQueryBuilder('r')
            ->select("AVG(r.score) as score")
            ->where('r.hotelId = :hotelId')
            ->setParameter('hotelId', $hotelId)
            ->getQuery();
        return (float) round($queryAvgScore->getScalarResult()[0]['score'], 4);
    }
}