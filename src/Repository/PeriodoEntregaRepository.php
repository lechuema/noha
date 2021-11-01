<?php

namespace App\Repository;

use App\Entity\PeriodoEntrega;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PeriodoEntrega|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeriodoEntrega|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeriodoEntrega[]    findAll()
 * @method PeriodoEntrega[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodoEntregaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeriodoEntrega::class);
    }

    // /**
    //  * @return PeriodoEntrega[] Returns an array of PeriodoEntrega objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PeriodoEntrega
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
