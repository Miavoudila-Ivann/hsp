<?php
/**
 * Traitement de modification d'une réponse du forum.
 * Valide les champs obligatoires (contenu, date, référence post, référence auteur),
 * construit l'objet Reponse et délègue la mise à jour à ReponseRepository.
 * Redirige vers la liste des réponses après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Reponse.php';
require_once '../../src/repository/ReponseRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérifie que tous les champs obligatoires sont présents
    if (!empty($idReponse) && !empty($contenue) && !empty($date_post) && !empty($idref_post) && !empty($idRef_auteur)) {
        $reponse = new Reponse($idReponse, $contenue, $date_post, $idref_post, $idRef_auteur);
        $repo = new ReponseRepository($bdd);

        // Mise à jour en base de données
        $result = $repo->modifier($reponse);

        if ($result) {
            // Succès : retour à la liste des réponses
            header('Location: ../../vue/ListeReponse.php');
            exit();
        } else {
            echo "Erreur lors de la modification.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

