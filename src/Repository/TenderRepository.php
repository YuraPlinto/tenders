<?php

namespace App\Repository;

use App\Entity\Tender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tender>
 */
class TenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tender::class);
    }

    /**
    * @return Tender[] Returns an array of Tender objects
    */
    public function findTenders($name = null, $date = null, $limit = null, $order = 'asc'): array
    {
       $queryBuilder = $this->createQueryBuilder('t');

        if (\is_int($limit) && ($limit > 0)) {
            $queryBuilder->setMaxResults($limit);
        }

       if (!\is_null($name)) {
           $queryBuilder
               ->andWhere('t.name = :name')
               ->setParameter('name', $name);
       }

       if (!\is_null($date)) {
           $queryBuilder
               ->andWhere('DATE(t.updatedAt) = DATE(:date)')
               ->setParameter('date', $date);
       }

       if ($order == 'desc') {
           $queryBuilder->orderBy('t.id', 'DESC');
       } else {
           // Порядок по умолчанию используется также в том случае, если было передано недопустимое значение параметра
           $queryBuilder->orderBy('t.id', 'ASC');
       }

       return $queryBuilder
           ->getQuery()
           ->getResult();
    }

    //    public function findOneBySomeField($value): ?Tender
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
