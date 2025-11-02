<?php
class Contrat
{
    private $idContrat;
    private $idCandidature;
    private $dateDebut;
    private $dateFin;
    private $poste;
    private $salaire;

    public function getIdContrat() { return $this->idContrat; }
    public function setIdContrat($id) { $this->idContrat = $id; }

    public function getIdCandidature() { return $this->idCandidature; }
    public function setIdCandidature($id) { $this->idCandidature = $id; }

    public function getDateDebut() { return $this->dateDebut; }
    public function setDateDebut($d) { $this->dateDebut = $d; }

    public function getDateFin() { return $this->dateFin; }
    public function setDateFin($d) { $this->dateFin = $d; }

    public function getPoste() { return $this->poste; }
    public function setPoste($p) { $this->poste = $p; }

    public function getSalaire() { return $this->salaire; }
    public function setSalaire($s) { $this->salaire = $s; }
}
?>
