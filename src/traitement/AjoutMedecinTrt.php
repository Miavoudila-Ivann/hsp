<?php
/**
 * Traite le formulaire d'ajout d'un médecin.
 * Vérifie que la spécialité, l'hôpital et l'établissement sont renseignés,
 * puis insère le médecin en base. Redirige vers la liste des médecins après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Medecin.php';
require_once '../../src/repository/MedecinRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    $ref_specialite    = $_POST['ref_specialite'] ?? null;
    $ref_hopital       = $_POST['ref_hopital'] ?? null;
    $ref_etablissement = $_POST['ref_etablissement'] ?? null;

    // Vérification que les trois références obligatoires sont présentes
    if ($ref_specialite && $ref_hopital && $ref_etablissement) {
        $db = new Bdd();
        $repo = new MedecinRepository($db->getBdd());
        $medecin = new Medecin($ref_specialite, $ref_hopital, $ref_etablissement);

        // Insertion en base de données
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
    // Accès direct sans POST : redirection vers le formulaire
    header('Location: ../../vue/CreeMedecin.php');
    exit();
}
?>