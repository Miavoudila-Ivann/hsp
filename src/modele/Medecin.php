<?php

class Medecin {
    private $id_medecin;
    private $ref_specialite;
    private $ref_hopital;
    private $ref_etablissement;

    public function __construct($id_medecin, $ref_specialite, $ref_hopital, $ref_etablissement) {
        $this->id_medecin = $id_medecin;
        $this->ref_specialite = $ref_specialite;
        $this->ref_hopital = $ref_hopital;
        $this->ref_etablissement = $ref_etablissement;
    }

    // Getters
    public function getIdMedecin() {
        return $this->id_medecin;
    }

    public function getRefSpecialite() {
        return $this->ref_specialite;
    }

    public function getRefHopital() {
        return $this->ref_hopital;
    }

    public function getRefEtablissement() {
        return $this->ref_etablissement;
    }

    // Setters
    public function setIdMedecin($id_medecin) {
        $this->id_medecin = $id_medecin;
    }

    public function setRefSpecialite($ref_specialite) {
        $this->ref_specialite = $ref_specialite;
    }

    public function setRefHopital($ref_hopital) {
        $this->ref_hopital = $ref_hopital;
    }

    public function setRefEtablissement($ref_etablissement) {
        $this->ref_etablissement = $ref_etablissement;
    }
}
?>
