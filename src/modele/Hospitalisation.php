<?php
class Hospitalisation {
    private $id_hospitalisation;
    private $date_debut;
    private $description_maladie;
    private $ref_dossier;
    private $ref_chambre;
    private $ref_medecin;

    public function __construct($id_hospitalisation = null, $date_debut = null, $description_maladie = null, $ref_dossier = null, $ref_chambre = null, $ref_medecin = null) {
        $this->id_hospitalisation = $id_hospitalisation;
        $this->date_debut = $date_debut;
        $this->description_maladie = $description_maladie;
        $this->ref_dossier = $ref_dossier;
        $this->ref_chambre = $ref_chambre;
        $this->ref_medecin = $ref_medecin;
    }

    public function getIdHospitalisation() {
        return $this->id_hospitalisation;
    }
    public function getDateDebut() {
        return $this->date_debut;
    }
    public function getDescriptionMaladie() {
        return $this->description_maladie;
    }
    public function getRefDossier() {
        return $this->ref_dossier;
    }
    public function getRefChambre() {
        return $this->ref_chambre;
    }
    public function getRefMedecin() {
        return $this->ref_medecin;
    }

    public function setIdHospitalisation($id_hospitalisation) {
        $this->id_hospitalisation = $id_hospitalisation;
    }
    public function setDateDebut($date_debut) {
        $this->date_debut = $date_debut;
    }
    public function setDescriptionMaladie($description_maladie) {
        $this->description_maladie = $description_maladie;
    }
    public function setRefDossier($ref_dossier) {
        $this->ref_dossier = $ref_dossier;
    }
    public function setRefChambre($ref_chambre) {
        $this->ref_chambre = $ref_chambre;
    }
    public function setRefMedecin($ref_medecin) {
        $this->ref_medecin = $ref_medecin;
    }
}
?>
