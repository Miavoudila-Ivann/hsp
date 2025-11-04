<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Medecin.php';
require_once '../../src/repository/MedecinRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    $id = $_POST['id_medecin'] ?? null;
    $ref_specialite = $_POST['ref_specialite'] ?? null;
    $ref_hopital = $_POST['ref_hopital'] ?? null;
    $ref_etablissement = $_POST['ref_etablissement'] ?? null;

    if ($id && $ref_specialite && $ref_hopital && $ref_etablissement) {
        $db = new Bdd();
        $repo = new MedecinRepository($db->getBdd());
        $medecin = new Medecin($ref_specialite, $ref_hopital, $ref_etablissement, $id);

        if ($repo->modifier($medecin)) {
            header('Location: ../../vue/ListeMedecin.php');
            exit();
        } else {
            die("Erreur lors de la modification.");
        }
    } else {
        die("Données manquantes.");
    }
} else {
    header('Location: ../../vue/ListeMedecin.php');
    exit();
}
?>