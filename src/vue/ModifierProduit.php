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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ListeProduits.php');
    exit();
}

$produit = $repo->findById((int)$_GET['id']);
if (!$produit) {
    header('Location: ListeProduits.php');
    exit();
}

$title = "Modifier un produit";
include __DIR__ . '/header.php';
?>

<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display:block; font-weight:bold; margin-bottom:5px; color:#555; }
    .btn-submit { background:#17a2b8; color:white; padding:10px 20px; border:none; border-radius:5px; font-size:15px; font-weight:bold; cursor:pointer; width:100%; }
    .btn-submit:hover { background:#138496; }
    .btn-retour { color:#17a2b8; text-decoration:none; display:inline-block; margin-top:10px; }
</style>

<h2>Modifier un produit</h2>

<form method="post" action="../traitement/ModifierProduitTrt.php">
    <input type="hidden" name="id_produit" value="<?= (int)$produit->getIdProduit() ?>">

    <div class="form-group">
        <label for="libelle">Libellé *</label>
        <input type="text" id="libelle" name="libelle" required
               value="<?= htmlspecialchars($produit->getLibelle()) ?>">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"
                  style="width:100%;padding:10px;border:1px solid #ccc;border-radius:5px;resize:vertical;"><?= htmlspecialchars($produit->getDescription() ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label for="dangerosite">Dangerosité *</label>
        <select id="dangerosite" name="dangerosite" required>
            <option value="">-- Choisir --</option>
            <?php
            $labels = ['Très faible', 'Faible', 'Modérée', 'Élevée', 'Très élevée'];
            for ($i = 1; $i <= 5; $i++):
            ?>
                <option value="<?= $i ?>" <?= ((int)$produit->getDangerosite() === $i) ? 'selected' : '' ?>>
                    <?= $i ?> - <?= $labels[$i - 1] ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="stock_actuel">Stock actuel *</label>
        <input type="number" id="stock_actuel" name="stock_actuel" required min="0"
               value="<?= (int)$produit->getStockActuel() ?>">
    </div>
    <button type="submit" class="btn-submit">Enregistrer les modifications</button>
</form>

<a href="ListeProduits.php" class="btn-retour">← Retour à la liste</a>

<?php include __DIR__ . '/footer.php'; ?>
