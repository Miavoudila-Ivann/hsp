<?php

/**
 * Classe Ordonnance — Représente une ordonnance médicale émise par un médecin pour un patient.
 *
 * Une ordonnance est liée à un dossier de prise en charge et prescrite par un médecin.
 * Elle contient le détail des médicaments ou traitements prescrits ainsi que la date d'émission.
 */
class Ordonnance {

    /** @var mixed Identifiant unique de l'ordonnance */
    private $id_ordonnance;

    /** @var mixed Date à laquelle l'ordonnance a été émise par le médecin */
    private $date_emission;

    /** @var mixed Contenu textuel de l'ordonnance (médicaments, posologies, instructions) */
    private $contenu;

    /** @var mixed Clé étrangère vers le dossier de prise en charge auquel est rattachée l'ordonnance */
    private $ref_dossier;

    /** @var mixed Clé étrangère vers le médecin ayant rédigé l'ordonnance */
    private $ref_medecin;

    /**
     * Initialise une ordonnance avec ses informations médicales.
     *
     * @param mixed $id_ordonnance Identifiant de l'ordonnance
     * @param mixed $date_emission Date d'émission de l'ordonnance
     * @param mixed $contenu       Contenu de la prescription médicale
     * @param mixed $ref_dossier   Référence du dossier de prise en charge
     * @param mixed $ref_medecin   Référence du médecin prescripteur
     */
    public function __construct($id_ordonnance = null, $date_emission = null, $contenu = null, $ref_dossier = null, $ref_medecin = null) {
        $this->id_ordonnance = $id_ordonnance;
        $this->date_emission = $date_emission;
        $this->contenu = $contenu;
        $this->ref_dossier = $ref_dossier;
        $this->ref_medecin = $ref_medecin;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdOrdonnance() {
        return $this->id_ordonnance;
    }

    /** @return mixed */
    public function getDateEmission() {
        return $this->date_emission;
    }

    /** @return mixed */
    public function getContenu() {
        return $this->contenu;
    }

    /** @return mixed */
    public function getRefDossier() {
        return $this->ref_dossier;
    }

    /** @return mixed */
    public function getRefMedecin() {
        return $this->ref_medecin;
    }

    // --- Setters ---

    /** @param mixed $id_ordonnance */
    public function setIdOrdonnance($id_ordonnance) {
        $this->id_ordonnance = $id_ordonnance;
    }

    /** @param mixed $date_emission */
    public function setDateEmission($date_emission) {
        $this->date_emission = $date_emission;
    }

    /** @param mixed $contenu */
    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    /** @param mixed $ref_dossier */
    public function setRefDossier($ref_dossier) {
        $this->ref_dossier = $ref_dossier;
    }

    /** @param mixed $ref_medecin */
    public function setRefMedecin($ref_medecin) {
        $this->ref_medecin = $ref_medecin;
    }
}
?>
