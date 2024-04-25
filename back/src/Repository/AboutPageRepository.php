<?php

namespace App\Repository;

use App\Entity\AboutPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AboutPage>
 *
 * @method AboutPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AboutPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AboutPage[]    findAll()
 * @method AboutPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AboutPage::class);
    }

//    /**
//     * @return AboutPage[] Returns an array of AboutPage objects
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

//    public function findOneBySomeField($value): ?AboutPage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
