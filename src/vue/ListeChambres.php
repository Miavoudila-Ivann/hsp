<?php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'medecin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ChambreRepository.php';

use repository\ChambreRepository;

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ChambreRepository($bdd);

$chambres = $repo->findAll();
$title = "Gestion des chambres";
include __DIR__ . '/header.php';
?>

<style>
    .btn { display: inline-block; padding: 6px 12px; border-radius: 5px; font-weight: bold; text-decoration: none; font-size: 13px; }
    .btn-ajout  { background: #28a745; color: white; margin-bottom: 15px; display: inline-block; }
    .btn-liberer { background: #fd7e14; color: white; }
    .btn:hover  { opacity: 0.85; }
    .badge-dispo  { background: #d4edda; color: #155724; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
    .badge-occupe { background: #f8d7da; color: #721c24; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
</style>

<h2>Gestion des chambres</h2>

<a class="btn btn-ajout" href="CreeChambre.php">+ Ajouter une chambre</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Numéro</th>
            <th>Hôpital</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($chambres as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['id_chambre']) ?></td>
                <td><?= htmlspecialchars($c['numero']) ?></td>
                <td><?= htmlspecialchars($c['nom_hopital'] ?? 'N/A') ?></td>
                <td>
                    <?php if ($c['disponible']): ?>
                        <span class="badge-dispo">Disponible</span>
                    <?php else: ?>
                        <span class="badge-occupe">Occupée</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!$c['disponible']): ?>
                        <a class="btn btn-liberer"
                           href="../traitement/LibererChambreTrt.php?id=<?= $c['id_chambre'] ?>"
                           onclick="return confirm('Libérer cette chambre ?')">Libérer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($chambres)): ?>
            <tr><td colspan="5" style="text-align:center;">Aucune chambre enregistrée.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/footer.php'; ?>
