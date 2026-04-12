<?php
/**
 * Page d'affichage de la liste des patients.
 * Rôles autorisés : secrétaire, admin.
 * Permet la suppression d'un patient via paramètre GET et l'accès au formulaire de création.
 */
session_start();
// Accès réservé aux rôles secrétaire et admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'secretaire' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/PatientRepository.php';

use repository\PatientRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new PatientRepository($bdd);

// Suppression d'un patient si demandée via l'URL
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $repo->supprimer($id);
    header('Location: ListePatients.php');
    exit();
}

$patients = $repo->findAll();
$title = "Liste des patients";
include __DIR__ . '/header.php';
?>

<style>
    .btn { display: inline-block; padding: 6px 12px; border-radius: 5px; font-weight: bold; text-decoration: none; font-size: 13px; }
    .btn-ajout  { background: #28a745; color: white; margin-bottom: 15px; display: inline-block; }
    .btn-modif  { background: #ffc107; color: #333; }
    .btn-suppr  { background: #dc3545; color: white; }
    .btn:hover  { opacity: 0.85; }
</style>

<h2>Liste des patients</h2>

<a class="btn btn-ajout" href="CreePatient.php">+ Ajouter un patient</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>N° Sécurité Sociale</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($patients as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id_patient']) ?></td>
                <td><?= htmlspecialchars($p['nom']) ?></td>
                <td><?= htmlspecialchars($p['prenom']) ?></td>
                <td><?= htmlspecialchars($p['num_secu']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td><?= htmlspecialchars($p['telephone']) ?></td>
                <td class="actions">
                    <a class="btn btn-modif" href="ModifierPatient.php?id=<?= $p['id_patient'] ?>">Modifier</a>
                    <a class="btn btn-suppr" href="ListePatients.php?delete=<?= $p['id_patient'] ?>"
                       onclick="return confirm('Confirmer la suppression de ce patient ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($patients)): ?>
            <tr><td colspan="7" style="text-align:center;">Aucun patient enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
