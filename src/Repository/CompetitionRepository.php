<?php

namespace App\Repository;

use App\Entity\Competition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competition>
 */
class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competition::class);
    }

    // Exemple de méthode personnalisée (facultatif)
    public function findWithResultats(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.resultats', 'r')
            ->addSelect('r')
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Tu peux ajouter d'autres méthodes personnalisées ici si besoin.
}
