<?php
namespace repository;

use modele\Candidature;
use PDO;

/**
 * Gère les requêtes SQL liées aux candidatures.
 *
 * Permet d'ajouter, récupérer, modifier et supprimer des candidatures
 * soumises par des utilisateurs pour des offres d'emploi.
 */
class CandidatureRepository
{
    /** @var PDO Instance de connexion à la base de données */
    private PDO $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd;
    }

    /**
     * Insère une nouvelle candidature en base de données.
     *
     * @param Candidature $candidature L'objet candidature à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(Candidature $candidature): bool
    {
        $sql = "INSERT INTO candidature (motivation, statut, date_candidature, ref_offre, ref_utilisateur, cv_path)
                VALUES (:motivation, :statut, :date_candidature, :ref_offre, :ref_utilisateur, :cv_path)";
        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            ':motivation'       => $candidature->getMotivation(),
            ':statut'           => $candidature->getStatut(),
            ':date_candidature' => $candidature->getDateCandidature(),
            ':ref_offre'        => $candidature->getRefOffre(),
            ':ref_utilisateur'  => $candidature->getRefUtilisateur(),
            ':cv_path'          => $candidature->getCvPath()
        ]);
    }

    /**
     * Récupère toutes les candidatures, triées par date décroissante.
     *
     * @return array Tableau associatif de toutes les candidatures
     */
    public function findAll(): array
    {
        $stmt = $this->bdd->query("SELECT * FROM candidature ORDER BY date_candidature DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime une candidature par son identifiant.
     *
     * @param int $id Identifiant de la candidature à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer(int $id): bool
    {
        $stmt = $this->bdd->prepare("DELETE FROM candidature WHERE id_candidature = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Met à jour le statut d'une candidature (ex : "acceptée", "refusée", "en attente").
     *
     * @param int    $id     Identifiant de la candidature
     * @param string $statut Nouveau statut à appliquer
     * @return bool Vrai si la mise à jour a réussi, faux sinon
     */
    public function modifierStatut(int $id, string $statut): bool
    {
        $stmt = $this->bdd->prepare("UPDATE candidature SET statut = ? WHERE id_candidature = ?");
        return $stmt->execute([$statut, $id]);
    }
}
