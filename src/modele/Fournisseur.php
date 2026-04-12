<?php

/**
 * Classe Fournisseur — Représente un fournisseur de produits médicaux ou de matériel hospitalier.
 *
 * Un fournisseur est identifié par son nom, une personne de contact et une adresse e-mail.
 * Il est lié aux produits qu'il fournit à l'hôpital dans le cadre de la gestion des stocks.
 */
class Fournisseur {

    /** @var mixed Identifiant unique du fournisseur */
    private $id_fournisseur;

    /** @var mixed Raison sociale ou nom commercial du fournisseur */
    private $nom;

    /** @var mixed Nom ou coordonnées de la personne de contact chez le fournisseur */
    private $contact;

    /** @var mixed Adresse e-mail professionnelle du fournisseur */
    private $email;

    /**
     * Initialise un fournisseur avec ses informations de contact.
     *
     * @param mixed $id_fournisseur Identifiant du fournisseur
     * @param mixed $nom            Nom du fournisseur
     * @param mixed $contact        Personne de contact
     * @param mixed $email          Adresse e-mail du fournisseur
     */
    public function __construct($id_fournisseur = null, $nom = null, $contact = null, $email = null) {
        $this->id_fournisseur = $id_fournisseur;
        $this->nom = $nom;
        $this->contact = $contact;
        $this->email = $email;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdFournisseur() {
        return $this->id_fournisseur;
    }

    /** @return mixed */
    public function getNom() {
        return $this->nom;
    }

    /** @return mixed */
    public function getContact() {
        return $this->contact;
    }

    /** @return mixed */
    public function getEmail() {
        return $this->email;
    }

    // --- Setters ---

    /** @param mixed $id_fournisseur */
    public function setIdFournisseur($id_fournisseur) {
        $this->id_fournisseur = $id_fournisseur;
    }

    /** @param mixed $nom */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /** @param mixed $contact */
    public function setContact($contact) {
        $this->contact = $contact;
    }

    /** @param mixed $email */
    public function setEmail($email) {
        $this->email = $email;
    }
}
?>
