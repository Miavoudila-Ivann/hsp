<?php
class Chambre {
    private $id_chambre;
    private $numero;
    private $disponible;
    private $ref_hopital;

    public function __construct($id_chambre = null, $numero = null, $disponible = null, $ref_hopital = null) {
        $this->id_chambre = $id_chambre;
        $this->numero = $numero;
        $this->disponible = $disponible;
        $this->ref_hopital = $ref_hopital;
    }

    public function getIdChambre() {
        return $this->id_chambre;
    }
    public function getNumero() {
        return $this->numero;
    }
    public function getDisponible() {
        return $this->disponible;
    }
    public function getRefHopital() {
        return $this->ref_hopital;
    }

    public function setIdChambre($id_chambre) {
        $this->id_chambre = $id_chambre;
    }
    public function setNumero($numero) {
        $this->numero = $numero;
    }
    public function setDisponible($disponible) {
        $this->disponible = $disponible;
    }
    public function setRefHopital($ref_hopital) {
        $this->ref_hopital = $ref_hopital;
    }
}
?>
