<?php
session_start();

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['gestionnaire_stock', 'admin'])) {
    header('Location: Connexion.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/DemandeStockRepository.php';

use repository\DemandeStockRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new DemandeStockRepository($bdd);

$filtreStatut = $_GET['statut'] ?? '';
$statutsValides = ['en_attente', 'acceptee', 'refusee'];

if ($filtreStatut !== '' && in_array($filtreStatut, $statutsValides)) {
    $demandes = $repo->findByStatut($filtreStatut);
} else {
    $demandes = $repo->findAll();
    $filtreStatut = '';
}

$title = "Demandes de stock";
include __DIR__ . '/header.php';
?>

<style>
    .badge {
        display:inline-block;
        padding:3px 10px;
        border-radius:12px;
        font-size:12px;
        font-weight:bold;
        color:white;
    }
    .badge-en_attente { background:#ffc107; color:#333; }
    .badge-acceptee   { background:#28a745; }
    .badge-refusee    { background:#dc3545; }
    .btn-accepter { background:#28a745; color:white; padding:4px 10px; border:none; border-radius:4px; cursor:pointer; font-size:13px; }
    .btn-refuser  { background:#dc3545; color:white; padding:4px 10px; border:none; border-radius:4px; cursor:pointer; font-size:13px; }
    .btn-accepter:hover { background:#218838; }
    .btn-refuser:hover  { background:#c82333; }
    .filtre-bar { margin-bottom:15px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filtre-bar a { padding:6px 14px; border-radius:4px; text-decoration:none; font-size:13px; background:#e9ecef; color:#333; }
    .filtre-bar a.actif { background:#343a40; color:white; }
    .filtre-bar a:hover { background:#adb5bd; }
</style>

<h2>Demandes de stock</h2>

<div class="filtre-bar">
    <strong>Filtrer :</strong>
    <a href="ListeDemandesStock.php" class="<?= $filtreStatut === '' ? 'actif' : '' ?>">Toutes</a>
    <a href="ListeDemandesStock.php?statut=en_attente" class="<?= $filtreStatut === 'en_attente' ? 'actif' : '' ?>">En attente</a>
    <a href="ListeDemandesStock.php?statut=acceptee" class="<?= $filtreStatut === 'acceptee' ? 'actif' : '' ?>">Acceptées</a>
    <a href="ListeDemandesStock.php?statut=refusee" class="<?= $filtreStatut === 'refusee' ? 'actif' : '' ?>">Refusées</a>
</div>

<?php if (empty($demandes)): ?>
    <p style="text-align:center;color:#666;">Aucune demande trouvée.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Médecin</th>
                <th>Quantité</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['id_demande']) ?></td>
                    <td><?= htmlspecialchars($d['libelle_produit'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($d['nom_medecin'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($d['quantite']) ?></td>
                    <td><?= htmlspecialchars($d['date_demande'] ?? '') ?></td>
                    <td>
                        <span class="badge badge-<?= htmlspecialchars($d['statut']) ?>">
                            <?= htmlspecialchars($d['statut']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($d['statut'] === 'en_attente'): ?>
                            <form method="post" action="../traitement/TraiterDemandeStockTrt.php"
                                  style="display:inline;">
                                <input type="hidden" name="id_demande" value="<?= (int)$d['id_demande'] ?>">
                                <input type="hidden" name="statut" value="acceptee">
                                <button type="submit" class="btn-accepter"
                                        onclick="return confirm('Accepter cette demande ?');">Accepter</button>
                            </form>
                            <form method="post" action="../traitement/TraiterDemandeStockTrt.php"
                                  style="display:inline;margin-left:5px;">
                                <input type="hidden" name="id_demande" value="<?= (int)$d['id_demande'] ?>">
                                <input type="hidden" name="statut" value="refusee">
                                <button type="submit" class="btn-refuser"
                                        onclick="return confirm('Refuser cette demande ?');">Refuser</button>
                            </form>
                        <?php else: ?>
                            <span style="color:#888;font-size:13px;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
