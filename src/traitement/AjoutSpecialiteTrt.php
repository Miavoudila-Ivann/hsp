<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Specialite.php';
require_once '../../src/repository/SpecialiteRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    $libelle = trim($_POST['libelle'] ?? '');

    if (!empty($libelle)) {
        $db = new Bdd();
        $repo = new SpecialiteRepository($db->getBdd());
        $specialite = new Specialite($libelle);

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
    header('Location: ../../vue/CreeSpecialite.php');
    exit();
}
?>