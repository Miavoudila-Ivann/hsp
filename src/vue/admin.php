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
require_once __DIR__ . '/../repository/HopitalRepository.php';

require_once __DIR__ . '/../modele/Evenement.php';
require_once __DIR__ . '/../modele/Utilisateur.php';
require_once __DIR__ . '/../modele/Candidature.php';
require_once __DIR__ . '/../modele/Contrat.php';
require_once __DIR__ . '/../modele/Etablissement.php';
require_once __DIR__ . '/../modele/Hopital.php';

use repository\EtablissementRepository;
use repository\HopitalRepository;
use repository\UtilisateurRepository;
use repository\CandidatureRepository;
use repository\ContratRepository;
use repository\EvenementRepository;

$database = new \Bdd();
$bdd = $database->getBdd();

// Création des repositories
$utilisateurRepo = new UtilisateurRepository($bdd);
$candidatureRepo = new CandidatureRepository($bdd);
$contratRepo = new ContratRepository($bdd);
$etablissementRepo = new EtablissementRepository($bdd);
$evenementRepo = new EvenementRepository($bdd);
$hopitalRepo = new HopitalRepository($bdd);

// === GESTION ACTIONS ADMIN ===

// Suppression candidature
if (isset($_GET['delete_candidature'])) {
    $candidatureRepo->supprimer((int)$_GET['delete_candidature']);
    header("Location: admin.php");
    exit();
}

// Modification du statut d'une candidature
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_statut'])) {
    $candidatureRepo->modifierStatut((int)$_POST['id_candidature'], $_POST['statut']);
    header("Location: admin.php");
    exit();
}

// === GESTION DES UTILISATEURS EN ATTENTE ===

// Approuver un utilisateur
if (isset($_POST['accepter']) && isset($_POST['id_utilisateur'])) {
    $id = (int) $_POST['id_utilisateur'];
    $stmt = $bdd->prepare("UPDATE utilisateur SET status = 'accepter' WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
}

// Refuser un utilisateur
if (isset($_POST['refuser']) && isset($_POST['id_utilisateur'])) {
    $id = (int) $_POST['id_utilisateur'];
    $stmt = $bdd->prepare("UPDATE utilisateur SET status = 'refuser' WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
}

// Récupération des utilisateurs en attente
$stmt = $bdd->query("SELECT * FROM utilisateur WHERE status = 'attente'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de toutes les données
$utilisateurs = $utilisateurRepo->findAll();
$candidatures = $candidatureRepo->findAll();
$contrats = $contratRepo->findAll();
$etablissements = $etablissementRepo->findAll();
$evenements = $evenementRepo->findAll();
$hopitaux = $hopitalRepo->findAll();

include __DIR__ . '/header.php';
?>

<h1>Dashboard Admin</h1>

<!-- Bouton retour index -->
<p>
    <a href="../../index.php" style="display: inline-block; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;">
        ← Retour à l'index
    </a>
</p>

<!-- ===================== UTILISATEURS ===================== -->
<section>
    <h2>Utilisateurs</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Status</th><th>Actions</th>
        </tr>
        <?php foreach($utilisateurs as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id_utilisateur'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['nom'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['role'] ?? '') ?></td>
                <td><?= htmlspecialchars($u['status'] ?? '') ?></td>
                <td>
                    <?php if ($u['status'] === 'attente'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($u['id_utilisateur']) ?>">
                            <button type="submit" name="accepter">✅ Approuver</button>
                            <button type="submit" name="refuser" style="margin-left:5px;">❌ Refuser</button>
                        </form>
                    <?php endif; ?>
                    <a href="../vue/ModifierUtilisateur.php?email=<?= urlencode($u['email'] ?? '') ?>"> Modifier</a>    |
                    <a href="../vue/ListeUtilisateurs.php?delete=<?= htmlspecialchars($u['id_utilisateur'] ?? '') ?>" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?')"> Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- ===================== CANDIDATURES ===================== -->
<section>
    <h2>Candidatures</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Utilisateur</th><th>Motivation</th><th>CV</th><th>Statut</th><th>Date</th><th>Action</th></tr>
        <?php foreach($candidatures as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['id_candidature'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['ref_utilisateur'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['motivation'] ?? '') ?></td>
                <td>
                    <?php if (!empty($c['cv_path'])): ?>
                        <a href="../../<?= htmlspecialchars($c['cv_path']) ?>" target="_blank">📄 Voir CV</a>
                    <?php else: ?>
                        <em>Aucun</em>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_candidature" value="<?= htmlspecialchars($c['id_candidature']) ?>">
                        <label>
                            <select name="statut">
                                <option value="en attente" <?= ($c['statut'] ?? '') === 'en attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="acceptée" <?= ($c['statut'] ?? '') === 'acceptée' ? 'selected' : '' ?>>Acceptée</option>
                                <option value="refusée" <?= ($c['statut'] ?? '') === 'refusée' ? 'selected' : '' ?>>Refusée</option>
                            </select>
                        </label>
                        <button type="submit" name="modifier_statut">✔️</button>
                    </form>
                </td>
                <td><?= htmlspecialchars($c['date_candidature'] ?? '') ?></td>
                <td>
                    <a href="?delete_candidature=<?= htmlspecialchars($c['id_candidature']) ?>" onclick="return confirm('Supprimer cette candidature ?')">🗑️ Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- ===================== CONTRATS ===================== -->
<section>
    <h2>Contrats</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Utilisateur</th><th>Date début</th><th>Date fin</th><th>Actions</th></tr>
        <?php foreach($contrats as $ct): ?>
            <tr>
                <td><?= htmlspecialchars($ct['id_contrat'] ?? '') ?></td>
                <td><?= htmlspecialchars($ct['utilisateur_nom'] ?? '') ?></td>
                <td><?= htmlspecialchars($ct['date_debut'] ?? '') ?></td>
                <td><?= htmlspecialchars($ct['date_fin'] ?? '') ?></td>
                <td>
                    <a href="../vue/ModifierContrat.php?id=<?= htmlspecialchars($ct['id_contrat'] ?? '') ?>">Modifier</a> |
                    <a href="../vue/ListeContrat.php?delete=<?= htmlspecialchars($ct['id_contrat'] ?? '') ?>" onclick="return confirm('Supprimer ce contrat ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- ===================== ETABLISSEMENTS ===================== -->
<section>
    <h2>Établissements</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Nom</th><th>Adresse</th><th>Site Web</th><th>Actions</th></tr>
        <?php foreach($etablissements as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['id_etablissement'] ?? '') ?></td>
                <td><?= htmlspecialchars($e['nom_etablissement'] ?? '') ?></td>
                <td><?= htmlspecialchars($e['adresse_etablissement'] ?? '') ?></td>
                <td><?= htmlspecialchars($e['site_web_etablissement'] ?? '') ?></td>
                <td>
                    <a href="../vue/modifierEtablissement.php?id=<?= htmlspecialchars($e['id_etablissement'] ?? '') ?>">Modifier</a> |
                    <a href="../vue/ListeEtablissement.php?delete=<?= htmlspecialchars($e['id_etablissement'] ?? '') ?>" onclick="return confirm('Supprimer cet établissement ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- ===================== EVENEMENTS ===================== -->
<section>
    <h2>Événements</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Titre</th><th>Description</th><th>Type</th><th>Lieu</th><th>Places</th><th>Date</th><th>Actions</th></tr>
        <?php foreach($evenements as $ev): ?>
            <tr>
                <td><?= htmlspecialchars($ev['id_evenement'] ?? '') ?></td>
                <td><?= htmlspecialchars($ev['titre'] ?? '') ?></td>
                <td><?= htmlspecialchars($ev['description'] ?? '') ?></td>
                <td><?= htmlspecialchars($ev['type_evenement'] ?? '') ?></td>
                <td><?= htmlspecialchars($ev['lieu'] ?? '') ?></td>
                <td><?= htmlspecialchars($ev['nb_place'] ?? '') ?></td>
                <td><?= htmlspecialchars($ev['date_evenement'] ?? '') ?></td>
                <td>
                    <a href="../vue/modifierEvenement.php?id=<?= htmlspecialchars($ev['id_evenement'] ?? '') ?>">Modifier</a> |
                    <a href="../vue/ListeEvenement.php?delete=<?= htmlspecialchars($ev['id_evenement'] ?? '') ?>" onclick="return confirm('Supprimer cet événement ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<!-- ===================== HOPITAUX ===================== -->
<section>
    <h2>Hôpitaux</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Nom</th><th>Adresse</th><th>Ville</th><th>Actions</th></tr>
        <?php foreach($hopitaux as $h): ?>
            <tr>
                <td><?= htmlspecialchars($h['id_hopital'] ?? '') ?></td>
                <td><?= htmlspecialchars($h['nom'] ?? '') ?></td>
                <td><?= htmlspecialchars($h['adresse_hopital'] ?? '') ?></td>
                <td><?= htmlspecialchars($h['ville_hopital'] ?? '') ?></td>
                <td>
                    <a href="../vue/ModifierHopital.php?id=<?= htmlspecialchars($h['id_hopital'] ?? '') ?>">Modifier</a> |
                    <a href="../vue/ListeHopital.php?delete=<?= htmlspecialchars($h['id_hopital'] ?? '') ?>" onclick="return confirm('Supprimer cet hôpital ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php include __DIR__ . '/footer.php'; ?>
