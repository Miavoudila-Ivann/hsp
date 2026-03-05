<?php
class Patient {
    private $id_patient;
    private $nom;
    private $prenom;
    private $num_secu;
    private $email;
    private $telephone;
    private $adresse;

    public function __construct($id_patient = null, $nom = null, $prenom = null, $num_secu = null, $email = null, $telephone = null, $adresse = null) {
        $this->id_patient = $id_patient;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->num_secu = $num_secu;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
    }

    public function getIdPatient() {
        return $this->id_patient;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getPrenom() {
        return $this->prenom;
    }
    public function getNumSecu() {
        return $this->num_secu;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getTelephone() {
        return $this->telephone;
    }
    public function getAdresse() {
        return $this->adresse;
    }

    public function setIdPatient($id_patient) {
        $this->id_patient = $id_patient;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    public function setNumSecu($num_secu) {
        $this->num_secu = $num_secu;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }
    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }
}
?>
