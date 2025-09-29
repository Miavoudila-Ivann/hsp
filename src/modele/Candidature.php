<?php

class Candidature
{
    private $id;
    private $idUtilisateur;
    private $idOffre;
    private $motivation;

    public function __construct(array $data = [])
    {
        if (isset($data['id'])) $this->id = $data['id'];
        if (isset($data['id_utilisateur'])) $this->idUtilisateur = $data['id_utilisateur'];
        if (isset($data['id_offre'])) $this->idOffre = $data['id_offre'];
        if (isset($data['motivation'])) $this->motivation = $data['motivation'];
    }

    public function getId() {
        return $this->id;
    }
    public function getIdUtilisateur() {
        return $this->idUtilisateur;
    }
    public function getIdOffre() {
        return $this->idOffre;
    }
    public function getMotivation() {
        return $this->motivation;
    }


    public function setIdUtilisateur($idUtilisateur) {
        $this->idUtilisateur = $idUtilisateur;
    }
    public function setIdOffre($idOffre) {
        $this->idOffre = $idOffre;
    }
    public function setMotivation($motivation) {
        $this->motivation = $motivation;
    }
}
