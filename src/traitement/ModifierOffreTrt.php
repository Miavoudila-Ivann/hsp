<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/repository/OffreRepository.php';
use repository\OffreRepository;
use modele\Offre;
$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (
        !empty($id_offre) && !empty($titre) && !empty($description) && !empty($mission) &&
        !empty($salaire) && !empty($type_offre) && !empty($etat) &&
        !empty($ref_utilisateur) && !empty($ref_entreprise) && !empty($date_publication)
    ) {
        $offre = new Offre([
            'id_offre' => $id_offre,
            'titre' => $titre,
            'description' => $description,
            'mission' => $mission,
            'salaire' => $salaire,
            'type_offre' => $type_offre,
            'etat' => $etat,
            'ref_utilisateur' => $ref_utilisateur,
            'ref_entreprise' => $ref_entreprise,
            'date_publication' => $date_publication
        ]);


        $repo = new OffreRepository($bdd);
        $result = $repo->modifier($offre);

        if ($result) {
            header('Location: ../vue/ListeOffre.php');
            exit();
        } else {
            echo "Erreur lors de la modification.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>
