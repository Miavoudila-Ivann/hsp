<?php

class Candidature
{
    private ?int $id_candidature;
    private string $motivation;
    private string $statut;
    private string $date_candidature;
    private int $ref_offre;
    private int $ref_utilisateur;

    public function __construct($id_candidature, $motivation, $statut, $date_candidature, $ref_offre, $ref_utilisateur)
    {
        $this->id_candidature = $id_candidature;
        $this->motivation = $motivation;
        $this->statut = $statut;
        $this->date_candidature = $date_candidature;
        $this->ref_offre = $ref_offre;
        $this->ref_utilisateur = $ref_utilisateur;
    }

    public function getRef_offre() {
        return $this->ref_offre;
    }
    public function getId_andidature() {
        return $this->id_candidature;
    }
    public function getRef_utilisateur() {
        return $this->ref_utilisateur;
    }
    public function getStatut(){
      return $this->statut;
    }

    public function getMotivation() {
        return $this->motivation;
    }


    public function setRef_offre($ref_offre) {
        $this->idRef_offre = $ref_offre;
    }
    public function setIdCandidature($idCandidature) {
        $this->idCandidature = $idCandidature;
    }
    public function setRef_utilisateur($ref_utilisateur) {
        $this->ref_utilisateur = $ref_utilisateur;
    }
    public function setStatut($statut) {
        $this->statut = $statut;
    }
    public function setMotivation($motivation) {
        $this->motivation = $motivation;
    }
}
