<?php

/**
 * Classe Hospitalisation — Représente le séjour d'un patient hospitalisé dans une chambre.
 *
 * Une hospitalisation est déclenchée depuis un dossier de prise en charge et rattache
 * un patient à une chambre spécifique sous la responsabilité d'un médecin référent.
 * Elle documente la pathologie traitée pendant le séjour.
 */
class Hospitalisation {

    /** @var mixed Identifiant unique de l'hospitalisation */
    private $id_hospitalisation;

    /** @var mixed Date de début du séjour hospitalier */
    private $date_debut;

    /** @var mixed Description de la maladie ou pathologie traitée durant l'hospitalisation */
    private $description_maladie;

    /** @var mixed Clé étrangère vers le dossier de prise en charge associé */
    private $ref_dossier;

    /** @var mixed Clé étrangère vers la chambre attribuée au patient */
    private $ref_chambre;

    /** @var mixed Clé étrangère vers le médecin responsable du suivi du patient */
    private $ref_medecin;

    /**
     * Initialise une hospitalisation avec les informations du séjour.
     *
     * @param mixed $id_hospitalisation  Identifiant de l'hospitalisation
     * @param mixed $date_debut          Date de début du séjour
     * @param mixed $description_maladie Description de la pathologie traitée
     * @param mixed $ref_dossier         Référence du dossier de prise en charge
     * @param mixed $ref_chambre         Référence de la chambre attribuée
     * @param mixed $ref_medecin         Référence du médecin responsable
     */
    public function __construct($id_hospitalisation = null, $date_debut = null, $description_maladie = null, $ref_dossier = null, $ref_chambre = null, $ref_medecin = null) {
        $this->id_hospitalisation = $id_hospitalisation;
        $this->date_debut = $date_debut;
        $this->description_maladie = $description_maladie;
        $this->ref_dossier = $ref_dossier;
        $this->ref_chambre = $ref_chambre;
        $this->ref_medecin = $ref_medecin;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdHospitalisation() {
        return $this->id_hospitalisation;
    }

    /** @return mixed */
    public function getDateDebut() {
        return $this->date_debut;
    }

    /** @return mixed */
    public function getDescriptionMaladie() {
        return $this->description_maladie;
    }

    /** @return mixed */
    public function getRefDossier() {
        return $this->ref_dossier;
    }

    /** @return mixed */
    public function getRefChambre() {
        return $this->ref_chambre;
    }

    /** @return mixed */
    public function getRefMedecin() {
        return $this->ref_medecin;
    }

    // --- Setters ---

    /** @param mixed $id_hospitalisation */
    public function setIdHospitalisation($id_hospitalisation) {
        $this->id_hospitalisation = $id_hospitalisation;
    }

    /** @param mixed $date_debut */
    public function setDateDebut($date_debut) {
        $this->date_debut = $date_debut;
    }

    /** @param mixed $description_maladie */
    public function setDescriptionMaladie($description_maladie) {
        $this->description_maladie = $description_maladie;
    }

    /** @param mixed $ref_dossier */
    public function setRefDossier($ref_dossier) {
        $this->ref_dossier = $ref_dossier;
    }

    /** @param mixed $ref_chambre */
    public function setRefChambre($ref_chambre) {
        $this->ref_chambre = $ref_chambre;
    }

    /** @param mixed $ref_medecin */
    public function setRefMedecin($ref_medecin) {
        $this->ref_medecin = $ref_medecin;
    }
}
?>
