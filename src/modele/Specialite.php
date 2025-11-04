<?php
class Specialite {
    private $id_specialite;
    private $libelle;

    public function __construct($libelle, $id_specialite = null) {
        $this->id_specialite = $id_specialite;
        $this->libelle = $libelle;
    }

    public function getIdSpecialite() { return $this->id_specialite; }
    public function getLibelle() { return $this->libelle; }

    public function setIdSpecialite($id) { $this->id_specialite = $id; }
    public function setLibelle($libelle) { $this->libelle = $libelle; }
}
?>