<?php
/**
 * Traite le formulaire de création d'un événement hospitalier.
 * Valide les champs obligatoires (titre, date, lieu, nombre de places, type)
 * et insère l'événement en base. Redirige vers la page de création d'événement après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Evenement.php';
require_once '../../src/repository/EvenementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($id_evenement) && !empty($date_evenement) && !empty($description) && !empty($lieu) && !empty($nb_place) && !empty($titre) && !empty($type_evenement)) {
        $evenement = new Evenement($id_evenement, $date_evenement, $description, $lieu, $nb_place, $titre, $type_evenement);
        $repo = new EvenementRepository($bdd);

        // Insertion en base de données
        $result = $repo->ajouter($evenement);

        if ($result) {
            header('Location: ../../vue/CreeEvenement.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout de l'evenement.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
