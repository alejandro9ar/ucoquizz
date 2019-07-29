<?php


namespace App\Repository;

use App\Entity\PlayerAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Matrix\identity;

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

    public function findByUser($value)
    {
         $gb = $this->createQueryBuilder('p') ;

         $gb->select('count(p.correct)')
             ->innerJoin('p.user','c')
             ->innerJoin('p.gamesession','g')
             ->addSelect('c.id')
             ->andWhere('g.id = :val')
             ->andWhere('p.correct = true')
             ->setParameter('val', $value)
             ->groupBy('c.id');


         return $gb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return PlayerAnswer[] Returns an array of PlayerAnswer objects
    //  */

    public function findByQuestion($value)
    {
        $gb = $this->createQueryBuilder('p') ;

        $gb->select('count(p.correct)')
            ->innerJoin('p.question','q')
            ->innerJoin('p.gamesession','g')
            ->addSelect('q.title')
            ->andWhere('g.id = :val')
            ->andWhere('p.correct = true')
            ->setParameter('val', $value)
            ->groupBy('q.title');


        return $gb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return PlayerAnswer[] Returns an array of PlayerAnswer objects
    //  */

    public function findByAverageDurationOfAnswer($value)
    {
        $gb = $this->createQueryBuilder('p') ;

        $gb->select('avg(p.DurationOfAnswer)')
            ->innerJoin('p.user','c')
            ->innerJoin('p.gamesession','g')
            ->addSelect('c.id')
            ->andWhere('g.id = :val')
            ->setParameter('val', $value)
            ->groupBy('c.id');


        return $gb->getQuery()->getArrayResult();
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
