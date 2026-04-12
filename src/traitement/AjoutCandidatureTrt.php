<?php
/**
 * Traite le formulaire de dépôt d'une candidature à une offre d'emploi.
 * Valide les champs obligatoires, crée l'objet Candidature et l'insère en base.
 * Redirige vers la page d'ajout de candidature après succès.
 */
session_start();
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Candidature.php';
require_once '../../src/repository/CandidatureRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($motivation) && !empty($statut) && !empty($date_candidature) && !empty($ref_offre) && !empty($ref_utilisateur)) {
        $candidature = new Candidature($id_candidature ?? null, $motivation, $statut, $date_candidature, $ref_offre, $ref_utilisateur);
        $repo = new CandidatureRepository($bdd);

        // Insertion en base de données
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
