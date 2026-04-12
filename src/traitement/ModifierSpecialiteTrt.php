<?php
/**
 * Traitement de modification d'une spécialité médicale.
 * Valide l'identifiant et le libellé de la spécialité,
 * construit l'objet Specialite et délègue la mise à jour à SpecialiteRepository.
 * Toute requête non-POST est redirigée vers la liste des spécialités.
 */
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Specialite.php';
require_once '../../src/repository/SpecialiteRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ok'])) {
    // Récupération et nettoyage des champs du formulaire
    $id      = $_POST['id_specialite'] ?? null;
    $libelle = trim($_POST['libelle'] ?? '');

    // Vérifie que l'ID et le libellé sont présents
    if ($id && !empty($libelle)) {
        $db = new Bdd();
        $repo = new SpecialiteRepository($db->getBdd());
        $specialite = new Specialite($libelle, $id);

        // Mise à jour en base de données
        if ($repo->modifier($specialite)) {
            header('Location: ../../vue/ListeSpecialite.php');
            exit();
        } else {
            die("Erreur lors de la modification.");
        }
    } else {
        die("Données manquantes.");
    }
} else {
    // Accès direct sans POST : redirection
    header('Location: ../../vue/ListeSpecialite.php');
    exit();
}
?>