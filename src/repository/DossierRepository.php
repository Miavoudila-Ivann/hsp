<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/DossierPriseEnCharge.php';

use \PDO;
use PDOException;
use \DossierPriseEnCharge;

class DossierRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT d.*,
                        p.nom AS nom_patient,
                        p.prenom AS prenom_patient,
                        u.nom AS nom_secretaire
                    FROM dossier_prise_en_charge d
                    JOIN patient p ON d.ref_patient = p.id_patient
                    JOIN utilisateur u ON d.ref_secretaire = u.id_utilisateur
                    ORDER BY d.date_arrivee DESC, d.heure_arrivee DESC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll dossiers : ' . $e->getMessage());
            return [];
        }
    }

    public function findById($id): ?DossierPriseEnCharge
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM dossier_prise_en_charge WHERE id_dossier = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new DossierPriseEnCharge(
                    $data['id_dossier'],
                    $data['date_arrivee'],
                    $data['heure_arrivee'],
                    $data['symptomes'],
                    $data['gravite'],
                    $data['statut'],
                    $data['ref_patient'],
                    $data['ref_secretaire']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById dossier : ' . $e->getMessage());
            return null;
        }
    }

    public function findByStatut(string $statut): array
    {
        try {
            $sql = "SELECT d.*,
                        p.nom AS nom_patient,
                        p.prenom AS prenom_patient,
                        u.nom AS nom_secretaire
                    FROM dossier_prise_en_charge d
                    JOIN patient p ON d.ref_patient = p.id_patient
                    JOIN utilisateur u ON d.ref_secretaire = u.id_utilisateur
                    WHERE d.statut = :statut
                    ORDER BY d.date_arrivee DESC, d.heure_arrivee DESC";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute([':statut' => $statut]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findByStatut dossier : ' . $e->getMessage());
            return [];
        }
    }

    public function changerStatut($id, string $statut): bool
    {
        try {
            $stmt = $this->bdd->prepare("UPDATE dossier_prise_en_charge SET statut = :statut WHERE id_dossier = :id");
            return $stmt->execute([':statut' => $statut, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur changerStatut dossier : ' . $e->getMessage());
            return false;
        }
    }

    public function ajouter(DossierPriseEnCharge $dossier): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO dossier_prise_en_charge (date_arrivee, heure_arrivee, symptomes, gravite, statut, ref_patient, ref_secretaire)
                VALUES (:date_arrivee, :heure_arrivee, :symptomes, :gravite, :statut, :ref_patient, :ref_secretaire)
            ");
            return $stmt->execute([
                ':date_arrivee'   => $dossier->getDateArrivee(),
                ':heure_arrivee'  => $dossier->getHeureArrivee(),
                ':symptomes'      => $dossier->getSymptomes(),
                ':gravite'        => $dossier->getGravite(),
                ':statut'         => $dossier->getStatut() ?? 'en_attente',
                ':ref_patient'    => $dossier->getRefPatient(),
                ':ref_secretaire' => $dossier->getRefSecretaire(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter dossier : ' . $e->getMessage());
            return false;
        }
    }

    public function modifier(DossierPriseEnCharge $dossier): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE dossier_prise_en_charge
                SET date_arrivee = :date_arrivee,
                    heure_arrivee = :heure_arrivee,
                    symptomes = :symptomes,
                    gravite = :gravite,
                    statut = :statut,
                    ref_patient = :ref_patient,
                    ref_secretaire = :ref_secretaire
                WHERE id_dossier = :id_dossier
            ");
            return $stmt->execute([
                ':id_dossier'     => $dossier->getIdDossier(),
                ':date_arrivee'   => $dossier->getDateArrivee(),
                ':heure_arrivee'  => $dossier->getHeureArrivee(),
                ':symptomes'      => $dossier->getSymptomes(),
                ':gravite'        => $dossier->getGravite(),
                ':statut'         => $dossier->getStatut(),
                ':ref_patient'    => $dossier->getRefPatient(),
                ':ref_secretaire' => $dossier->getRefSecretaire(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier dossier : ' . $e->getMessage());
            return false;
        }
    }

    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM dossier_prise_en_charge WHERE id_dossier = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer dossier : ' . $e->getMessage());
            return false;
        }
    }
}
?>
