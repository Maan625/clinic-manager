<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    //    /**
    //     * @return Patient[] Returns an array of Patient objects
    //     */
    public function findBySearch(string $search): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.firstName LIKE :search ')
            ->orWhere('p.lastName LIKE :search')
            ->orWhere('p.email LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('p.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Patient
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
