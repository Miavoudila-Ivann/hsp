<?php

namespace modele;

class Hopital
{
    private $id_hopital;
    private $nom;
    private $adresse_hopital;
    private $ville_hopital;

    public function __construct(array $data = [])
    {
        if (isset($data['id_hopital'])) $this->id_hopital = $data['id_hopital'];
        if (isset($data['nom'])) $this->nom = $data['nom'];
        if (isset($data['adresse_hopital'])) $this->adresse_hopital = $data['adresse_hopital'];
        if (isset($data['ville_hopital'])) $this->ville_hopital = $data['ville_hopital'];
    }
    /**
     * @return mixed
     */
    public function getIdHopital()
    {
        return $this->id_hopital;
    }

    /**
     * @param mixed $id_hopital
     */
    public function setIdHopital($id_hopital)
    {
        $this->id_hopital = $id_hopital;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getAdresseHopital()
    {
        return $this->adresse_hopital;
    }

    /**
     * @param mixed $adresse_hopital
     */
    public function setAdresseHopital($adresse_hopital)
    {
        $this->adresse_hopital = $adresse_hopital;
    }

    /**
     * @return mixed
     */
    public function getVilleHopital()
    {
        return $this->ville_hopital;
    }

    /**
     * @param mixed $ville_hopital
     */
    public function setVilleHopital($ville_hopital)
    {
        $this->ville_hopital = $ville_hopital;
    }
}