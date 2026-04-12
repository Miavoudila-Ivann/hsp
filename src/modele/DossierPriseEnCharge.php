<?php

/**
 * Classe DossierPriseEnCharge — Représente le dossier d'admission d'un patient aux urgences.
 *
 * Créé par une secrétaire à l'arrivée d'un patient, ce dossier enregistre
 * les symptômes déclarés, le niveau de gravité et le statut de prise en charge.
 * Il sert de point d'entrée pour le parcours médical du patient à l'hôpital.
 */
class DossierPriseEnCharge {

    /** @var mixed Identifiant unique du dossier de prise en charge */
    private $id_dossier;

    /** @var mixed Date d'arrivée du patient à l'hôpital */
    private $date_arrivee;

    /** @var mixed Heure d'arrivée du patient à l'hôpital */
    private $heure_arrivee;

    /** @var mixed Description des symptômes rapportés par le patient à l'admission */
    private $symptomes;

    /** @var mixed Niveau de gravité de l'état du patient (ex : 'Faible', 'Modéré', 'Critique') */
    private $gravite;

    /** @var mixed Statut de traitement du dossier (ex : 'En attente', 'En cours', 'Clôturé') */
    private $statut;

    /** @var mixed Clé étrangère vers le patient concerné par ce dossier */
    private $ref_patient;

    /** @var mixed Clé étrangère vers la secrétaire ayant créé le dossier */
    private $ref_secretaire;

    /**
     * Initialise un dossier de prise en charge avec les données d'admission du patient.
     *
     * @param mixed $id_dossier      Identifiant du dossier
     * @param mixed $date_arrivee    Date d'arrivée du patient
     * @param mixed $heure_arrivee   Heure d'arrivée du patient
     * @param mixed $symptomes       Symptômes décrits à l'admission
     * @param mixed $gravite         Niveau de gravité évalué
     * @param mixed $statut          Statut de traitement du dossier
     * @param mixed $ref_patient     Référence du patient
     * @param mixed $ref_secretaire  Référence de la secrétaire créatrice du dossier
     */
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

    // --- Getters ---

    /** @return mixed */
    public function getIdDossier() {
        return $this->id_dossier;
    }

    /** @return mixed */
    public function getDateArrivee() {
        return $this->date_arrivee;
    }

    /** @return mixed */
    public function getHeureArrivee() {
        return $this->heure_arrivee;
    }

    /** @return mixed */
    public function getSymptomes() {
        return $this->symptomes;
    }

    /** @return mixed */
    public function getGravite() {
        return $this->gravite;
    }

    /** @return mixed */
    public function getStatut() {
        return $this->statut;
    }

    /** @return mixed */
    public function getRefPatient() {
        return $this->ref_patient;
    }

    /** @return mixed */
    public function getRefSecretaire() {
        return $this->ref_secretaire;
    }

    // --- Setters ---

    /** @param mixed $id_dossier */
    public function setIdDossier($id_dossier) {
        $this->id_dossier = $id_dossier;
    }

    /** @param mixed $date_arrivee */
    public function setDateArrivee($date_arrivee) {
        $this->date_arrivee = $date_arrivee;
    }

    /** @param mixed $heure_arrivee */
    public function setHeureArrivee($heure_arrivee) {
        $this->heure_arrivee = $heure_arrivee;
    }

    /** @param mixed $symptomes */
    public function setSymptomes($symptomes) {
        $this->symptomes = $symptomes;
    }

    /** @param mixed $gravite */
    public function setGravite($gravite) {
        $this->gravite = $gravite;
    }

    /** @param mixed $statut */
    public function setStatut($statut) {
        $this->statut = $statut;
    }

    /** @param mixed $ref_patient */
    public function setRefPatient($ref_patient) {
        $this->ref_patient = $ref_patient;
    }

    /** @param mixed $ref_secretaire */
    public function setRefSecretaire($ref_secretaire) {
        $this->ref_secretaire = $ref_secretaire;
    }
}
?>
