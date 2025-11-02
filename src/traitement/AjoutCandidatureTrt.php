<?php
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Candidature.php';
require_once '../../src/repository/CandidatureRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($motivation) && !empty($statut) && !empty($date_candidature) && !empty($ref_offre) && !empty($ref_utilisateur)) {
        $candidature = new Candidature($id_candidature ?? null, $motivation, $statut, $date_candidature, $ref_offre, $ref_utilisateur);
        $repo = new CandidatureRepository($bdd);

        $result = $repo->ajouter($candidature);

        if ($result) {
            header('Location: ../../vue/AjoutCandidature.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout de la candidature.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
