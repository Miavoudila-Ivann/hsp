<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Ordonnance.php';

use \PDO;
use PDOException;
use \Ordonnance;

class OrdonnanceRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT o.*,
                        d.date_arrivee AS date_arrivee_dossier,
                        d.statut AS statut_dossier,
                        u.nom AS nom_medecin,
                        u.prenom AS prenom_medecin
                    FROM ordonnance o
                    JOIN dossier_prise_en_charge d ON o.ref_dossier = d.id_dossier
                    JOIN utilisateur u ON o.ref_medecin = u.id_utilisateur
                    ORDER BY o.date_emission DESC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll ordonnances : ' . $e->getMessage());
            return [];
        }
    }

    public function findById($id): ?Ordonnance
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM ordonnance WHERE id_ordonnance = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Ordonnance(
                    $data['id_ordonnance'],
                    $data['date_emission'],
                    $data['contenu'],
                    $data['ref_dossier'],
                    $data['ref_medecin']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById ordonnance : ' . $e->getMessage());
            return null;
        }
    }

    public function ajouter(Ordonnance $ordonnance): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO ordonnance (date_emission, contenu, ref_dossier, ref_medecin)
                VALUES (:date_emission, :contenu, :ref_dossier, :ref_medecin)
            ");
            return $stmt->execute([
                ':date_emission' => $ordonnance->getDateEmission(),
                ':contenu'       => $ordonnance->getContenu(),
                ':ref_dossier'   => $ordonnance->getRefDossier(),
                ':ref_medecin'   => $ordonnance->getRefMedecin(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter ordonnance : ' . $e->getMessage());
            return false;
        }
    }

    public function modifier(Ordonnance $ordonnance): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE ordonnance
                SET date_emission = :date_emission,
                    contenu = :contenu,
                    ref_dossier = :ref_dossier,
                    ref_medecin = :ref_medecin
                WHERE id_ordonnance = :id_ordonnance
            ");
            return $stmt->execute([
                ':id_ordonnance'  => $ordonnance->getIdOrdonnance(),
                ':date_emission'  => $ordonnance->getDateEmission(),
                ':contenu'        => $ordonnance->getContenu(),
                ':ref_dossier'    => $ordonnance->getRefDossier(),
                ':ref_medecin'    => $ordonnance->getRefMedecin(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier ordonnance : ' . $e->getMessage());
            return false;
        }
    }

    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM ordonnance WHERE id_ordonnance = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer ordonnance : ' . $e->getMessage());
            return false;
        }
    }
}
?>
