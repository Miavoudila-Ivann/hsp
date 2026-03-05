<?php
class Fournisseur {
    private $id_fournisseur;
    private $nom;
    private $contact;
    private $email;

    public function __construct($id_fournisseur = null, $nom = null, $contact = null, $email = null) {
        $this->id_fournisseur = $id_fournisseur;
        $this->nom = $nom;
        $this->contact = $contact;
        $this->email = $email;
    }

    public function getIdFournisseur() {
        return $this->id_fournisseur;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getContact() {
        return $this->contact;
    }
    public function getEmail() {
        return $this->email;
    }

    public function setIdFournisseur($id_fournisseur) {
        $this->id_fournisseur = $id_fournisseur;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setContact($contact) {
        $this->contact = $contact;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
}
?>
