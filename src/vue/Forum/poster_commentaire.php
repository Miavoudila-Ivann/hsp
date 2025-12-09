<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../bdd/Bdd.php';
session_start();

$pdo = getBdd();

// Récupération ID utilisateur depuis la session
// Adaptez selon votre système : user_id ou id_utilisateur
$auteur_id = $_SESSION['id_utilisateur'] ?? null;

if ($auteur_id === null) {
    die("Vous devez être connecté pour poster un commentaire.");
}

if (!isset($_POST['ressource_id'], $_POST['contenu'])) {
    die("Formulaire invalide.");
}

$ressource_id = (int)$_POST['ressource_id'];
$contenu = trim($_POST['contenu']);
$parent_id = isset($_POST['parent_id']) && $_POST['parent_id'] !== ''
    ? (int)$_POST['parent_id']
    : null;

if ($contenu === '') {
    die("Commentaire vide.");
}

$sql = "INSERT INTO commentaires (ressource_id, auteur_id, contenu, parent_id, date_commentaire)
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ressource_id, $auteur_id, $contenu, $parent_id]);

header("Location: afficher_ressource.php?id=" . $ressource_id);
exit;