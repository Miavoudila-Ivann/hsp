<?php

namespace modele;

class Etablissement
{
    private $id_etablissement;

    /**
     * @return mixed
     */
    public function getIdEtablissement()
    {
        return $this->id_etablissement;
    }

    /**
     * @param mixed $id_etablissement
     */
    public function setIdEtablissement($id_etablissement)
    {
        $this->id_etablissement = $id_etablissement;
    }

    /**
     * @return mixed
     */
    public function getNomEtablissemnet()
    {
        return $this->nom_etablissemnet;
    }

    /**
     * @param mixed $nom_etablissemnet
     */
    public function setNomEtablissemnet($nom_etablissemnet)
    {
        $this->nom_etablissemnet = $nom_etablissemnet;
    }

    /**
     * @return mixed
     */
    public function getAdresseEtablissement()
    {
        return $this->adresse_etablissement;
    }

    /**
     * @param mixed $adresse_etablissement
     */
    public function setAdresseEtablissement($adresse_etablissement)
    {
        $this->adresse_etablissement = $adresse_etablissement;
    }

    /**
     * @return mixed
     */
    public function getSiteWebEtablissement()
    {
        return $this->site_web_etablissement;
    }

    /**
     * @param mixed $site_web_etablissement
     */
    public function setSiteWebEtablissement($site_web_etablissement)
    {
        $this->site_web_etablissement = $site_web_etablissement;
    }
    private $nom_etablissemnet;
    private $adresse_etablissement;
    private $site_web_etablissement;

    public function __construct(array $data = [])
    {
        if (isset($data['id_etablissement'])) $this->id_etablissement = $data['id_etablissement'];
        if (isset($data['nom_etablissement'])) $this->nom_etablissemnet = $data['nom_etablissement'];
        if (isset($data['adresse_etablissement'])) $this->adresse_etablissement = $data['adresse_etablissement'];
        if (isset($data['site_web_etablissement'])) $this->site_web_etablissement = $data['site_web_etablissement'];
    }
}