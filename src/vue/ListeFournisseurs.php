<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: Connexion.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/FournisseurRepository.php';

use repository\FournisseurRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new FournisseurRepository($bdd);

// Suppression via GET
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $repo->supprimer((int)$_GET['delete']);
    header('Location: ListeFournisseurs.php');
    exit();
}

$fournisseurs = $repo->findAll();
$title = "Gestion des fournisseurs";
include __DIR__ . '/header.php';
?>

<style>
    .btn-ajout { background:#28a745; color:white; padding:7px 14px; border-radius:4px; text-decoration:none; font-size:14px; }
    .btn-modif { background:#17a2b8; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:13px; }
    .btn-suppr { background:#dc3545; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:13px; }
    .btn-ajout:hover { background:#218838; }
    .btn-modif:hover { background:#138496; }
    .btn-suppr:hover { background:#c82333; }
</style>

<h2>Gestion des fournisseurs</h2>

<div style="margin-bottom:15px;">
    <a href="CreeFournisseur.php" class="btn-ajout">+ Ajouter un fournisseur</a>
</div>

<?php if (empty($fournisseurs)): ?>
    <p style="text-align:center;color:#666;">Aucun fournisseur enregistré.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fournisseurs as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['id_fournisseur']) ?></td>
                    <td><?= htmlspecialchars($f['nom']) ?></td>
                    <td><?= htmlspecialchars($f['contact'] ?? '') ?></td>
                    <td><?= htmlspecialchars($f['email'] ?? '') ?></td>
                    <td>
                        <a href="ModifierFournisseur.php?id=<?= (int)$f['id_fournisseur'] ?>" class="btn-modif">Modifier</a>
                        <a href="ListeFournisseurs.php?delete=<?= (int)$f['id_fournisseur'] ?>"
                           class="btn-suppr"
                           onclick="return confirm('Supprimer ce fournisseur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
