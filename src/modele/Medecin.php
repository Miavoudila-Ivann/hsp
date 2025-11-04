<?php
class Medecin {
    private $id_medecin;
    private $ref_specialite;
    private $ref_hopital;
    private $ref_etablissement;

    public function __construct($ref_specialite, $ref_hopital, $ref_etablissement, $id_medecin = null) {
        $this->id_medecin = $id_medecin;
        $this->ref_specialite = $ref_specialite;
        $this->ref_hopital = $ref_hopital;
        $this->ref_etablissement = $ref_etablissement;
    }

    public function getIdMedecin() { return $this->id_medecin; }
    public function getRefSpecialite() { return $this->ref_specialite; }
    public function getRefHopital() { return $this->ref_hopital; }
    public function getRefEtablissement() { return $this->ref_etablissement; }

    public function setIdMedecin($id) { $this->id_medecin = $id; }
    public function setRefSpecialite($ref) { $this->ref_specialite = $ref; }
    public function setRefHopital($ref) { $this->ref_hopital = $ref; }
    public function setRefEtablissement($ref) { $this->ref_etablissement = $ref; }
}
?>