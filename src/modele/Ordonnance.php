<?php
class Ordonnance {
    private $id_ordonnance;
    private $date_emission;
    private $contenu;
    private $ref_dossier;
    private $ref_medecin;

    public function __construct($id_ordonnance = null, $date_emission = null, $contenu = null, $ref_dossier = null, $ref_medecin = null) {
        $this->id_ordonnance = $id_ordonnance;
        $this->date_emission = $date_emission;
        $this->contenu = $contenu;
        $this->ref_dossier = $ref_dossier;
        $this->ref_medecin = $ref_medecin;
    }

    public function getIdOrdonnance() {
        return $this->id_ordonnance;
    }
    public function getDateEmission() {
        return $this->date_emission;
    }
    public function getContenu() {
        return $this->contenu;
    }
    public function getRefDossier() {
        return $this->ref_dossier;
    }
    public function getRefMedecin() {
        return $this->ref_medecin;
    }

    public function setIdOrdonnance($id_ordonnance) {
        $this->id_ordonnance = $id_ordonnance;
    }
    public function setDateEmission($date_emission) {
        $this->date_emission = $date_emission;
    }
    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }
    public function setRefDossier($ref_dossier) {
        $this->ref_dossier = $ref_dossier;
    }
    public function setRefMedecin($ref_medecin) {
        $this->ref_medecin = $ref_medecin;
    }
}
?>
