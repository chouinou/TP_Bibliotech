<?php

namespace App\Repository;

use App\Entity\Emprunt;
use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function findDerniersPourLivre(Livre $livre, int $max = 5): array
    {
        return $this->createQueryBuilder('emprunt')
            ->where('emprunt.id_livre = :livre')
            ->setParameter('livre', $livre)
            ->orderBy('emprunt.date_emprunt', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }
}
