<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\query;



/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    //    /**
    //     * @return Appointment[] Returns an array of Appointment objects
    //     */
    public function findBySearch(string $search): Query
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.patient', 'p')
            ->join('a.doctor', 'd')
            ->where('p.firstName LIKE :search')
            ->orWhere('p.lastName LIKE :search')
            ->orWhere('d.firstName LIKE :search')
            ->orWhere('d.lastName LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('a.createdAt', 'ASC');

        return $qb->getQuery();
    }
public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'ASC')
            ->getQuery();
    }

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
