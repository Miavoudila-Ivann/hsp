<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/ContratRepository.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new ContratRepository($bdd);

if(isset($_GET['delete'])){
    $repo->supprimerContrat((int)$_GET['delete']);
    header("Location: ListeContrat.php");
    exit();
}

$contrats = $repo->findAll();
include __DIR__ . '/header.php';
?>

<h2>Liste des contrats</h2>

<a class="btn" href="CreeContrat.php">Créer un contrat</a>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Utilisateur</th>
        <th>Poste</th>
        <th>Date début</th>
        <th>Date fin</th>
        <th>Salaire</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($contrats as $c): ?>
        <tr>
            <td><?= $c['id_contrat'] ?></td>
            <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
            <td><?= htmlspecialchars($c['poste']) ?></td>
            <td><?= $c['date_debut'] ?></td>
            <td><?= $c['date_fin'] ?></td>
            <td><?= htmlspecialchars($c['salaire']) ?> €</td>
            <td>
                <a href="ModifierContrat.php?id=<?= $c['id_contrat'] ?>">Modifier</a> |
                <a href="ListeContrats.php?delete=<?= $c['id_contrat'] ?>" onclick="return confirm('Supprimer ce contrat ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a class="btn" href="../dashboard/index.php">Retour au dashboard</a>
<?php include __DIR__ . '/footer.php'; ?>
