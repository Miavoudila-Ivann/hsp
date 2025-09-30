<?php

class Candidature
{
    private $idCandidature;
    private $status;
    private $idRef_offre;
    private $idRef_utilisateur;
    private $motivation;

    public function __construct(array $data = [])
    {
        if (isset($data['idRef_offre'])) $this->idRef_offre = $data['idRef_offre'];
        if (isset($data['id_candidature'])) $this->idCandidature = $data['id_candidature'];
        if (isset($data['idRef_utilisateur'])) $this->idRef_utilisateur = $data['idRef_utilisateur'];
        if (isset($data['statut'])) $this->status = $data['statut'];
        if (isset($data['motivation'])) $this->motivation = $data['motivation'];
    }

    public function getIdRef_offre() {
        return $this->idRef_offre;
    }
    public function getIdCandidature() {
        return $this->idCandidature;
    }
    public function getIdRef_utilisateur() {
        return $this->idRef_utilisateur;
    }
    public function getStatut(){
      return $this->statut;
    }

    public function getMotivation() {
        return $this->motivation;
    }


    public function setIdRef_offre($idRef_offre) {
        $this->idRef_offre = $idRef_offre;
    }
    public function setIdCandidature($idCandidature) {
        $this->idOffre = $idCandidature;
    }
    public function setIdRef_utilisateur($idRef_utilisateur) {
        $this->idOffre = $idRef_utilisateur;
    }
    public function setStatut($statut) {
        $this->statut = $statut;
    }
    public function setMotivation($motivation) {
        $this->motivation = $motivation;
    }
}
