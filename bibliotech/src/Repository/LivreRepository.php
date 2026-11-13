<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function findGenres(): array
    {
        $data = $this->createQueryBuilder('livre')
            ->select('DISTINCT livre.genre')
            ->orderBy('livre.genre', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($data, 'genre');
    }

    public function findAuteurs(): array
    {
        $data = $this->createQueryBuilder('livre')
            ->select('DISTINCT livre.auteur')
            ->orderBy('livre.auteur', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($data, 'auteur');
    }

    public function findCatalogue(
        ?string $genre,
        ?string $auteur,
        ?string $tri
    ): array {
        $data = $this->createQueryBuilder('livre');

        if ($genre != null) {
            $data->andWhere('livre.genre = :genre')
                ->setParameter('genre', $genre);
        }

        if ($auteur != null) {
            $data->andWhere('livre.auteur = :auteur')
                ->setParameter('auteur', $auteur);
        }

        switch ($tri) {
            case 'annee_desc':
                $data->orderBy('livre.Annee', 'DESC');
                break;

            case 'annee_asc':
                $data->orderBy('livre.Annee', 'ASC');
                break;

            default:
                $data->orderBy('livre.titre', 'ASC');
                break;
        }

        return $data->getQuery()->getResult();
    }

    public function findMemeGenre(
        Livre $livre,
        int $max = 4
    ): array {
        return $this->createQueryBuilder('livre')
            ->where('livre.genre = :genre')
            ->andWhere('livre.id != :id')
            ->setParameter('genre', $livre->getGenre())
            ->setParameter('id', $livre->getId())
            ->orderBy('livre.titre', 'ASC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    public function findPlusEmpruntes(int $max = 5): array
    {
        return $this->createQueryBuilder('livre')
            ->select('livre, COUNT(emprunt.id) AS nombreEmprunts')
            ->join('livre.emprunts_associes', 'emprunt')
            ->groupBy('livre.id')
            ->orderBy('nombreEmprunts', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }
}
