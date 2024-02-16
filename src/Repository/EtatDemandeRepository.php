<?php

namespace App\Repository;

use App\Entity\EtatDemande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EtatDemande>
 *
 * @method EtatDemande|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatDemande|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatDemande[]    findAll()
 * @method EtatDemande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatDemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatDemande::class);
    }

//    /**
//     * @return EtatDemande[] Returns an array of EtatDemande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EtatDemande
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
