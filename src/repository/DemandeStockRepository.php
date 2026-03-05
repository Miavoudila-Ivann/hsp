<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/DemandeStock.php';

use \PDO;
use PDOException;
use \DemandeStock;

class DemandeStockRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        try {
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

    public function findByStatut(string $statut): array
    {
        try {
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
