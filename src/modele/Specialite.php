<?php

class Specialite
{
    private $idSpecialite;
    private $libelle;

    /**
     * @param $idSpecialite
     * @param $libelle
     */
    public function __construct($idSpecialite, $libelle)
    {
        $this->idSpecialite = $idSpecialite;
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getIdSpecialite()
    {
        return $this->idSpecialite;
    }

    /**
     * @param mixed $idSpecialite
     */
    public function setIdSpecialite($idSpecialite)
    {
        $this->idSpecialite = $idSpecialite;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }
}
