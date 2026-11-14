-- Rêquetes SQL pour la partie 5

-- Les livres de Jules Vernes du plus ancien au plus récent.
SELECT *
FROM livre
WHERE auteur = 'Jules Verne'
ORDER BY annee ASC;

-- Le nombre de livres par genre.
SELECT
    genre,
    COUNT(*) AS nombre_livres
FROM livre
GROUP BY genre
ORDER BY nombre_livres;

-- Les emprunts en cours, avec le nom de l'adhérent et le titre du livre.
SELECT
    adherent.nom AS adherent,
    livre.titre AS livre,
    emprunt.date_emprunt
FROM emprunt
-- On utilise INNER JOIN pour relier les tables entre elles
INNER JOIN adherent
    ON emprunt.id_adherent_id = adherent.id
INNER JOIN livre
    ON emprunt.id_livre_id = livre.id
-- emprunts en cours = pas de date de retour
WHERE emprunt.date_retour IS NULL;

-- Les 5 livres les plus empruntés, avec leur nombre d'emprunts.
SELECT
    livre.titre,
    livre.auteur,
    COUNT(emprunt.id) AS nombre_emprunts
FROM livre
INNER JOIN emprunt
    ON livre.id = emprunt.id_livre_id
GROUP BY livre.id, livre.titre, livre.auteur
LIMIT 5;

-- Les adhérents qui n'ont jamais emprunté de livre.
SELECT
    adherent.id,
    adherent.nom,
    adherent.ville,
    adherent.email
FROM adherent
LEFT JOIN emprunt
    ON adherent.id = emprunt.id_adherent_id
WHERE emprunt.id IS NULL;
-- retourne Empty set (0,00 sec) car dans le cadre des fixtures tous les adhérents ont déjà empruntés