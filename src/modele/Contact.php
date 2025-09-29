<?php

class Contact
{
    private $id;
    private $nom;
    private $email;
    private $sujet;
    private $message;

    public function __construct(array $data = [])
    {
        if (isset($data['id'])) $this->id = $data['id'];
        if (isset($data['nom'])) $this->nom = $data['nom'];
        if (isset($data['email'])) $this->email = $data['email'];
        if (isset($data['sujet'])) $this->sujet = $data['sujet'];
        if (isset($data['message'])) $this->message = $data['message'];
    }

    // --- Getters ---
    public function getId() {
        return $this->id;
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
