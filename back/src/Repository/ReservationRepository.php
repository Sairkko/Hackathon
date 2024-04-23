<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function countTotalParticipantsForAtelier($atelierId)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('SUM(r.nombre)') // Utilisation de SUM pour additionner les participants
        ->join('r.ateliers', 'a')
            ->where('a.id = :atelierId')
            ->setParameter('atelierId', $atelierId);

        $result = $qb->getQuery()->getSingleScalarResult();
        return (int)$result; // Retourner un entier, même si aucun participant n'est trouvé (retournera 0)
    }

    public function hasUserAlreadyReservedAtelier($userId, $atelierId)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('count(r.id)')
            ->join('r.user', 'u')
            ->join('r.ateliers', 'a')
            ->where('u.id = :userId AND a.id = :atelierId')
            ->setParameters([
                'userId' => $userId,
                'atelierId' => $atelierId,
            ]);

        $count = $qb->getQuery()->getSingleScalarResult();
        return $count > 0;
    }


//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
