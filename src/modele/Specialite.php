<?php

/**
 * Classe Specialite — Représente une spécialité médicale exercée par des médecins de l'hôpital.
 *
 * Chaque médecin est rattaché à une spécialité (ex : 'Cardiologie', 'Pédiatrie', 'Urgences').
 * Cette classe sert de référentiel pour catégoriser les médecins et orienter les patients.
 */
class Specialite {

    /** @var mixed Identifiant unique de la spécialité médicale */
    private $id_specialite;

    /** @var mixed Intitulé de la spécialité médicale (ex : 'Cardiologie', 'Chirurgie') */
    private $libelle;

    /**
     * Initialise une spécialité médicale avec son libellé.
     *
     * @param mixed $libelle      Intitulé de la spécialité
     * @param mixed $id_specialite Identifiant de la spécialité (optionnel, null avant insertion)
     */
    public function __construct($libelle, $id_specialite = null) {
        $this->id_specialite = $id_specialite;
        $this->libelle = $libelle;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdSpecialite() { return $this->id_specialite; }

    /** @return mixed */
    public function getLibelle() { return $this->libelle; }

    // --- Setters ---

    /** @param mixed $id */
    public function setIdSpecialite($id) { $this->id_specialite = $id; }

    /** @param mixed $libelle */
    public function setLibelle($libelle) { $this->libelle = $libelle; }
}
?>
