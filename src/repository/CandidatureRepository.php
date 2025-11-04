<?php
namespace repository;

use modele\Candidature;
use PDO;

class CandidatureRepository
{
    private PDO $bdd;

    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd;
    }

    // === Ajouter une candidature ===
    public function ajouter(Candidature $candidature): bool
    {
        $sql = "INSERT INTO candidature (motivation, statut, date_candidature, ref_offre, ref_utilisateur, cv_path)
                VALUES (:motivation, :statut, :date_candidature, :ref_offre, :ref_utilisateur, :cv_path)";
        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            ':motivation' => $candidature->getMotivation(),
            ':statut' => $candidature->getStatut(),
            ':date_candidature' => $candidature->getDateCandidature(),
            ':ref_offre' => $candidature->getRefOffre(),
            ':ref_utilisateur' => $candidature->getRefUtilisateur(),
            ':cv_path' => $candidature->getCvPath()
        ]);
    }

    // === Récupérer toutes les candidatures ===
    public function findAll(): array
    {
        $stmt = $this->bdd->query("SELECT * FROM candidature ORDER BY date_candidature DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === Supprimer une candidature ===
    public function supprimer(int $id): bool
    {
        $stmt = $this->bdd->prepare("DELETE FROM candidature WHERE id_candidature = ?");
        return $stmt->execute([$id]);
    }

    // === Modifier le statut ===
    public function modifierStatut(int $id, string $statut): bool
    {
        $stmt = $this->bdd->prepare("UPDATE candidature SET statut = ? WHERE id_candidature = ?");
        return $stmt->execute([$statut, $id]);
    }
}
