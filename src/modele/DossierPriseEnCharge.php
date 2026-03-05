<?php
class DossierPriseEnCharge {
    private $id_dossier;
    private $date_arrivee;
    private $heure_arrivee;
    private $symptomes;
    private $gravite;
    private $statut;
    private $ref_patient;
    private $ref_secretaire;

    public function __construct($id_dossier = null, $date_arrivee = null, $heure_arrivee = null, $symptomes = null, $gravite = null, $statut = null, $ref_patient = null, $ref_secretaire = null) {
        $this->id_dossier = $id_dossier;
        $this->date_arrivee = $date_arrivee;
        $this->heure_arrivee = $heure_arrivee;
        $this->symptomes = $symptomes;
        $this->gravite = $gravite;
        $this->statut = $statut;
        $this->ref_patient = $ref_patient;
        $this->ref_secretaire = $ref_secretaire;
    }

    public function getIdDossier() {
        return $this->id_dossier;
    }
    public function getDateArrivee() {
        return $this->date_arrivee;
    }
    public function getHeureArrivee() {
        return $this->heure_arrivee;
    }
    public function getSymptomes() {
        return $this->symptomes;
    }
    public function getGravite() {
        return $this->gravite;
    }
    public function getStatut() {
        return $this->statut;
    }
    public function getRefPatient() {
        return $this->ref_patient;
    }
    public function getRefSecretaire() {
        return $this->ref_secretaire;
    }

    public function setIdDossier($id_dossier) {
        $this->id_dossier = $id_dossier;
    }
    public function setDateArrivee($date_arrivee) {
        $this->date_arrivee = $date_arrivee;
    }
    public function setHeureArrivee($heure_arrivee) {
        $this->heure_arrivee = $heure_arrivee;
    }
    public function setSymptomes($symptomes) {
        $this->symptomes = $symptomes;
    }
    public function setGravite($gravite) {
        $this->gravite = $gravite;
    }
    public function setStatut($statut) {
        $this->statut = $statut;
    }
    public function setRefPatient($ref_patient) {
        $this->ref_patient = $ref_patient;
    }
    public function setRefSecretaire($ref_secretaire) {
        $this->ref_secretaire = $ref_secretaire;
    }
}
?>
