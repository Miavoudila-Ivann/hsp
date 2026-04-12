<?php

/**
 * Classe Chambre — Représente une chambre au sein d'un hôpital.
 *
 * Modélise une chambre identifiée par son numéro et rattachée à un hôpital.
 * Le champ disponibilité indique si la chambre peut accueillir un nouveau patient.
 */
class Chambre {

    /** @var mixed Identifiant unique de la chambre */
    private $id_chambre;

    /** @var mixed Numéro de la chambre dans l'hôpital */
    private $numero;

    /** @var mixed Disponibilité de la chambre (1 = disponible, 0 = occupée) */
    private $disponible;

    /** @var mixed Clé étrangère vers l'hôpital auquel appartient la chambre */
    private $ref_hopital;

    /**
     * Initialise une chambre avec ses informations de base.
     *
     * @param mixed $id_chambre  Identifiant de la chambre
     * @param mixed $numero      Numéro de la chambre
     * @param mixed $disponible  Indicateur de disponibilité
     * @param mixed $ref_hopital Référence de l'hôpital associé
     */
    public function __construct($id_chambre = null, $numero = null, $disponible = null, $ref_hopital = null) {
        $this->id_chambre = $id_chambre;
        $this->numero = $numero;
        $this->disponible = $disponible;
        $this->ref_hopital = $ref_hopital;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdChambre() {
        return $this->id_chambre;
    }

    /** @return mixed */
    public function getNumero() {
        return $this->numero;
    }

    /** @return mixed */
    public function getDisponible() {
        return $this->disponible;
    }

    /** @return mixed */
    public function getRefHopital() {
        return $this->ref_hopital;
    }

    // --- Setters ---

    /** @param mixed $id_chambre */
    public function setIdChambre($id_chambre) {
        $this->id_chambre = $id_chambre;
    }

    /** @param mixed $numero */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /** @param mixed $disponible */
    public function setDisponible($disponible) {
        $this->disponible = $disponible;
    }

    /** @param mixed $ref_hopital */
    public function setRefHopital($ref_hopital) {
        $this->ref_hopital = $ref_hopital;
    }
}
?>
