<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Specialite.php';
require_once '../../src/repository/SpecialiteRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    $id = $_POST['id_specialite'] ?? null;
    $libelle = trim($_POST['libelle'] ?? '');

    if ($id && !empty($libelle)) {
        $db = new Bdd();
        $repo = new SpecialiteRepository($db->getBdd());
        $specialite = new Specialite($libelle, $id);

        if ($repo->modifier($specialite)) {
            header('Location: ../../vue/ListeSpecialite.php');
            exit();
        } else {
            die("Erreur lors de la modification.");
        }
    } else {
        die("Données manquantes.");
    }
} else {
    header('Location: ../../vue/ListeSpecialite.php');
    exit();
}
?>