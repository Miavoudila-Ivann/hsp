<?php

use repository\CandidatureRepository;

session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CandidatureRepository.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$cRepo = new CandidatureRepository($bdd);

if(isset($_GET['delete'])){
    $cRepo->supprimer((int)$_GET['delete']);
    header("Location: ListeCandidatures.php");
    exit();
}

$candidatures = $cRepo->findAll();
include __DIR__ . '/header.php';
?>

<h2>Liste des candidatures</h2>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Utilisateur</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Date</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($candidatures as $c): ?>
        <tr>
            <td><?= $c['id_candidature'] ?></td>
            <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
            <td><?= htmlspecialchars($c['titre_poste']) ?></td>
            <td><?= htmlspecialchars($c['description']) ?></td>
            <td><?= $c['date_creation'] ?></td>
            <td><?= htmlspecialchars($c['statut']) ?></td>
            <td>
                <a href="ModifierCandidature.php?id=<?= $c['id_candidature'] ?>">Modifier</a> |
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a class="btn" href="../vue/admin.php">Retour au dashboard</a>

<?php include __DIR__ . '/footer.php'; ?>
