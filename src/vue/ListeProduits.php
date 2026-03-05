<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: Connexion.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ProduitRepository.php';

use repository\ProduitRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ProduitRepository($bdd);

// Suppression via GET
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $repo->supprimer((int)$_GET['delete']);
    header('Location: ListeProduits.php');
    exit();
}

$produits = $repo->findAll();
$title = "Catalogue des produits";
include __DIR__ . '/header.php';
?>

<style>
    .btn-ajout { background:#28a745; color:white; padding:7px 14px; border-radius:4px; text-decoration:none; font-size:14px; }
    .btn-modif { background:#17a2b8; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:13px; }
    .btn-suppr { background:#dc3545; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:13px; }
    .btn-ajout:hover { background:#218838; }
    .btn-modif:hover { background:#138496; }
    .btn-suppr:hover { background:#c82333; }
    .dangerosite { font-weight:bold; }
    .dang-1 { color:#28a745; }
    .dang-2 { color:#8bc34a; }
    .dang-3 { color:#ffc107; }
    .dang-4 { color:#ff7043; }
    .dang-5 { color:#dc3545; }
</style>

<h2>Catalogue des produits</h2>

<div style="margin-bottom:15px;">
    <a href="CreeProduit.php" class="btn-ajout">+ Ajouter un produit</a>
</div>

<?php if (empty($produits)): ?>
    <p style="text-align:center;color:#666;">Aucun produit enregistré.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Description</th>
                <th>Dangerosité</th>
                <th>Stock actuel</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id_produit']) ?></td>
                    <td><?= htmlspecialchars($p['libelle']) ?></td>
                    <td><?= htmlspecialchars($p['description'] ?? '') ?></td>
                    <td>
                        <span class="dangerosite dang-<?= (int)$p['dangerosite'] ?>">
                            <?= (int)$p['dangerosite'] ?>/5
                        </span>
                    </td>
                    <td><?= htmlspecialchars($p['stock_actuel']) ?></td>
                    <td>
                        <a href="ModifierProduit.php?id=<?= (int)$p['id_produit'] ?>" class="btn-modif">Modifier</a>
                        <a href="ListeProduits.php?delete=<?= (int)$p['id_produit'] ?>"
                           class="btn-suppr"
                           onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
