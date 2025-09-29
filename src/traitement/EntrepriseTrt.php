<?php
require_once __DIR__ . '/../repository/EntrepriseRepository.php';

$entrepriseRepo = new EntrepriseRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'creer') {
        $entreprise = new Entreprise([
            'nom'      => $_POST['nom'],
            'adresse'  => $_POST['adresse'],
            'site_web' => $_POST['site_web']
        ]);
        $entrepriseRepo->creerEntreprise($entreprise);
    }

    if ($action === 'modifier' && isset($_POST['id'])) {
        $entreprise = new Entreprise([
            'id'       => $_POST['id'],
            'nom'      => $_POST['nom'],
            'adresse'  => $_POST['adresse'],
            'site_web' => $_POST['site_web']
        ]);
        $entrepriseRepo->majEntreprise($entreprise);
    }

    if ($action === 'supprimer' && isset($_POST['id'])) {
        $entrepriseRepo->supprimerEntreprise($_POST['id']);
    }
}

// récupérer toutes les entreprises
$listeEntreprises = $entrepriseRepo->getToutesLesEntreprises();

