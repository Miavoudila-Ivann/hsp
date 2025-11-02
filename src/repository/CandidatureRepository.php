<?php
namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Candidature.php';

use modele\Candidature;
use PDO;

class CandidatureRepository
{
    private PDO $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        $stmt = $this->bdd->query("SELECT * FROM candidature ORDER BY date_candidature DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUtilisateur(int $idUtilisateur): array
    {
        $stmt = $this->bdd->prepare("SELECT * FROM candidature WHERE ref_utilisateur = :id");
        $stmt->execute(['id' => $idUtilisateur]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $stmt = $this->bdd->prepare("SELECT * FROM candidature WHERE id_candidature = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function ajouter(Candidature $candidature): bool
    {
        $sql = "INSERT INTO candidature (motivation, statut, date_candidature, ref_offre, ref_utilisateur)
                VALUES (:motivation, :statut, :date_candidature, :ref_offre, :ref_utilisateur)";
        $stmt = $this->bdd->prepare($sql);
        return $stmt->execute([
            ':motivation' => $candidature->getMotivation(),
            ':statut' => $candidature->getStatut(),
            ':date_candidature' => $candidature->getDateCandidature(),
            ':ref_offre' => $candidature->getRefOffre(),
            ':ref_utilisateur' => $candidature->getRefUtilisateur()
        ]);
    }

    public function modifierStatut(int $id, string $statut): bool
    {
        $sql = "UPDATE candidature SET statut = :statut WHERE id_candidature = :id";
        $stmt = $this->bdd->prepare($sql);
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }

    public function supprimer(int $id): bool
    {
        $stmt = $this->bdd->prepare("DELETE FROM candidature WHERE id_candidature = :id");
        return $stmt->execute([':id' => $id]);
    }
}
