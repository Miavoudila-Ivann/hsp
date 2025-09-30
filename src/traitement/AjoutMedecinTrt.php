<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Medecin.php';
require_once '../../src/repository/MedecinRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_medecin) && !empty($ref_specialite) && !empty($ref_hopital) && !empty($ref_etablissement)) {
        $medecin = new Medecin($id_medecin, $ref_specialite, $ref_hopital, $ref_etablissement);
        $repo = new MedecinRepository($bdd);

        $result = $repo->ajouter($medecin);

        if ($result) {
            header('Location: ../../vue/ListeMedecin.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
