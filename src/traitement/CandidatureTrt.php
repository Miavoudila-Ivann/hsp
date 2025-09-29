<?php
require_once __DIR__ . '/../repository/CandidatureRepository.php';

$candidatureRepo = new CandidatureRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidature = new Candidature([
        'id_utilisateur' => $_POST['id_utilisateur'],
        'id_offre'       => $_POST['id_offre'],
        'motivation'     => $_POST['motivation']
    ]);

    $candidatureRepo->creerCandidature($candidature);

    // redirection après candidature
    header('Location: merci_candidature.php');
    exit();
}

//  récupérer les candidatures liées à une offre

if (isset($_GET['id_offre'])) {
    $listeCandidatures = $candidatureRepo->getCandidaturesParOffre($_GET['id_offre']);
}
