<?php

namespace App\Repository;

use App\Entity\Ranking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ranking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ranking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ranking[]    findAll()
 * @method Ranking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ranking::class);
    }

    public function findUserRatedMeetings($user_id)
    {
        return $this->createQueryBuilder('fm')
            ->andWhere('fm.user = :user')
            ->setParameter('user', $user_id)
            ->orderBy('fm.id', 'ASC')
            ->select('fm.meeting as meeting')
            ->distinct()
            ->getQuery()
            ->getResult()
            ;
    }

    public function findUserUnratedMeetings($user_id)
    {
        return $this->createQueryBuilder('fm')
            ->andWhere('fm.user != :user')
            ->setParameter('user', $user_id)
            ->orderBy('fm.id', 'ASC')
            ->select('fm.meeting as meeting')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Ranking[] Returns an array of Ranking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ranking
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
