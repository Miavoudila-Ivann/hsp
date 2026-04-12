<?php
/**
 * Traite le formulaire d'ajout d'une réponse à un post du forum.
 * Valide les champs obligatoires (contenu, date, référence du post et de l'auteur)
 * et insère la réponse en base. Redirige vers la liste des réponses après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Reponse.php';
require_once '../../src/repository/ReponseRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérification que tous les champs obligatoires sont renseignés
    if (!empty($idReponse) && !empty($contenue) && !empty($date_post) && !empty($idref_post) && !empty($idRef_auteur)) {
        $reponse = new Reponse($idReponse, $contenue, $date_post, $idref_post, $idRef_auteur);
        $repo = new ReponseRepository($bdd);

        // Insertion en base de données
        $result = $repo->ajouter($reponse);

        if ($result) {
            header('Location: ../../vue/ListeReponse.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
