<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: Connexion.php');
    exit();
}

$title = "Ajouter un produit";
include __DIR__ . '/header.php';
?>

<style>
    .form-group { margin-bottom: 15px; }
    .form-group label { display:block; font-weight:bold; margin-bottom:5px; color:#555; }
    .btn-submit { background:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; font-size:15px; font-weight:bold; cursor:pointer; width:100%; }
    .btn-submit:hover { background:#218838; }
    .btn-retour { color:#17a2b8; text-decoration:none; display:inline-block; margin-top:10px; }
</style>

<h2>Ajouter un produit</h2>

<form method="post" action="../traitement/AjoutProduitTrt.php">
    <div class="form-group">
        <label for="libelle">Libellé *</label>
        <input type="text" id="libelle" name="libelle" required placeholder="Nom du produit">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"
                  style="width:100%;padding:10px;border:1px solid #ccc;border-radius:5px;resize:vertical;"
                  placeholder="Description du produit"></textarea>
    </div>
    <div class="form-group">
        <label for="dangerosite">Dangerosité *</label>
        <select id="dangerosite" name="dangerosite" required>
            <option value="">-- Choisir --</option>
            <option value="1">1 - Très faible</option>
            <option value="2">2 - Faible</option>
            <option value="3">3 - Modérée</option>
            <option value="4">4 - Élevée</option>
            <option value="5">5 - Très élevée</option>
        </select>
    </div>
    <div class="form-group">
        <label for="stock_actuel">Stock actuel *</label>
        <input type="number" id="stock_actuel" name="stock_actuel" required min="0" placeholder="0">
    </div>
    <button type="submit" class="btn-submit">Enregistrer le produit</button>
</form>

<a href="ListeProduits.php" class="btn-retour">← Retour à la liste</a>

<?php include __DIR__ . '/footer.php'; ?>
