<?php
/**
 * Traitement de suppression d'un établissement.
 * Reçoit l'identifiant de l'établissement via GET,
 * délègue la suppression à EtablissementRepository,
 * puis redirige vers la page des établissements.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/EtablissementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_GET['id_etablissement'])) {
    $id_etablissement = $_GET['id_etablissement'];

    $repo = new EtablissementRepository($bdd);

    // Suppression en base de données
    $result = $repo->supprimer(id_etablissement);

    if ($result) {
        // Succès : retour à la page des établissements
        header('Location: ../../vue/CreeEtablissement.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'établissement.";
    }
} else {
    echo "ID établissement manquant.";
}
?>