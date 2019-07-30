<?php


namespace App\Repository;

use App\Entity\User;
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
             ->addSelect('c.username')
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
            ->addSelect('c.username')
            ->andWhere('g.id = :val')
            ->setParameter('val', $value)
            ->groupBy('c.id');


        return $gb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return PlayerAnswer[] Returns an array of PlayerAnswer objects
    //  */

    public function findByMorePutuation($value)
    {
        $gb = $this->createQueryBuilder('p') ;

        $gb->select('sum(p.puntuation)')
            ->innerJoin('p.user','c')
            ->innerJoin('p.gamesession','g')
            ->addSelect('c.username')
            ->andWhere('g.id = :val')
            ->setParameter('val', $value)
            ->groupBy('c.id')
            ->orderby('sum(p.puntuation)','DESC');

        dump($gb->getQuery()->getArrayResult());

        return $gb->getQuery()->getArrayResult();
    }

    // /**
    //  * @return PlayerAnswer[] Returns an array of PlayerAnswer objects
    //  */

    public function findByQuestionData($id,$user )
    {
        $gb = $this->createQueryBuilder('p') ;

        $gb->innerJoin('p.question','q')
            ->select('q.title')
            ->innerJoin('p.gamesession','g')
            ->innerJoin('p.user','u')
            ->addSelect('q.title')
            ->addSelect('q.id')
            ->addSelect('p.puntuation')
            ->addSelect('p.correct')
            ->addSelect('p.DurationOfAnswer')
            ->andWhere('g.id = :id')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user)
            ->setParameter('id', $id);


        dump($gb->getQuery()->getArrayResult());

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
