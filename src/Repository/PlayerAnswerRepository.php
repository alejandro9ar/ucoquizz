<?php

namespace App\Repository;

use App\Entity\PlayerAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlayerAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerAnswer[]    findAll()
 * @method PlayerAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerAnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlayerAnswer::class);
    }

    // /**
    //  * @return PlayerAnswer[] Returns an array of PlayerAnswer objects
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


    /*
    public function findOneBySomeField($value): ?PlayerAnswer
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
