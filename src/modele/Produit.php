<?php

/**
 * Classe Produit — Représente un produit médical ou pharmaceutique géré dans le stock de l'hôpital.
 *
 * Un produit est caractérisé par son libellé, sa description, son niveau de dangerosité
 * et son stock actuel. Les médecins peuvent émettre des demandes de stock pour ces produits.
 */
class Produit {

    /** @var mixed Identifiant unique du produit dans la base de données */
    private $id_produit;

    /** @var mixed Nom court ou code du produit (ex : 'Paracétamol 500mg') */
    private $libelle;

    /** @var mixed Description détaillée du produit (usage, composition, précautions) */
    private $description;

    /** @var mixed Niveau de dangerosité du produit (ex : 'Faible', 'Modéré', 'Élevé') */
    private $dangerosite;

    /** @var mixed Quantité disponible actuellement en stock */
    private $stock_actuel;

    /**
     * Initialise un produit médical avec ses caractéristiques et son état de stock.
     *
     * @param mixed $id_produit   Identifiant du produit
     * @param mixed $libelle      Nom ou libellé du produit
     * @param mixed $description  Description détaillée
     * @param mixed $dangerosite  Niveau de dangerosité
     * @param mixed $stock_actuel Quantité en stock actuellement disponible
     */
    public function __construct($id_produit = null, $libelle = null, $description = null, $dangerosite = null, $stock_actuel = null) {
        $this->id_produit = $id_produit;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->dangerosite = $dangerosite;
        $this->stock_actuel = $stock_actuel;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdProduit() {
        return $this->id_produit;
    }

    /** @return mixed */
    public function getLibelle() {
        return $this->libelle;
    }

    /** @return mixed */
    public function getDescription() {
        return $this->description;
    }

    /** @return mixed */
    public function getDangerosite() {
        return $this->dangerosite;
    }

    /** @return mixed */
    public function getStockActuel() {
        return $this->stock_actuel;
    }

    // --- Setters ---

    /** @param mixed $id_produit */
    public function setIdProduit($id_produit) {
        $this->id_produit = $id_produit;
    }

    /** @param mixed $libelle */
    public function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    /** @param mixed $description */
    public function setDescription($description) {
        $this->description = $description;
    }

    /** @param mixed $dangerosite */
    public function setDangerosite($dangerosite) {
        $this->dangerosite = $dangerosite;
    }

    /** @param mixed $stock_actuel */
    public function setStockActuel($stock_actuel) {
        $this->stock_actuel = $stock_actuel;
    }
}
?>
