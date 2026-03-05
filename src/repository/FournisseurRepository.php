<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Fournisseur.php';

use \PDO;
use PDOException;
use \Fournisseur;

class FournisseurRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM fournisseur ORDER BY id_fournisseur ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll fournisseurs : ' . $e->getMessage());
            return [];
        }
    }

    public function findById($id): ?Fournisseur
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM fournisseur WHERE id_fournisseur = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Fournisseur(
                    $data['id_fournisseur'],
                    $data['nom'],
                    $data['contact'],
                    $data['email']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById fournisseur : ' . $e->getMessage());
            return null;
        }
    }

    public function findByProduit($id_produit): array
    {
        try {
            $sql = "SELECT f.*, fp.prix
                    FROM fournisseur f
                    JOIN fournisseur_produit fp ON f.id_fournisseur = fp.ref_fournisseur
                    WHERE fp.ref_produit = :id_produit
                    ORDER BY f.nom ASC";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute([':id_produit' => $id_produit]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findByProduit fournisseur : ' . $e->getMessage());
            return [];
        }
    }

    public function ajouter(Fournisseur $fournisseur): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO fournisseur (nom, contact, email)
                VALUES (:nom, :contact, :email)
            ");
            return $stmt->execute([
                ':nom'     => $fournisseur->getNom(),
                ':contact' => $fournisseur->getContact(),
                ':email'   => $fournisseur->getEmail(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter fournisseur : ' . $e->getMessage());
            return false;
        }
    }

    public function modifier(Fournisseur $fournisseur): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE fournisseur
                SET nom = :nom,
                    contact = :contact,
                    email = :email
                WHERE id_fournisseur = :id_fournisseur
            ");
            return $stmt->execute([
                ':id_fournisseur' => $fournisseur->getIdFournisseur(),
                ':nom'            => $fournisseur->getNom(),
                ':contact'        => $fournisseur->getContact(),
                ':email'          => $fournisseur->getEmail(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier fournisseur : ' . $e->getMessage());
            return false;
        }
    }

    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM fournisseur WHERE id_fournisseur = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer fournisseur : ' . $e->getMessage());
            return false;
        }
    }
}
?>
