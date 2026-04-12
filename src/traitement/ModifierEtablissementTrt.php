<?php
/**
 * Traitement de modification d'un établissement.
 * Valide les champs POST (nom, adresse, site web), construit l'objet
 * et délègue la mise à jour à EtablissementRepository.
 * Redirige vers la liste des établissements après succès.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Etablissement.php';
require_once '../../src/repository/EtablissementRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    // Vérifie que tous les champs obligatoires sont présents
    if (!empty($id_etablissement) && !empty($nom_etablissement) && !empty($adresse_etablissement) && !empty($site_web_etablissement)) {
        $etablissement = new Medecin($id_etablissement, $nom_etablissement, $adresse_etablissement, $site_web_etablissement);
        $repo = new EtablissementRepository($bdd);

        // Mise à jour en base de données
        $result = $repo->modifier($etablissement);

        if ($result) {
            // Succès : retour à la liste des établissements
            header('Location: ../vue/ListeEtablissements.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'établissement.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>