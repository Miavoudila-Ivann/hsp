<?php
session_start();

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Inclusion des fichiers
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/EvenementRepository.php';
require_once __DIR__ . '/../repository/EtablissementRepository.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';
require_once __DIR__ . '/../repository/ContratRepository.php';

require_once __DIR__ . '/../modele/Evenement.php';
require_once __DIR__ . '/../modele/Utilisateur.php';
require_once __DIR__ . '/../modele/Candidature.php';
require_once __DIR__ . '/../modele/Contrat.php';
require_once __DIR__ . '/../modele/Etablissement.php';


use modele\Evenement;
use repository\EtablissementRepository;
use repository\UtilisateurRepository;
use repository\CandidatureRepository;
use repository\ContratRepository;
use repository\EvenementRepository;

$database = new Bdd();
$bdd = $database->getBdd();

// Création des repositories
$utilisateurRepo = new UtilisateurRepository($bdd);
$candidatureRepo = new CandidatureRepository($bdd);
$contratRepo = new ContratRepository($bdd);
$etablissementRepo = new EtablissementRepository($bdd);
$evenementRepo = new EvenementRepository($bdd);

// Récupération des données
$utilisateurs = $utilisateurRepo->findAll();
$candidatures = $candidatureRepo->findAll();
$contrats = $contratRepo->findAll();
$etablissement = $etablissementRepo->findAll();
$evenement = $evenementRepo->findAll();

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
                    <a href="../vue/ModifierUtilisateur.php?email=<?= urlencode($u['email'] ?? '') ?>">Modifier</a>
                    <a href="../vue/ListeUtilisateurs.php?delete=<?= $u['id_utilisateur'] ?? '' ?>" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?')">Supprimer</a>
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

<section>
    <h2>Liste Etablissement</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Etablissement</th><th>Nom Etablissement</th><th>Adresse Etablissement</th><th>Site web de Etablissement</th></tr>
        <?php foreach($etablissement as $ct): ?>
            <tr>
                <td><?= $ct['id_etablissement'] ?? '' ?></td>
                <td><?= htmlspecialchars($ct['nom_etablissement'] ?? '') ?></td>
                <td><?= $ct['adresse_etablissement'] ?? '' ?></td>
                <td><?= $ct['site_web_etablissement'] ?? '' ?></td>
                <td>
                    <a href="../vue/modifierEtablissement.php?id=<?= $ct['id_etablissement'] ?? '' ?>">Modifier</a>
                    <a href="../vue/ListeEtablissement.php?delete=<?= $ct['id_etablissement'] ?? '' ?>" onclick="return confirm('Supprimer cette établissement ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<section>
    <h2>Liste Evenement</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Evenement</th><th>Titre</th><th>Description</th><th>Type évenement</th><th>Lieu</th><th>nombre de place</th><th>Date de l'évenement</th></tr>
        <?php foreach($etablissement as $ct): ?>
            <tr>
                <td><?= $ct['id_evenement'] ?? '' ?></td>
                <td><?= htmlspecialchars($ct['titre'] ?? '') ?></td>
                <td><?= $ct['description'] ?? '' ?></td>
                <td><?= $ct['type_evenement'] ?? '' ?></td>
                <td><?= $ct['lieu'] ?? '' ?></td>
                <td><?= $ct['nb_place'] ?? '' ?></td>
                <td><?= $ct['date_evenement'] ?? '' ?></td>
                <td>
                    <a href="../vue/modifierEvenement.php?id=<?= $ct['id_evenement'] ?? '' ?>">Modifier</a>
                    <a href="../vue/ListeEvenement.php?delete=<?= $ct['id_evenement'] ?? '' ?>" onclick="return confirm('Supprimer cette établissement ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<?php include __DIR__ . '/footer.php'; ?>
