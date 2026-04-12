<?php
/**
 * Traitement de modification d'un médecin.
 * Reçoit les références de spécialité, hôpital et établissement via POST,
 * construit l'objet Medecin et délègue la mise à jour à MedecinRepository.
 * Redirige vers la liste des médecins. Toute requête non-POST est aussi redirigée.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Medecin.php';
require_once '../../src/repository/MedecinRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    // Récupération des identifiants et références du formulaire
    $id                = $_POST['id_medecin'] ?? null;
    $ref_specialite    = $_POST['ref_specialite'] ?? null;
    $ref_hopital       = $_POST['ref_hopital'] ?? null;
    $ref_etablissement = $_POST['ref_etablissement'] ?? null;

    // Vérifie que toutes les références sont présentes
    if ($id && $ref_specialite && $ref_hopital && $ref_etablissement) {
        $db = new Bdd();
        $repo = new MedecinRepository($db->getBdd());
        $medecin = new Medecin($ref_specialite, $ref_hopital, $ref_etablissement, $id);

        // Mise à jour en base de données
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
    // Accès direct sans POST : redirection
    header('Location: ../../vue/ListeMedecin.php');
    exit();
}
?>