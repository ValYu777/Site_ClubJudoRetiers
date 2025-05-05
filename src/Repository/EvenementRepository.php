<?php

// src/Repository/EvenementRepository.php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * Méthode pour récupérer les événements à venir dans un intervalle de temps
     *
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return Evenement[]
     */
    public function findUpcomingEvents(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.start >= :startDate')
            ->andWhere('e.start <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('e.start', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
