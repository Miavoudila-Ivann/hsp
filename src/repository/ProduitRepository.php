<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Produit.php';

use \PDO;
use PDOException;
use \Produit;

/**
 * Gère les requêtes SQL liées aux produits médicaux en stock.
 *
 * Permet de lister, créer, modifier et supprimer les produits
 * (médicaments, équipements, consommables) gérés dans le stock hospitalier.
 */
class ProduitRepository
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
     * Récupère tous les produits, triés par identifiant croissant.
     *
     * @return array Tableau associatif de tous les produits
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM produit ORDER BY id_produit ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll produits : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un produit par son identifiant.
     *
     * @param mixed $id Identifiant du produit
     * @return Produit|null L'objet Produit correspondant, ou null si non trouvé
     */
    public function findById($id): ?Produit
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM produit WHERE id_produit = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Produit(
                    $data['id_produit'],
                    $data['libelle'],
                    $data['description'],
                    $data['dangerosite'],
                    $data['stock_actuel']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById produit : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Insère un nouveau produit en base de données.
     *
     * Le stock est initialisé à 0 si aucune valeur n'est fournie.
     *
     * @param Produit $produit L'objet produit à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(Produit $produit): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO produit (libelle, description, dangerosite, stock_actuel)
                VALUES (:libelle, :description, :dangerosite, :stock_actuel)
            ");
            return $stmt->execute([
                ':libelle'      => $produit->getLibelle(),
                ':description'  => $produit->getDescription(),
                ':dangerosite'  => $produit->getDangerosite(),
                ':stock_actuel' => $produit->getStockActuel() ?? 0,
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter produit : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'un produit existant.
     *
     * @param Produit $produit L'objet produit avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier(Produit $produit): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE produit
                SET libelle = :libelle,
                    description = :description,
                    dangerosite = :dangerosite,
                    stock_actuel = :stock_actuel
                WHERE id_produit = :id_produit
            ");
            return $stmt->execute([
                ':id_produit'   => $produit->getIdProduit(),
                ':libelle'      => $produit->getLibelle(),
                ':description'  => $produit->getDescription(),
                ':dangerosite'  => $produit->getDangerosite(),
                ':stock_actuel' => $produit->getStockActuel(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier produit : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un produit par son identifiant.
     *
     * @param mixed $id Identifiant du produit à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM produit WHERE id_produit = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer produit : ' . $e->getMessage());
            return false;
        }
    }
}
?>
