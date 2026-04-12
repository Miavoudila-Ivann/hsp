<?php

/**
 * Classe Contrat — Représente un contrat de travail généré à la suite d'une candidature acceptée.
 *
 * Lie une candidature retenue à un poste, une période d'emploi et une rémunération.
 * Utilisé pour formaliser l'embauche d'un personnel médical ou administratif.
 */
class Contrat
{
    /** @var mixed Identifiant unique du contrat */
    private $idContrat;

    /** @var mixed Clé étrangère vers la candidature ayant abouti à ce contrat */
    private $idCandidature;

    /** @var mixed Date de début de la période contractuelle */
    private $dateDebut;

    /** @var mixed Date de fin de la période contractuelle (null pour un CDI) */
    private $dateFin;

    /** @var mixed Intitulé du poste occupé dans le cadre du contrat */
    private $poste;

    /** @var mixed Salaire brut mensuel associé au contrat */
    private $salaire;

    // --- Getters & Setters ---

    /** @return mixed */
    public function getIdContrat() { return $this->idContrat; }

    /** @param mixed $id */
    public function setIdContrat($id) { $this->idContrat = $id; }

    /** @return mixed */
    public function getIdCandidature() { return $this->idCandidature; }

    /** @param mixed $id */
    public function setIdCandidature($id) { $this->idCandidature = $id; }

    /** @return mixed */
    public function getDateDebut() { return $this->dateDebut; }

    /** @param mixed $d */
    public function setDateDebut($d) { $this->dateDebut = $d; }

    /** @return mixed */
    public function getDateFin() { return $this->dateFin; }

    /** @param mixed $d */
    public function setDateFin($d) { $this->dateFin = $d; }

    /** @return mixed */
    public function getPoste() { return $this->poste; }

    /** @param mixed $p */
    public function setPoste($p) { $this->poste = $p; }

    /** @return mixed */
    public function getSalaire() { return $this->salaire; }

    /** @param mixed $s */
    public function setSalaire($s) { $this->salaire = $s; }
}
?>
