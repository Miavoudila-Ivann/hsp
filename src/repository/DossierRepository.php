<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/DossierPriseEnCharge.php';

use \PDO;
use PDOException;
use \DossierPriseEnCharge;

/**
 * Gère les requêtes SQL liées aux dossiers de prise en charge des patients.
 *
 * Un dossier regroupe les informations d'admission d'un patient (date, heure,
 * symptômes, gravité, statut) et est rattaché à un patient et à une secrétaire.
 */
class DossierRepository
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
     * Récupère tous les dossiers de prise en charge avec le nom du patient et de la secrétaire.
     *
     * Jointure entre dossier_prise_en_charge, patient et utilisateur pour enrichir
     * chaque ligne avec les noms lisibles. Triés par date et heure décroissantes.
     *
     * @return array Tableau associatif de tous les dossiers
     */
    public function findAll(): array
    {
        try {
            // Récupère les dossiers en joignant le nom du patient et de la secrétaire responsable
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

    /**
     * Récupère un dossier de prise en charge par son identifiant.
     *
     * @param mixed $id Identifiant du dossier
     * @return DossierPriseEnCharge|null L'objet DossierPriseEnCharge correspondant, ou null si non trouvé
     */
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

    /**
     * Récupère les dossiers filtrés par statut, avec le détail du patient et de la secrétaire.
     *
     * @param string $statut Statut à filtrer (ex : "en_attente", "traité", "clôturé")
     * @return array Tableau associatif des dossiers correspondant au statut
     */
    public function findByStatut(string $statut): array
    {
        try {
            // Filtre les dossiers par statut en joignant le patient et la secrétaire
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

    /**
     * Met à jour le statut d'un dossier de prise en charge.
     *
     * @param mixed  $id     Identifiant du dossier
     * @param string $statut Nouveau statut à appliquer
     * @return bool Vrai si la mise à jour a réussi, faux sinon
     */
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

    /**
     * Insère un nouveau dossier de prise en charge en base de données.
     *
     * @param DossierPriseEnCharge $dossier L'objet dossier à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
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

    /**
     * Met à jour les informations d'un dossier de prise en charge existant.
     *
     * @param DossierPriseEnCharge $dossier L'objet dossier avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
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

    /**
     * Supprime un dossier de prise en charge par son identifiant.
     *
     * @param mixed $id Identifiant du dossier à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
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
