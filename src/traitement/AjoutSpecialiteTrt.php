<?php
/**
 * Traite le formulaire d'ajout d'une spécialité médicale.
 * Le libellé est le seul champ requis.
 * Insère la spécialité en base et redirige vers la liste des spécialités après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Specialite.php';
require_once '../../src/repository/SpecialiteRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    $libelle = trim($_POST['libelle'] ?? '');

    // Validation : le libellé est obligatoire
    if (!empty($libelle)) {
        $db = new Bdd();
        $repo = new SpecialiteRepository($db->getBdd());
        $specialite = new Specialite($libelle);

        // Insertion en base de données
        if ($repo->ajouter($specialite)) {
            header('Location: ../../vue/ListeSpecialite.php');
            exit();
        } else {
            die("Erreur lors de l'ajout.");
        }
    } else {
        die("Le libellé est obligatoire.");
    }
} else {
    // Accès direct sans POST : redirection vers le formulaire
    header('Location: ../../vue/CreeSpecialite.php');
    exit();
}
?>