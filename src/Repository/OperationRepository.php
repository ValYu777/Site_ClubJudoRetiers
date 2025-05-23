<?php


namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Operation>
 *
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    // Exemple de méthode personnalisée : récupérer les opérations actives (celles dont la date de fin n'est pas dépassée)
    public function findActiveOperations(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.dateFin >= :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('o.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Exemple d'une autre méthode personnalisée : récupérer les opérations dans une période donnée
    public function findOperationsByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.dateDebut >= :startDate')
            ->andWhere('o.dateFin <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('o.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode de recherche par nom
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.nom LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('o.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
