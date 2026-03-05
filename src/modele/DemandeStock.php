<?php
class DemandeStock {
    private $id_demande;
    private $quantite;
    private $statut;
    private $date_demande;
    private $ref_produit;
    private $ref_medecin;

    public function __construct($id_demande = null, $quantite = null, $statut = null, $date_demande = null, $ref_produit = null, $ref_medecin = null) {
        $this->id_demande = $id_demande;
        $this->quantite = $quantite;
        $this->statut = $statut;
        $this->date_demande = $date_demande;
        $this->ref_produit = $ref_produit;
        $this->ref_medecin = $ref_medecin;
    }

    public function getIdDemande() {
        return $this->id_demande;
    }
    public function getQuantite() {
        return $this->quantite;
    }
    public function getStatut() {
        return $this->statut;
    }
    public function getDateDemande() {
        return $this->date_demande;
    }
    public function getRefProduit() {
        return $this->ref_produit;
    }
    public function getRefMedecin() {
        return $this->ref_medecin;
    }

    public function setIdDemande($id_demande) {
        $this->id_demande = $id_demande;
    }
    public function setQuantite($quantite) {
        $this->quantite = $quantite;
    }
    public function setStatut($statut) {
        $this->statut = $statut;
    }
    public function setDateDemande($date_demande) {
        $this->date_demande = $date_demande;
    }
    public function setRefProduit($ref_produit) {
        $this->ref_produit = $ref_produit;
    }
    public function setRefMedecin($ref_medecin) {
        $this->ref_medecin = $ref_medecin;
    }
}
?>
