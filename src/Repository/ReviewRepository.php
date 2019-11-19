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
}