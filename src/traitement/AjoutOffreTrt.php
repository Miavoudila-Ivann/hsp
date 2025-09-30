<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/repository/OffreRepository.php';

$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (
        !empty($id_offre) && !empty($titre) && !empty($description) && !empty($mission) &&
        !empty($salaire) && !empty($type_offre) && !empty($etat) &&
        !empty($ref_utilisateur) && !empty($ref_entreprise) && !empty($date_publication)
    ) {
        $offre = new Offre(
            $id_offre, $titre, $description, $mission, $salaire,
            $type_offre, $etat, $ref_utilisateur, $ref_entreprise, $date_publication
        );

        $repo = new OffreRepository($bdd);
        $result = $repo->ajouter($offre);

        if ($result) {
            header('Location: ../../vue/ListeOffre.php');
            exit();
        } else {
            echo "Erreur lors de l'ajout.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
