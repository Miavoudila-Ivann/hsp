<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Chambre.php';

use \PDO;
use PDOException;
use \Chambre;

/**
 * Gère les requêtes SQL liées aux chambres hospitalières.
 *
 * Permet de lister, créer, modifier, supprimer et gérer la disponibilité
 * des chambres associées à un hôpital.
 */
class ChambreRepository
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
     * Récupère toutes les chambres, triées par identifiant croissant.
     *
     * @return array Tableau associatif de toutes les chambres
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM chambre ORDER BY id_chambre ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll chambres : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère une chambre par son identifiant.
     *
     * @param mixed $id Identifiant de la chambre
     * @return Chambre|null L'objet Chambre correspondant, ou null si non trouvé
     */
    public function findById($id): ?Chambre
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM chambre WHERE id_chambre = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Chambre(
                    $data['id_chambre'],
                    $data['numero'],
                    $data['disponible'],
                    $data['ref_hopital']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById chambre : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère toutes les chambres disponibles (disponible = 1).
     *
     * @return array Tableau associatif des chambres disponibles
     */
    public function findDisponibles(): array
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM chambre WHERE disponible = 1 ORDER BY id_chambre ASC");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findDisponibles chambre : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour la disponibilité d'une chambre.
     *
     * @param mixed $id   Identifiant de la chambre
     * @param bool  $bool Vrai pour rendre disponible, faux pour indisponible
     * @return bool Vrai si la mise à jour a réussi, faux sinon
     */
    public function setDisponible($id, bool $bool): bool
    {
        try {
            $stmt = $this->bdd->prepare("UPDATE chambre SET disponible = :disponible WHERE id_chambre = :id");
            return $stmt->execute([':disponible' => $bool ? 1 : 0, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur setDisponible chambre : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Insère une nouvelle chambre en base de données.
     *
     * @param Chambre $chambre L'objet chambre à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(Chambre $chambre): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO chambre (numero, disponible, ref_hopital)
                VALUES (:numero, :disponible, :ref_hopital)
            ");
            return $stmt->execute([
                ':numero'      => $chambre->getNumero(),
                ':disponible'  => $chambre->getDisponible() ?? 1,
                ':ref_hopital' => $chambre->getRefHopital(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter chambre : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'une chambre existante.
     *
     * @param Chambre $chambre L'objet chambre avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier(Chambre $chambre): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE chambre
                SET numero = :numero,
                    disponible = :disponible,
                    ref_hopital = :ref_hopital
                WHERE id_chambre = :id_chambre
            ");
            return $stmt->execute([
                ':id_chambre'  => $chambre->getIdChambre(),
                ':numero'      => $chambre->getNumero(),
                ':disponible'  => $chambre->getDisponible(),
                ':ref_hopital' => $chambre->getRefHopital(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier chambre : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une chambre par son identifiant.
     *
     * @param mixed $id Identifiant de la chambre à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM chambre WHERE id_chambre = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer chambre : ' . $e->getMessage());
            return false;
        }
    }
}
?>
