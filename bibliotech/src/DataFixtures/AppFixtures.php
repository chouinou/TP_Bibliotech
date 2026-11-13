<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use App\Entity\Adherent;
use App\Entity\Emprunt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Livres
        $catalogue = [
            ['1984', 'George Orwell', 'Science-fiction', 1949, 3],
            ['Dune', 'Frank Herbert', 'Science-fiction', 1965, 2],
            ['Fondation', 'Isaac Asimov', 'Science-fiction', 1951, 2],
            ['Germinal', 'Émile Zola', 'Roman', 1885, 3],
            ["L'Étranger", 'Albert Camus', 'Roman', 1942, 2],
            ['Le Chien des Baskerville', 'Arthur Conan Doyle', 'Policier', 1902, 2],
            ['Le Comte de Monte-Cristo', 'Alexandre Dumas', 'Aventure', 1844, 2],
            ["Le Crime de l'Orient-Express", 'Agatha Christie', 'Policier', 1934, 3],
            ['Le Tour du monde en 80 jours', 'Jules Verne', 'Aventure', 1872, 4],
            ['Les Misérables', 'Victor Hugo', 'Roman', 1862, 2],
            ['Madame Bovary', 'Gustave Flaubert', 'Roman', 1857, 1],
            ['Vingt mille lieues sous les mers', 'Jules Verne', 'Aventure', 1870, 3],
        ];

        $livres = [];

        foreach ($catalogue as [$titre, $auteur, $genre, $annee, $exemplaires]) {

            $livre = new Livre();

            $livre->setTitre($titre);
            $livre->setAuteur($auteur);
            $livre->setGenre($genre);
            $livre->setAnnee($annee);
            $livre->setExemplaires($exemplaires);
            $livre->setResume($faker->paragraph());
            $livre->setCouverture(
                'https://placehold.co/400x600?text=' . urlencode($titre)
            );

            $manager->persist($livre);
            $livres[] = $livre;
        }

        // Adhérents
        $adherents = [];

        for ($i = 0; $i < 20; $i++) {

            $adherent = new Adherent();

            $adherent->setNom($faker->name());
            $adherent->setVille($faker->city());
            $adherent->setEmail($faker->unique()->safeEmail());

            $manager->persist($adherent);
            $adherents[] = $adherent;
        }

        // Emprunts
        $empruntsEnCours = [];

        foreach ($livres as $livre) {
            $empruntsEnCours[spl_object_id($livre)] = 0;
        }

        for ($i = 0; $i < 60; $i++) {

            $emprunt = new Emprunt();

            $adherent = $faker->randomElement($adherents);
            $livre = $faker->randomElement($livres);

            $dateEmprunt = DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('-6 months', 'now')
            );

            $emprunt->setDateEmprunt($dateEmprunt);

            $adherent->addEmpruntsAssocy($emprunt);

            $idLivre = spl_object_id($livre);

            $disponible =
                $empruntsEnCours[$idLivre] < $livre->getExemplaires();

            // 7/10 chances que l'emprunt soit rendu
            if ($faker->boolean(70) || !$disponible) {
                // à une date aléatoire de 7 à 30 jours
                $dateRetour = $dateEmprunt->modify(
                    '+' . $faker->numberBetween(7, 30) . ' days'
                );

                $emprunt->setDateRetour($dateRetour);

            } else {
                $emprunt->setDateRetour(null);
                $empruntsEnCours[$idLivre]++;
            }

            $livre->addEmpruntsAssocy($emprunt);

            $manager->persist($emprunt);
        }
        $manager->flush();
    }
}
