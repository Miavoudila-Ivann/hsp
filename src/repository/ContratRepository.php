<?php
namespace repository; // Doit être en première ligne avant tout require/include

use Contrat;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Contrat.php';

/**
 * Gère les requêtes SQL liées aux contrats du personnel.
 *
 * Permet de lister, créer, modifier et supprimer les contrats
 * associés aux utilisateurs (médecins, secrétaires, etc.).
 */
class ContratRepository
{
    /** @var \PDO Instance de connexion à la base de données */
    private $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param \PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Récupère tous les contrats, triés par date de début décroissante.
     *
     * @return array Tableau associatif de tous les contrats
     */
    public function findAll(): array {
        $stmt = $this->bdd->query("SELECT * FROM contrat ORDER BY date_debut DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un contrat par son identifiant.
     *
     * @param int $id Identifiant du contrat
     * @return array|false Tableau associatif du contrat, ou false si non trouvé
     */
    public function findById(int $id) {
        $stmt = $this->bdd->prepare("SELECT * FROM contrat WHERE id_contrat = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Insère un nouveau contrat en base de données.
     *
     * @param Contrat $contrat L'objet contrat à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(Contrat $contrat): bool {
        $stmt = $this->bdd->prepare(
            "INSERT INTO contrat (ref_utilisateur, date_debut, date_fin)
             VALUES (:ref_utilisateur, :date_debut, :date_fin)"
        );
        return $stmt->execute([
            ':ref_utilisateur' => $contrat->getRefUtilisateur(),
            ':date_debut'      => $contrat->getDateDebut(),
            ':date_fin'        => $contrat->getDateFin()
        ]);
    }

    /**
     * Met à jour les informations d'un contrat existant.
     *
     * @param Contrat $contrat L'objet contrat avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier(Contrat $contrat): bool {
        $stmt = $this->bdd->prepare(
            "UPDATE contrat SET ref_utilisateur = :ref_utilisateur, date_debut = :date_debut, date_fin = :date_fin
             WHERE id_contrat = :id"
        );
        return $stmt->execute([
            ':ref_utilisateur' => $contrat->getRefUtilisateur(),
            ':date_debut'      => $contrat->getDateDebut(),
            ':date_fin'        => $contrat->getDateFin(),
            ':id'              => $contrat->getIdContrat()
        ]);
    }

    /**
     * Supprime un contrat par son identifiant.
     *
     * @param int $id Identifiant du contrat à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer(int $id): bool {
        $stmt = $this->bdd->prepare("DELETE FROM contrat WHERE id_contrat = :id");
        return $stmt->execute([':id' => $id]);
    }
}
