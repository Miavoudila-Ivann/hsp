<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ProduitRepository.php';
require_once __DIR__ . '/../repository/DemandeStockRepository.php';

use repository\ProduitRepository;
use repository\DemandeStockRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repoProduit  = new ProduitRepository($bdd);
$repoDemande  = new DemandeStockRepository($bdd);

$produits = $repoProduit->findAll();

// Récupère les demandes passées de ce médecin
$mes_demandes = $repoDemande->findByMedecin((int)$_SESSION['id_utilisateur']);

$title = "Demander des produits";
include __DIR__ . '/header.php';
?>

<style>
    .btn-retour { display: inline-block; margin-bottom: 15px; padding: 8px 14px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; }
    .btn-retour:hover { opacity: 0.85; }
    label { font-weight: bold; font-size: 14px; display: block; margin-top: 8px; margin-bottom: 2px; }
    h3 { color: #555; margin-top: 35px; border-bottom: 2px solid #eee; padding-bottom: 5px; }
    .badge-attente  { background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .badge-acceptee { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .badge-refusee  { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
</style>

<h2>Demander des produits</h2>

<form action="../traitement/AjoutDemandeStockTrt.php" method="POST">
    <input type="hidden" name="ref_medecin"   value="<?= htmlspecialchars($_SESSION['id_utilisateur']) ?>">
    <input type="hidden" name="date_demande"  value="<?= date('Y-m-d') ?>">

    <label for="ref_produit">Produit</label>
    <select id="ref_produit" name="ref_produit" required>
        <option value="">-- Sélectionner un produit --</option>
        <?php foreach ($produits as $p): ?>
            <option value="<?= htmlspecialchars($p['id_produit']) ?>">
                <?= htmlspecialchars($p['libelle']) ?>
                (stock actuel : <?= htmlspecialchars($p['stock_actuel']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label for="quantite">Quantité demandée</label>
    <input type="number" id="quantite" name="quantite" min="1" placeholder="Ex : 10" required>

    <button type="submit">Envoyer la demande</button>
</form>

<h3>Mes demandes passées</h3>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Date</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mes_demandes as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['id_demande']) ?></td>
                <td><?= htmlspecialchars($d['libelle_produit'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($d['quantite']) ?></td>
                <td><?= htmlspecialchars($d['date_demande']) ?></td>
                <td>
                    <?php if ($d['statut'] === 'en_attente'): ?>
                        <span class="badge-attente">En attente</span>
                    <?php elseif ($d['statut'] === 'acceptee'): ?>
                        <span class="badge-acceptee">Acceptée</span>
                    <?php else: ?>
                        <span class="badge-refusee">Refusée</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($mes_demandes)): ?>
            <tr><td colspan="5" style="text-align:center;">Aucune demande passée.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
