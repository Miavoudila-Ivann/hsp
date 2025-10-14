<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/HopitalRepository.php';

use repository\HopitalRepository;

if (!isset($_GET['id'])) {
    die('ID manquant.');
}

$id = (int)$_GET['id'];
$pdo = (new \Bdd())->getBdd();
$repo = new HopitalRepository($pdo);

if ($repo->supprimerHopital($id)) {
    header('Location: ListeHopital.php');
    exit;
} else {
    echo "Erreur lors de la suppression.";
}
