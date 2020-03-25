<?php

namespace App\Repository;

use App\Entity\CompletedHabit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CompletedHabit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompletedHabit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompletedHabit[]    findAll()
 * @method CompletedHabit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompletedHabitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompletedHabit::class);
    }

    // /**
    //  * @return CompletedHabit[] Returns an array of CompletedHabit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompletedHabit
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
