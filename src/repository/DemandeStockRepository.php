<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/DemandeStock.php';

use \PDO;
use PDOException;
use \DemandeStock;

/**
 * Gère les requêtes SQL liées aux demandes de stock médical.
 *
 * Permet aux médecins de soumettre des demandes d'approvisionnement en produits,
 * et aux administrateurs de les consulter, filtrer et changer leur statut.
 */
class DemandeStockRepository
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
     * Récupère toutes les demandes de stock avec les informations du produit et du médecin demandeur.
     *
     * Jointure entre demande_stock, produit et utilisateur pour enrichir chaque ligne
     * avec le libellé du produit et le nom/prénom du médecin.
     *
     * @return array Tableau associatif de toutes les demandes de stock
     */
    public function findAll(): array
    {
        try {
            // Récupère les demandes avec le libellé du produit et le nom du médecin via jointures
            $sql = "SELECT ds.*,
                        p.libelle AS libelle_produit,
                        u.nom AS nom_medecin,
                        u.prenom AS prenom_medecin
                    FROM demande_stock ds
                    JOIN produit p ON ds.ref_produit = p.id_produit
                    JOIN utilisateur u ON ds.ref_medecin = u.id_utilisateur
                    ORDER BY ds.date_demande DESC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll demandes stock : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère une demande de stock par son identifiant.
     *
     * @param mixed $id Identifiant de la demande
     * @return DemandeStock|null L'objet DemandeStock correspondant, ou null si non trouvé
     */
    public function findById($id): ?DemandeStock
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM demande_stock WHERE id_demande = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new DemandeStock(
                    $data['id_demande'],
                    $data['quantite'],
                    $data['statut'],
                    $data['date_demande'],
                    $data['ref_produit'],
                    $data['ref_medecin']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById demande stock : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère toutes les demandes de stock filtrées par statut, avec le détail du produit et du médecin.
     *
     * Jointure entre demande_stock, produit et utilisateur filtrée sur le statut demandé.
     *
     * @param string $statut Statut à filtrer (ex : "en_attente", "acceptée", "refusée")
     * @return array Tableau associatif des demandes correspondant au statut
     */
    public function findByStatut(string $statut): array
    {
        try {
            // Filtre les demandes par statut en joignant le produit et le médecin concernés
            $sql = "SELECT ds.*,
                        p.libelle AS libelle_produit,
                        u.nom AS nom_medecin,
                        u.prenom AS prenom_medecin
                    FROM demande_stock ds
                    JOIN produit p ON ds.ref_produit = p.id_produit
                    JOIN utilisateur u ON ds.ref_medecin = u.id_utilisateur
                    WHERE ds.statut = :statut
                    ORDER BY ds.date_demande DESC";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute([':statut' => $statut]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findByStatut demande stock : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour le statut d'une demande de stock.
     *
     * @param mixed  $id     Identifiant de la demande
     * @param string $statut Nouveau statut à appliquer
     * @return bool Vrai si la mise à jour a réussi, faux sinon
     */
    public function changerStatut($id, string $statut): bool
    {
        try {
            $stmt = $this->bdd->prepare("UPDATE demande_stock SET statut = :statut WHERE id_demande = :id");
            return $stmt->execute([':statut' => $statut, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur changerStatut demande stock : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Insère une nouvelle demande de stock en base de données.
     *
     * @param DemandeStock $demande L'objet demande à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(DemandeStock $demande): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO demande_stock (quantite, statut, date_demande, ref_produit, ref_medecin)
                VALUES (:quantite, :statut, :date_demande, :ref_produit, :ref_medecin)
            ");
            return $stmt->execute([
                ':quantite'     => $demande->getQuantite(),
                ':statut'       => $demande->getStatut() ?? 'en_attente',
                ':date_demande' => $demande->getDateDemande(),
                ':ref_produit'  => $demande->getRefProduit(),
                ':ref_medecin'  => $demande->getRefMedecin(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter demande stock : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'une demande de stock existante.
     *
     * @param DemandeStock $demande L'objet demande avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier(DemandeStock $demande): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE demande_stock
                SET quantite = :quantite,
                    statut = :statut,
                    date_demande = :date_demande,
                    ref_produit = :ref_produit,
                    ref_medecin = :ref_medecin
                WHERE id_demande = :id_demande
            ");
            return $stmt->execute([
                ':id_demande'   => $demande->getIdDemande(),
                ':quantite'     => $demande->getQuantite(),
                ':statut'       => $demande->getStatut(),
                ':date_demande' => $demande->getDateDemande(),
                ':ref_produit'  => $demande->getRefProduit(),
                ':ref_medecin'  => $demande->getRefMedecin(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier demande stock : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une demande de stock par son identifiant.
     *
     * @param mixed $id Identifiant de la demande à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM demande_stock WHERE id_demande = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer demande stock : ' . $e->getMessage());
            return false;
        }
    }
}
?>
