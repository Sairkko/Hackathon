<?php

namespace App\Repository;

use App\Entity\AtelierContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AtelierContent>
 *
 * @method AtelierContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method AtelierContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method AtelierContent[]    findAll()
 * @method AtelierContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AtelierContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AtelierContent::class);
    }

//    /**
//     * @return AtelierContent[] Returns an array of AtelierContent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AtelierContent
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
