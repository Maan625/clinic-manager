<?php

namespace App\Repository;

use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\query;

/**
 * @extends ServiceEntityRepository<Doctor>
 */
class DoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    //    /**
    //     * @return Doctor[] Returns an array of Doctor objects
    //     */
    public function findBySearch(string $search): Query
    {
        return $this->createQueryBuilder('d')
            ->Where('d.firstName LIKE :search ')
            ->orWhere('d.lastName LIKE :search')
            ->orWhere('d.email LIKE :search')
            ->orWhere('d.specialty LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('d.createdAt', 'ASC')
            ->getQuery()

        ;
    }
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'ASC')
            ->getQuery()

        ;
    }

    //    public function findOneBySomeField($value): ?Doctor
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
