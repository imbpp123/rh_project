<?php

namespace App\Repository;

use App\Entity\HotelChain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HotelChain|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelChain|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelChain[]    findAll()
 * @method HotelChain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelChainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelChain::class);
    }
}
