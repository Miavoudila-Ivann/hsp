<?php
/**
 * Traitement de modification d'un événement.
 * Valide les champs obligatoires (date, description, lieu, nombre de places,
 * titre, type d'événement), construit l'objet Evenement et délègue
 * la mise à jour à EvenementRepository.
 * Redirige vers la page des événements après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Evenement.php';
require_once '../../src/repository/EvenementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérifie que tous les champs obligatoires sont présents
    if (!empty($id_evenement) && !empty($date_evenement) && !empty($description) && !empty($lieu) && !empty($nb_place) && !empty($titre) && !empty($type_evenement)) {
        $evenement = new Evenement($id_evenement, $date_evenement, $description, $lieu, $nb_place, $titre, $type_evenement);
        $repo = new EvenementRepository($bdd);

        // Mise à jour en base de données
        $result = $repo->modifier($evenement);

        if ($result) {
            // Succès : retour à la page des événements
            header('Location: ../vue/CreeEvenement.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'évenement.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>