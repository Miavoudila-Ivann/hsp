<?php
/**
 * Tableau de bord administrateur.
 * Rôle autorisé : admin uniquement.
 * Centralise la gestion des utilisateurs, entreprises, candidatures,
 * contrats, établissements, événements et hôpitaux.
 * Gère également la validation/refus des comptes avec envoi d'email (PHPMailer).
 */
session_start();

// Redirection si l'utilisateur n'est pas admin
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
require_once __DIR__ . '/../repository/EntrepriseRepository.php';

require_once __DIR__ . '/../modele/Evenement.php';
require_once __DIR__ . '/../modele/Utilisateur.php';
require_once __DIR__ . '/../modele/Candidature.php';
require_once __DIR__ . '/../modele/Contrat.php';
require_once __DIR__ . '/../modele/Etablissement.php';
require_once __DIR__ . '/../modele/Hopital.php';
require_once __DIR__ . '/../modele/Entreprise.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envoie un email de refus à un utilisateur ou une entreprise via SMTP Gmail.
 * @param string $email       Destinataire
 * @param string $nom         Nom (ou raison sociale pour une entreprise)
 * @param string $prenom      Prénom (vide pour une entreprise)
 * @param string $typeCompte  'utilisateur' ou 'entreprise'
 */
function envoyerEmailRefus($email, $nom, $prenom, $typeCompte = 'utilisateur') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'eidbraim@gmail.com'; // Ton email
        $mail->Password = 'ycak zbav ifrt muei'; // Ton mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('eidbraim@gmail.com', 'Hôpital Sud Paris');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Votre demande d\'inscription - Hôpital Sud Paris';

        if ($typeCompte === 'entreprise') {
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #dc3545;'>Demande d'inscription refusée</h2>
                    <p>Bonjour,</p>
                    <p>Nous avons le regret de vous informer que votre demande d'inscription pour <strong>$nom</strong> sur notre plateforme a été refusée par notre équipe administrative.</p>
                    <p><strong>Raisons possibles :</strong></p>
                    <ul>
                        <li>Informations incomplètes ou incorrectes</li>
                        <li>Entreprise non conforme aux critères d'acceptation</li>
                        <li>Documentation manquante</li>
                    </ul>
                    <p>Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez obtenir plus d'informations, n'hésitez pas à nous contacter.</p>
                    <p>Cordialement,<br>L'équipe Hôpital Sud Paris</p>
                    <hr>
                    <p style='font-size: 12px; color: #666;'>
                        Hôpital Sud Paris<br>
                        Email: contact@hopitalsudparis.fr<br>
                        Téléphone: +33 1 45 67 89 00
                    </p>
                </div>
            ";
        } else {
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #dc3545;'>Demande d'inscription refusée</h2>
                    <p>Bonjour $prenom $nom,</p>
                    <p>Nous avons le regret de vous informer que votre demande d'inscription sur notre plateforme a été refusée par notre équipe administrative.</p>
                    <p><strong>Raisons possibles :</strong></p>
                    <ul>
                        <li>Informations personnelles incomplètes ou incorrectes</li>
                        <li>Compte ne correspondant pas aux critères d'acceptation</li>
                        <li>Documents justificatifs manquants</li>
                    </ul>
                    <p>Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez obtenir plus d'informations, n'hésitez pas à nous contacter.</p>
                    <p>Cordialement,<br>L'équipe Hôpital Sud Paris</p>
                    <hr>
                    <p style='font-size: 12px; color: #666;'>
                        Hôpital Sud Paris<br>
                        Email: contact@hopitalsudparis.fr<br>
                        Téléphone: +33 1 45 67 89 00
                    </p>
                </div>
            ";
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email refus: {$mail->ErrorInfo}");
        return false;
    }
}
/**
 * Envoie un email d'approbation à un utilisateur ou une entreprise via SMTP Gmail.
 */
function envoyerEmailApprobation($email, $nom, $prenom, $typeCompte = 'utilisateur') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'eidbraim@gmail.com';
        $mail->Password = 'ycak zbav ifrt muei';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('eidbraim@gmail.com', 'Hôpital Sud Paris');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = '✅ Votre compte a été approuvé - Hôpital Sud Paris';

        if ($typeCompte === 'entreprise') {
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #28a745;'>✅ Compte approuvé !</h2>
                    <p>Bonjour,</p>
                    <p>Bonne nouvelle ! Votre demande d'inscription pour <strong>$nom</strong> a été approuvée.</p>
                    <p>Vous pouvez maintenant vous connecter à votre espace entreprise :</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/hsp/src/vue/Connexion.php' 
                           style='background: #007BFF; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Se connecter
                        </a>
                    </p>
                    <p>Cordialement,<br>L'équipe Hôpital Sud Paris</p>
                </div>
            ";
        } else {
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #28a745;'>✅ Compte approuvé !</h2>
                    <p>Bonjour $prenom $nom,</p>
                    <p>Bonne nouvelle ! Votre demande d'inscription a été approuvée.</p>
                    <p>Vous pouvez maintenant vous connecter à votre espace :</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='http://localhost/hsp/src/vue/Connexion.php' 
                           style='background: #007BFF; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                            Se connecter
                        </a>
                    </p>
                    <p>Cordialement,<br>L'équipe Hôpital Sud Paris</p>
                </div>
            ";
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur envoi email approbation: {$mail->ErrorInfo}");
        return false;
    }
}

// Refus d'un utilisateur avec notification par email
if (isset($_POST['refuser']) && isset($_POST['id_utilisateur'])) {
    $id = (int) $_POST['id_utilisateur'];

    $stmt = $bdd->prepare("SELECT nom, prenom, email FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $bdd->prepare("UPDATE utilisateur SET status = 'refuser' WHERE id_utilisateur = ?");
    $stmt->execute([$id]);

    if ($user) {
        envoyerEmailRefus($user['email'], $user['nom'], $user['prenom'], 'utilisateur');
    }

    header("Location: admin.php?refus_success=1");
    exit();
}

// Refus d'une entreprise avec notification par email
if (isset($_POST['refuser_entreprise']) && isset($_POST['id_entreprise'])) {
    $id = (int) $_POST['id_entreprise'];

    // Récupérer les infos de l'entreprise avant de refuser
    $stmt = $bdd->prepare("SELECT nom_entreprise, email FROM entreprise WHERE id_entreprise = ?");
    $stmt->execute([$id]);
    $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mettre à jour le statut
    $stmt = $bdd->prepare("UPDATE entreprise SET status = 'refuser' WHERE id_entreprise = ?");
    $stmt->execute([$id]);

    // Envoyer l'email de notification
    if ($entreprise) {
        envoyerEmailRefus($entreprise['email'], $entreprise['nom_entreprise'], '', 'entreprise');
    }

    header("Location: admin.php?refus_success=1");
    exit();
}

use repository\EtablissementRepository;
use repository\HopitalRepository;
use repository\UtilisateurRepository;
use repository\CandidatureRepository;
use repository\ContratRepository;
use repository\EvenementRepository;
use repository\EntrepriseRepository;

$database = new \Bdd();
$bdd = $database->getBdd();

// Création des repositories
$utilisateurRepo = new UtilisateurRepository($bdd);
$candidatureRepo = new CandidatureRepository($bdd);
$contratRepo = new ContratRepository($bdd);
$etablissementRepo = new EtablissementRepository($bdd);
$evenementRepo = new EvenementRepository($bdd);
$hopitalRepo = new HopitalRepository($bdd);
$entrepriseRepo = new EntrepriseRepository($bdd);

// Instanciation de tous les repositories nécessaires au dashboard
// === ACTIONS ADMIN ===

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

// === VALIDATION / REFUS DES UTILISATEURS EN ATTENTE ===

// Approbation d'un utilisateur avec email de notification
if (isset($_POST['accepter']) && isset($_POST['id_utilisateur'])) {
    $id = (int) $_POST['id_utilisateur'];

    // Récupérer les infos avant d'approuver
    $stmt = $bdd->prepare("SELECT nom, prenom, email FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mettre à jour le statut
    $stmt = $bdd->prepare("UPDATE utilisateur SET status = 'accepter' WHERE id_utilisateur = ?");
    $stmt->execute([$id]);

    // Envoyer l'email
    if ($user) {
        envoyerEmailApprobation($user['email'], $user['nom'], $user['prenom'], 'utilisateur');
    }

    header("Location: admin.php?approbation_success=1");
    exit();
}

// Refuser un utilisateur
if (isset($_POST['refuser']) && isset($_POST['id_utilisateur'])) {
    $id = (int) $_POST['id_utilisateur'];
    $stmt = $bdd->prepare("UPDATE utilisateur SET status = 'refuser' WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit();
}

// === VALIDATION / REFUS DES ENTREPRISES EN ATTENTE ===

// Approbation d'une entreprise
if (isset($_POST['accepter_entreprise']) && isset($_POST['id_entreprise'])) {
    $id = (int) $_POST['id_entreprise'];
    $stmt = $bdd->prepare("UPDATE entreprise SET status = 'accepter' WHERE id_entreprise = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit();
}

// Refuser une entreprise
if (isset($_POST['refuser_entreprise']) && isset($_POST['id_entreprise'])) {
    $id = (int) $_POST['id_entreprise'];
    $stmt = $bdd->prepare("UPDATE entreprise SET status = 'refuser' WHERE id_entreprise = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit();
}

// Chargement des comptes en attente de validation
$stmt = $bdd->query("SELECT * FROM utilisateur WHERE status = 'Attente'");
$usersEnAttente = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtEntreprises = $bdd->query("SELECT * FROM entreprise WHERE status = 'Attente'");
$entreprisesEnAttente = $stmtEntreprises->fetchAll(PDO::FETCH_ASSOC);

// Chargement de toutes les données pour l'affichage du dashboard
$utilisateurs = $utilisateurRepo->findAll();
$candidatures = $candidatureRepo->findAll();
$contrats = $contratRepo->findAll();
$etablissements = $etablissementRepo->findAll();
$evenements = $evenementRepo->getAllEvenements();
$hopitaux = $hopitalRepo->findAll();
$entreprises = $entrepriseRepo->findAll();

include __DIR__ . '/header.php';
?>

    <h1>Dashboard Admin</h1>

<?php if (isset($_GET['refus_success'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px; border: 1px solid #c3e6cb;">
        ✅ Le compte a été refusé et l'utilisateur a reçu un email de notification.
    </div>
<?php endif; ?>

    <!-- Bouton retour index -->
    <p>
        <a href="../../index.php" style="display: inline-block; padding: 8px 12px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;">
            ← Retour à l'index
        </a>
    </p>

    <!-- ===================== UTILISATEURS EN ATTENTE ===================== -->
<?php if (!empty($usersEnAttente)): ?>
    <section style="background: #fff3cd; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 2px solid #ffc107;">
        <h2 style="color: #856404;">⏳ Utilisateurs en attente de validation</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Ville</th><th>Actions</th>
            </tr>
            <?php foreach($usersEnAttente as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id_utilisateur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['nom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['prenom'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($u['ville'] ?? '') ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($u['id_utilisateur']) ?>">
                            <button type="submit" name="accepter" style="background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;">✅ Approuver</button>
                            <button type="submit" name="refuser" style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; margin-left: 5px;">❌ Refuser</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
<?php endif; ?>

    <!-- ===================== ENTREPRISES EN ATTENTE ===================== -->
<?php if (!empty($entreprisesEnAttente)): ?>
    <section style="background: #d1ecf1; padding: 15px; margin-bottom: 20px; border-radius: 5px; border: 2px solid #17a2b8;">
        <h2 style="color: #0c5460;">🏢 Entreprises en attente de validation</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th><th>Nom</th><th>Email</th><th>Ville</th><th>Site Web</th><th>Actions</th>
            </tr>
            <?php foreach($entreprisesEnAttente as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['id_entreprise'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['nom_entreprise'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['ville_entreprise'] ?? '') ?></td>
                    <td><a href="<?= htmlspecialchars($e['site_web'] ?? '') ?>" target="_blank">🔗 Voir</a></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id_entreprise" value="<?= htmlspecialchars($e['id_entreprise']) ?>">
                            <button type="submit" name="accepter_entreprise" style="background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;">✅ Approuver</button>
                            <button type="submit" name="refuser_entreprise" style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; margin-left: 5px;">❌ Refuser</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
<?php endif; ?>

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
                        <a href="../vue/ModifierUtilisateur.php?email=<?= urlencode($u['email'] ?? '') ?>">Modifier</a> |
                        <a href="../vue/ListeUtilisateurs.php?delete=<?= htmlspecialchars($u['id_utilisateur'] ?? '') ?>" onclick="return confirm('Voulez-vous supprimer cet utilisateur ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- ===================== ENTREPRISES ===================== -->
    <section>
        <h2>Entreprises</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th><th>Nom</th><th>Email</th><th>Ville</th><th>Status</th><th>Actions</th>
            </tr>
            <?php foreach($entreprises as $ent): ?>
                <tr>
                    <td><?= htmlspecialchars($ent->getId()) ?></td>
                    <td><?= htmlspecialchars($ent->getNom()) ?></td>
                    <td><?= htmlspecialchars($ent->getEmail() ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($ent->getVille()) ?></td>
                    <td><?= htmlspecialchars($ent->getStatus()) ?></td>
                    <td>
                        <a href="../vue/ModifierEntreprise.php?id=<?= htmlspecialchars($ent->getId()) ?>">Modifier</a> |
                        <a href="../traitement/SupprimerEntrepriseTrt.php?id=<?= htmlspecialchars($ent->getId()) ?>" onclick="return confirm('Voulez-vous supprimer cette entreprise ?')">Supprimer</a>
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
                    <td><?= htmlspecialchars($ev->getIdEvenement() ?? '') ?></td>
                    <td><?= htmlspecialchars($ev->getTitre() ?? '') ?></td>
                    <td><?= htmlspecialchars($ev->getDescription() ?? '') ?></td>
                    <td><?= htmlspecialchars($ev->getTypeEvenement() ?? '') ?></td>
                    <td><?= htmlspecialchars($ev->getLieu() ?? '') ?></td>
                    <td><?= htmlspecialchars($ev->getNbPlace() ?? '') ?></td>
                    <td><?= htmlspecialchars($ev->getDateEvenement() ?? '') ?></td>
                    <td>
                        <a href="../vue/modifierEvenement.php?id=<?= htmlspecialchars($ev->getIdEvenement() ?? '') ?>">Modifier</a> |
                        <a href="../vue/ListeEvenement.php?delete=<?= htmlspecialchars($ev->getIdEvenement() ?? '') ?>" onclick="return confirm('Supprimer cet événement ?')">Supprimer</a>
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

    <style>
        section {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            margin-top: 10px;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: left;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>

<?php include __DIR__ . '/footer.php'; ?>