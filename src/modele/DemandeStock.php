<?php

/**
 * Classe DemandeStock — Représente une demande de réapprovisionnement en stock de produits médicaux.
 *
 * Initiée par un médecin pour un produit donné, elle précise la quantité souhaitée
 * et suit un cycle de vie via son statut (en attente, validée, refusée).
 */
class DemandeStock {

    /** @var mixed Identifiant unique de la demande de stock */
    private $id_demande;

    /** @var mixed Quantité de produit demandée */
    private $quantite;

    /** @var mixed Statut de la demande (ex : 'En attente', 'Validée', 'Refusée') */
    private $statut;

    /** @var mixed Date à laquelle la demande a été émise */
    private $date_demande;

    /** @var mixed Clé étrangère vers le produit médical concerné */
    private $ref_produit;

    /** @var mixed Clé étrangère vers le médecin ayant émis la demande */
    private $ref_medecin;

    /**
     * Initialise une demande de stock avec ses informations complètes.
     *
     * @param mixed $id_demande   Identifiant de la demande
     * @param mixed $quantite     Quantité demandée
     * @param mixed $statut       Statut de traitement de la demande
     * @param mixed $date_demande Date d'émission de la demande
     * @param mixed $ref_produit  Référence du produit concerné
     * @param mixed $ref_medecin  Référence du médecin demandeur
     */
    public function __construct($id_demande = null, $quantite = null, $statut = null, $date_demande = null, $ref_produit = null, $ref_medecin = null) {
        $this->id_demande = $id_demande;
        $this->quantite = $quantite;
        $this->statut = $statut;
        $this->date_demande = $date_demande;
        $this->ref_produit = $ref_produit;
        $this->ref_medecin = $ref_medecin;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdDemande() {
        return $this->id_demande;
    }

    /** @return mixed */
    public function getQuantite() {
        return $this->quantite;
    }

    /** @return mixed */
    public function getStatut() {
        return $this->statut;
    }

    /** @return mixed */
    public function getDateDemande() {
        return $this->date_demande;
    }

    /** @return mixed */
    public function getRefProduit() {
        return $this->ref_produit;
    }

    /** @return mixed */
    public function getRefMedecin() {
        return $this->ref_medecin;
    }

    // --- Setters ---

    /** @param mixed $id_demande */
    public function setIdDemande($id_demande) {
        $this->id_demande = $id_demande;
    }

    /** @param mixed $quantite */
    public function setQuantite($quantite) {
        $this->quantite = $quantite;
    }

    /** @param mixed $statut */
    public function setStatut($statut) {
        $this->statut = $statut;
    }

    /** @param mixed $date_demande */
    public function setDateDemande($date_demande) {
        $this->date_demande = $date_demande;
    }

    /** @param mixed $ref_produit */
    public function setRefProduit($ref_produit) {
        $this->ref_produit = $ref_produit;
    }

    /** @param mixed $ref_medecin */
    public function setRefMedecin($ref_medecin) {
        $this->ref_medecin = $ref_medecin;
    }
}
?>
