<?php

/**
 * Classe Medecin — Représente un médecin exerçant au sein du réseau hospitalier.
 *
 * Un médecin est rattaché à une spécialité médicale, à un hôpital et à un établissement.
 * Cette classe est complémentaire à Utilisateur : le médecin hérite de son compte utilisateur
 * et possède en plus des références vers ses affectations médicales.
 */
class Medecin {

    /** @var mixed Identifiant unique du médecin (correspond à l'id de l'utilisateur associé) */
    private $id_medecin;

    /** @var mixed Clé étrangère vers la spécialité médicale du médecin */
    private $ref_specialite;

    /** @var mixed Clé étrangère vers l'hôpital principal d'exercice du médecin */
    private $ref_hopital;

    /** @var mixed Clé étrangère vers l'établissement secondaire d'exercice du médecin */
    private $ref_etablissement;

    /**
     * Initialise un médecin avec ses références d'affectation.
     *
     * @param mixed $ref_specialite    Référence de la spécialité médicale
     * @param mixed $ref_hopital       Référence de l'hôpital d'exercice
     * @param mixed $ref_etablissement Référence de l'établissement d'exercice
     * @param mixed $id_medecin        Identifiant du médecin (optionnel, null avant insertion)
     */
    public function __construct($ref_specialite, $ref_hopital, $ref_etablissement, $id_medecin = null) {
        $this->id_medecin = $id_medecin;
        $this->ref_specialite = $ref_specialite;
        $this->ref_hopital = $ref_hopital;
        $this->ref_etablissement = $ref_etablissement;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdMedecin() { return $this->id_medecin; }

    /** @return mixed */
    public function getRefSpecialite() { return $this->ref_specialite; }

    /** @return mixed */
    public function getRefHopital() { return $this->ref_hopital; }

    /** @return mixed */
    public function getRefEtablissement() { return $this->ref_etablissement; }

    // --- Setters ---

    /** @param mixed $id */
    public function setIdMedecin($id) { $this->id_medecin = $id; }

    /** @param mixed $ref */
    public function setRefSpecialite($ref) { $this->ref_specialite = $ref; }

    /** @param mixed $ref */
    public function setRefHopital($ref) { $this->ref_hopital = $ref; }

    /** @param mixed $ref */
    public function setRefEtablissement($ref) { $this->ref_etablissement = $ref; }
}
?>
