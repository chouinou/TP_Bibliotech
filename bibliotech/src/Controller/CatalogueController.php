<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LivreRepository;


final class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_catalogue')]
    public function index(Request $request,
        LivreRepository $livreRepository): Response
    { 
        $tousLesLivres = $livreRepository->findAll();

        $genre = $request->query->get('genre', '');
        $auteur = $request->query->get('auteur', '');
        $tri = $request->query->get('tri', '');

        $genres = [];
        $auteurs = [];

        foreach ($tousLesLivres as $livre) {
            $genres[] = $livre->getGenre();
            $auteurs[] = $livre->getAuteur();
        }

        $genres = array_unique($genres);
        sort($genres);
        $auteurs = array_unique($auteurs);
        sort($auteurs);

        $livres = array_filter($tousLesLivres, function ($livre) use ($genre, $auteur) {

            if ($genre !== '' && $livre->getGenre() !== $genre) {
                return false;
            }

            if ($auteur !== '' && $livre->getAuteur() !== $auteur) {
                return false;
            }

            return true;
        });
        // usort pour trier en fonction des paramètres indiqués
        if ($tri === 'annee_desc') {
            usort($livres, function ($a, $b) {
                return $b->getAnnee() - $a->getAnnee();
            });
        } elseif ($tri === 'annee_asc') {
            usort($livres, function ($a, $b) {
                return $a->getAnnee() - $b->getAnnee();
            });
        } else {
            usort($livres, function ($a, $b) {
                return strcmp($a->getTitre(), $b->getTitre());
            });}

        return $this->render('catalogue/index.html.twig', [
            'livres' => $livres,
            'genres' => $genres,
            'auteurs' => $auteurs,
            'filtres' => [
                'genre' => $genre,
                'auteur' => $auteur,
                'tri' => $tri,
            ]],);
    }
}
