<?php
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Inclusion des fichiers
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../repository/ContratRepository.php';
require_once __DIR__ . '/../modele/Utilisateur.php';
require_once __DIR__ . '/../modele/Candidature.php';
require_once __DIR__ . '/../modele/Contrat.php';

use repository\UtilisateurRepository;
use repository\CandidatureRepository;
use repository\ContratRepository;

$database = new Bdd();
$bdd = $database->getBdd();

// Création des repositories
$utilisateurRepo = new UtilisateurRepository($bdd);
$candidatureRepo = new CandidatureRepository($bdd);
$contratRepo = new ContratRepository($bdd);

// Récupération des données
$utilisateurs = $utilisateurRepo->findAll();
$candidatures = $candidatureRepo->findAll();
$contrats = $contratRepo->findAll();

include __DIR__ . '/header.php';
?>

<h1>Dashboard Admin</h1>
<!-- Bouton retour index -->
<p>
    <a href="../../index.php" style="display: inline-block; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;">
        ← Retour à l'index
    </a>
</p>

<section>
    <h2>Utilisateurs</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
        <?php foreach($utilisateurs as $u): ?>
            <tr>
                <td><?= $u['id_utilisateur'] ?? '' ?></td>
                <td><?= htmlspecialchars($u['nom'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['role'] ?? '') ?></td>
                <td>
                    <a href="../utilisateur/ModifierUtilisateur.php?email=<?= urlencode($u['email'] ?? '') ?>">Modifier</a>
                    <a href="../utilisateur/ListeUtilisateurs.php?delete=<?= $u['id_utilisateur'] ?? '' ?>" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<section>
    <h2>Candidatures</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Motivation</th><th>Actions</th></tr>
        <?php foreach($candidatures as $c): ?>
            <tr>
                <td><?= $c['id_candidature'] ?? '' ?></td>
                <td><?= htmlspecialchars($c['nom'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['prenom'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['motivation'] ?? '') ?></td>
                <td>
                    <a href="../contrat/CreerContrat.php?candidature_id=<?= $c['id_candidature'] ?? '' ?>">Créer Contrat</a>
                    <a href="../candidature/ListeCandidatures.php?delete=<?= $c['id_candidature'] ?? '' ?>" onclick="return confirm('Supprimer cette candidature ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<section>
    <h2>Contrats</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Utilisateur</th><th>Date début</th><th>Date fin</th><th>Actions</th></tr>
        <?php foreach($contrats as $ct): ?>
            <tr>
                <td><?= $ct['id_contrat'] ?? '' ?></td>
                <td><?= htmlspecialchars($ct['utilisateur_nom'] ?? '') ?></td>
                <td><?= $ct['date_debut'] ?? '' ?></td>
                <td><?= $ct['date_fin'] ?? '' ?></td>
                <td>
                    <a href="../contrat/ModifierContrat.php?id=<?= $ct['id_contrat'] ?? '' ?>">Modifier</a>
                    <a href="../contrat/ListeContrats.php?delete=<?= $ct['id_contrat'] ?? '' ?>" onclick="return confirm('Supprimer ce contrat ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php include __DIR__ . '/footer.php'; ?>
