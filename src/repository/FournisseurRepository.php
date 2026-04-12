<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Fournisseur.php';

use \PDO;
use PDOException;
use \Fournisseur;

/**
 * Gère les requêtes SQL liées aux fournisseurs de produits médicaux.
 *
 * Permet de lister, créer, modifier et supprimer les fournisseurs,
 * ainsi que de les retrouver selon les produits qu'ils fournissent.
 */
class FournisseurRepository
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
     * Récupère tous les fournisseurs, triés par identifiant croissant.
     *
     * @return array Tableau associatif de tous les fournisseurs
     */
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

    /**
     * Récupère un fournisseur par son identifiant.
     *
     * @param mixed $id Identifiant du fournisseur
     * @return Fournisseur|null L'objet Fournisseur correspondant, ou null si non trouvé
     */
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

    /**
     * Récupère tous les fournisseurs associés à un produit donné, avec le prix pratiqué.
     *
     * Jointure entre fournisseur et la table de liaison fournisseur_produit
     * pour récupérer le prix unitaire proposé par chaque fournisseur pour ce produit.
     *
     * @param mixed $id_produit Identifiant du produit
     * @return array Tableau associatif des fournisseurs avec leur prix pour le produit
     */
    public function findByProduit($id_produit): array
    {
        try {
            // Jointure avec la table fournisseur_produit pour obtenir le prix par fournisseur
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

    /**
     * Insère un nouveau fournisseur en base de données.
     *
     * @param Fournisseur $fournisseur L'objet fournisseur à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
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

    /**
     * Met à jour les informations d'un fournisseur existant.
     *
     * @param Fournisseur $fournisseur L'objet fournisseur avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
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

    /**
     * Supprime un fournisseur par son identifiant.
     *
     * @param mixed $id Identifiant du fournisseur à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
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
