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
    public function findBySearch(?string $search): array
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.patient', 'p')
            ->leftJoin('a.doctor', 'd')
            ->addSelect('p', 'd')
            ->orderBy('a.appointmentDate', 'ASC');

        if ($search) {
            $qb
                ->andWhere(
                    "p.firstName LIKE :search
                OR p.lastName LIKE :search
                OR CONCAT(p.firstName, ' ', p.lastName) LIKE :search
                OR d.firstName LIKE :search
                OR d.lastName LIKE :search
                OR CONCAT(d.firstName, ' ', d.lastName) LIKE :search
                OR a.reason LIKE :search
                OR a.status LIKE :search"
                )
                ->setParameter('search', '%' . trim($search) . '%');
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'ASC')
            ->getQuery();
    }

    public function hasConflict(Appointment $appointment): bool
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->andWhere('a.doctor = :doctor')
            ->andWhere('a.appointmentDate = :appointmentDate')
            ->setParameter('doctor', $appointment->getDoctor())
            ->setParameter('appointmentDate', $appointment->getAppointmentDate());

        if ($appointment->getId() !== null) {
            $qb
                ->andWhere('a.id != :currentAppointmentId')
                ->setParameter('currentAppointmentId', $appointment->getId());
        }

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult() > 0;
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
