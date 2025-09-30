<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Specialite.php';
require_once '../../src/repository/SpecialiteRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($idSpecialite) && !empty($libelle)) {
        $specialite = new Specialite($idSpecialite, $libelle);
        $repo = new SpecialiteRepository($bdd);

        $result = $repo->ajouter($specialite);

        if ($result) {
            header('Location: ../../vue/ListeSpecialite.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
