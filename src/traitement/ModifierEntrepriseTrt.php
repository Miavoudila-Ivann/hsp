<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Entreprise.php';
require_once '../../src/repository/EntrepriseRepository.php';
use modele\Entreprise;
use repository\EntrepriseRepository;
$database = new Bdd();
$bdd = $database->getBdd();

if (isset($_POST['ok'])) {
    extract($_POST);

    if (!empty($id_entreprise) && !empty($nom_entreprise) && !empty($rue_entreprise) && !empty($ville_entreprise) && !empty($cd_entreprise) && !empty($site_web)) {
        $entreprise = new Entreprise([
            'id_entreprise' => $id_entreprise,
            'nom_entreprise' => $nom_entreprise,
            'rue_entreprise' => $rue_entreprise,
            'ville_entreprise' => $ville_entreprise,
            'cd_entreprise' => $cd_entreprise,
            'site_web' => $site_web
        ]);
        $repo = new EntrepriseRepository($bdd);

        $result = $repo->modifierEntreprise($entreprise);

        if ($result) {
            header('Location: ../../vue/ListeEntreprise.php');
            exit();
        } else {
            echo "Erreur lors de la modification de l'entreprise.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
