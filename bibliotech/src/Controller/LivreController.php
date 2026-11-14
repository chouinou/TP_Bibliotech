<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LivreRepository;

final class LivreController extends AbstractController
{
    #[Route('/livre/{id}', name: 'app_livre', requirements: ['id' => '\d+'])]
    public function show(int $id, LivreRepository $livreRepository): Response
    {
        $livre = $livreRepository->find($id);

        if (!$livre) {
            throw $this->createNotFoundException('Livre introuvable');
        }

        // Livres du même genre
        $similaires = $livreRepository->findMemeGenre($livre);

        // Derniers emprunts
        $derniersEmprunts = $livre->getEmpruntsAssocies()->toArray();

        usort($derniersEmprunts, function ($a, $b) {
            return $b->getDateEmprunt() <=> $a->getDateEmprunt();
        });

        $derniersEmprunts = array_slice($derniersEmprunts, 0, 5);

        return $this->render('livre/livre.html.twig', [
            'livre' => $livre,
            'similaires' => $similaires,
            'derniersEmprunts' => $derniersEmprunts,
        ]);
    }
}