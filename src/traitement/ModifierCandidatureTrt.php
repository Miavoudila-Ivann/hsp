<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Candidature.php';
require_once '../../src/repository/CandidatureRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_candidature) && !empty($motivation) && !empty($statut) && !empty($date_candidature) && !empty($ref_offre) && !empty($ref_utilisateur)) {
        $candidature = new Candidature($id_candidature, $motivation, $statut, $date_candidature, $ref_offre, $ref_utilisateur);
        $repo = new CandidatureRepository($bdd);

        $result = $repo->modifier($candidature);

        if ($result) {
            header('Location: ../../vue/ListeCandidature.php');
            exit();
        } else {
            echo "Erreur lors de la modification de la candidature.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
