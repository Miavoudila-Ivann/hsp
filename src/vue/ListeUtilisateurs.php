<?php
session_start();
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/UtilisateurRepository.php';

use repository\UtilisateurRepository;
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../../index.php");
    exit();
}

$database = new Bdd();
$bdd = $database->getBdd();
$repo = new UtilisateurRepository($bdd);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $repo->supprimerUtilisateur($id);
    header("Location: ListeUtilisateurs.php");
    exit();
}

$utilisateurs = $repo->findAll();
$title = "Liste des utilisateurs";
include __DIR__ . '/header.php';
?>

<h2>Liste des utilisateurs</h2>

<!-- Bouton retour au tableau de bord/admin -->
<a class="btn-retour" href="../../index.php">&larr; Retour au tableau de bord</a>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Ville</th>
        <th>Actions</th>
        <th>Statut</th>

    </tr>
    </thead>
    <tbody>
    <?php foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= $u['id_utilisateur'] ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= htmlspecialchars($u['ville']) ?></td>
            <td><?= htmlspecialchars($u['status'] ?? '') ?></td>

            <td class="actions">
                <a class="btn-modifier" href="ModifierUtilisateur.php?email=<?= urlencode($u['email']) ?>">Modifier</a>
                <a class="btn-supprimer" href="ListeUtilisateurs.php?delete=<?= $u['id_utilisateur'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<style>
    .btn-retour {
        display: inline-block;
        margin-bottom: 15px;
        padding: 8px 12px;
        background-color: #4CAF50; /* même style que le thème */
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }
    .btn-retour:hover {
        background-color: #45a049;
    }
    .btn-modifier {
        color: #2196F3;
        text-decoration: none;
        margin-right: 8px;
    }
    .btn-modifier:hover {
        text-decoration: underline;
    }
    .btn-supprimer {
        color: #f44336;
        text-decoration: none;
    }
    .btn-supprimer:hover {
        text-decoration: underline;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>
