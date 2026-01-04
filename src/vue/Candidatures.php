<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';

if(!isset($_SESSION['id_utilisateur'])){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$cRepo = new CandidatureRepository($bdd);

$candidatures = $cRepo->findByUtilisateur($_SESSION['id_utilisateur']);

include __DIR__ . '/header.php';
?>

<h2>Mes candidatures</h2>

<table>
    <thead>
    <tr>
        <th>Titre</th>
        <th>Description</th>
        <th>Date</th>
        <th>Statut</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($candidatures as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['titre_poste']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td><?= $c['date_creation'] ?></td>
            <td><?= htmlspecialchars($c['statut']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a class="btn" href="EnvoyerCandidature.php">Nouvelle candidature</a>

<?php include __DIR__ . '/footer.php'; ?>
