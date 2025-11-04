<?php
// ✅ Chemin correct : deux ".." pour remonter de "traitement/" vers "hsp/", puis entrer dans "src/"
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Medecin.php';
require_once '../../src/repository/MedecinRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    $ref_specialite = $_POST['ref_specialite'] ?? null;
    $ref_hopital = $_POST['ref_hopital'] ?? null;
    $ref_etablissement = $_POST['ref_etablissement'] ?? null;

    if ($ref_specialite && $ref_hopital && $ref_etablissement) {
        $db = new Bdd();
        $repo = new MedecinRepository($db->getBdd());
        $medecin = new Medecin($ref_specialite, $ref_hopital, $ref_etablissement);

        if ($repo->ajouter($medecin)) {
            header('Location: ../../vue/ListeMedecin.php');
            exit();
        } else {
            die("Erreur lors de l'ajout.");
        }
    } else {
        die("Tous les champs sont obligatoires.");
    }
} else {
    header('Location: ../../vue/CreeMedecin.php');
    exit();
}
?>