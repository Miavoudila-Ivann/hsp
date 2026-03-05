<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Produit.php';

use \PDO;
use PDOException;
use \Produit;

class ProduitRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

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
