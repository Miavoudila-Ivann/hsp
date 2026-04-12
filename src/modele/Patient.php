<?php

/**
 * Classe Patient — Représente un patient pris en charge par l'hôpital.
 *
 * Un patient est identifié par ses informations personnelles et son numéro de sécurité sociale.
 * Il est au centre du parcours de soins : dossiers de prise en charge, hospitalisations
 * et ordonnances lui sont directement rattachés.
 */
class Patient {

    /** @var mixed Identifiant unique du patient dans le système hospitalier */
    private $id_patient;

    /** @var mixed Nom de famille du patient */
    private $nom;

    /** @var mixed Prénom du patient */
    private $prenom;

    /** @var mixed Numéro de sécurité sociale du patient (identifiant unique national) */
    private $num_secu;

    /** @var mixed Adresse e-mail du patient pour les communications */
    private $email;

    /** @var mixed Numéro de téléphone du patient */
    private $telephone;

    /** @var mixed Adresse postale complète du patient */
    private $adresse;

    /**
     * Initialise un patient avec ses informations personnelles et de contact.
     *
     * @param mixed $id_patient Identifiant du patient
     * @param mixed $nom        Nom de famille
     * @param mixed $prenom     Prénom
     * @param mixed $num_secu   Numéro de sécurité sociale
     * @param mixed $email      Adresse e-mail
     * @param mixed $telephone  Numéro de téléphone
     * @param mixed $adresse    Adresse postale
     */
    public function __construct($id_patient = null, $nom = null, $prenom = null, $num_secu = null, $email = null, $telephone = null, $adresse = null) {
        $this->id_patient = $id_patient;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->num_secu = $num_secu;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdPatient() {
        return $this->id_patient;
    }

    /** @return mixed */
    public function getNom() {
        return $this->nom;
    }

    /** @return mixed */
    public function getPrenom() {
        return $this->prenom;
    }

    /** @return mixed */
    public function getNumSecu() {
        return $this->num_secu;
    }

    /** @return mixed */
    public function getEmail() {
        return $this->email;
    }

    /** @return mixed */
    public function getTelephone() {
        return $this->telephone;
    }

    /** @return mixed */
    public function getAdresse() {
        return $this->adresse;
    }

    // --- Setters ---

    /** @param mixed $id_patient */
    public function setIdPatient($id_patient) {
        $this->id_patient = $id_patient;
    }

    /** @param mixed $nom */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /** @param mixed $prenom */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    /** @param mixed $num_secu */
    public function setNumSecu($num_secu) {
        $this->num_secu = $num_secu;
    }

    /** @param mixed $email */
    public function setEmail($email) {
        $this->email = $email;
    }

    /** @param mixed $telephone */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    /** @param mixed $adresse */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }
}
?>
