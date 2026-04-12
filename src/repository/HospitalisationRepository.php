<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Hospitalisation.php';

use \PDO;
use PDOException;
use \Hospitalisation;

/**
 * Gère les requêtes SQL liées aux hospitalisations des patients.
 *
 * Permet de lister, créer, modifier et supprimer les hospitalisations,
 * qui associent un dossier de prise en charge, une chambre et un médecin référent.
 */
class HospitalisationRepository
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
     * Récupère toutes les hospitalisations avec les informations du dossier, de la chambre et du médecin.
     *
     * Jointure entre hospitalisation, dossier_prise_en_charge, chambre et utilisateur
     * pour obtenir un résultat enrichi avec le numéro de chambre, le statut du dossier
     * et le nom du médecin responsable. Triées par date de début décroissante.
     *
     * @return array Tableau associatif de toutes les hospitalisations
     */
    public function findAll(): array
    {
        try {
            // Jointure multiple pour enrichir les données d'hospitalisation
            $sql = "SELECT h.*,
                        d.date_arrivee AS date_arrivee_dossier,
                        d.statut AS statut_dossier,
                        c.numero AS numero_chambre,
                        c.disponible AS chambre_disponible,
                        u.nom AS nom_medecin,
                        u.prenom AS prenom_medecin
                    FROM hospitalisation h
                    JOIN dossier_prise_en_charge d ON h.ref_dossier = d.id_dossier
                    JOIN chambre c ON h.ref_chambre = c.id_chambre
                    JOIN utilisateur u ON h.ref_medecin = u.id_utilisateur
                    ORDER BY h.date_debut DESC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll hospitalisations : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère une hospitalisation par son identifiant.
     *
     * @param mixed $id Identifiant de l'hospitalisation
     * @return Hospitalisation|null L'objet Hospitalisation correspondant, ou null si non trouvé
     */
    public function findById($id): ?Hospitalisation
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM hospitalisation WHERE id_hospitalisation = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Hospitalisation(
                    $data['id_hospitalisation'],
                    $data['date_debut'],
                    $data['description_maladie'],
                    $data['ref_dossier'],
                    $data['ref_chambre'],
                    $data['ref_medecin']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById hospitalisation : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Insère une nouvelle hospitalisation en base de données.
     *
     * @param Hospitalisation $hospitalisation L'objet hospitalisation à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(Hospitalisation $hospitalisation): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO hospitalisation (date_debut, description_maladie, ref_dossier, ref_chambre, ref_medecin)
                VALUES (:date_debut, :description_maladie, :ref_dossier, :ref_chambre, :ref_medecin)
            ");
            return $stmt->execute([
                ':date_debut'          => $hospitalisation->getDateDebut(),
                ':description_maladie' => $hospitalisation->getDescriptionMaladie(),
                ':ref_dossier'         => $hospitalisation->getRefDossier(),
                ':ref_chambre'         => $hospitalisation->getRefChambre(),
                ':ref_medecin'         => $hospitalisation->getRefMedecin(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter hospitalisation : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'une hospitalisation existante.
     *
     * @param Hospitalisation $hospitalisation L'objet hospitalisation avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier(Hospitalisation $hospitalisation): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE hospitalisation
                SET date_debut = :date_debut,
                    description_maladie = :description_maladie,
                    ref_dossier = :ref_dossier,
                    ref_chambre = :ref_chambre,
                    ref_medecin = :ref_medecin
                WHERE id_hospitalisation = :id_hospitalisation
            ");
            return $stmt->execute([
                ':id_hospitalisation'  => $hospitalisation->getIdHospitalisation(),
                ':date_debut'          => $hospitalisation->getDateDebut(),
                ':description_maladie' => $hospitalisation->getDescriptionMaladie(),
                ':ref_dossier'         => $hospitalisation->getRefDossier(),
                ':ref_chambre'         => $hospitalisation->getRefChambre(),
                ':ref_medecin'         => $hospitalisation->getRefMedecin(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier hospitalisation : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une hospitalisation par son identifiant.
     *
     * @param mixed $id Identifiant de l'hospitalisation à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM hospitalisation WHERE id_hospitalisation = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer hospitalisation : ' . $e->getMessage());
            return false;
        }
    }
}
?>
