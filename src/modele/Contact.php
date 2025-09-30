<?php

class Contact
{
    private $id_contact;
    private $nom;
    private $status;
    private $sujet;
    private $message;

    public function __construct(array $data = [])
    {
        if (isset($data['id_contact'])) $this->id_contact = $data['id_contact'];
        if (isset($data['nom'])) $this->nom = $data['nom'];
        if (isset($data['email'])) $this->email = $data['email'];
        if (isset($data['sujet'])) $this->sujet = $data['sujet'];
        if (isset($data['message'])) $this->message = $data['message'];
    }

    public function getId_contact() {
        return $this->id_contact;
    }
    public function getNom() {
        return $this->nom;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getSujet() {
        return $this->sujet;
    }
    public function getMessage() {
        return $this->message;
    }


    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setSujet($sujet) {
        $this->sujet = $sujet;
    }
    public function setMessage($message) {
        $this->message = $message;
    }
}
