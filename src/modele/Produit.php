<?php
class Produit {
    private $id_produit;
    private $libelle;
    private $description;
    private $dangerosite;
    private $stock_actuel;

    public function __construct($id_produit = null, $libelle = null, $description = null, $dangerosite = null, $stock_actuel = null) {
        $this->id_produit = $id_produit;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->dangerosite = $dangerosite;
        $this->stock_actuel = $stock_actuel;
    }

    public function getIdProduit() {
        return $this->id_produit;
    }
    public function getLibelle() {
        return $this->libelle;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getDangerosite() {
        return $this->dangerosite;
    }
    public function getStockActuel() {
        return $this->stock_actuel;
    }

    public function setIdProduit($id_produit) {
        $this->id_produit = $id_produit;
    }
    public function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setDangerosite($dangerosite) {
        $this->dangerosite = $dangerosite;
    }
    public function setStockActuel($stock_actuel) {
        $this->stock_actuel = $stock_actuel;
    }
}
?>
