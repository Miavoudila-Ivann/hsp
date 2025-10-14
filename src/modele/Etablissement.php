<?php

namespace modele;

class Etablissement
{
    private $id_etablissement;
    private $nom_etablissement;
    private $adresse_etablissement;
    private $site_web_etablissement;

    // Constructeur avec tableau associatif
    public function __construct(array $data = [])
    {
        if (isset($data['id_etablissement'])) $this->id_etablissement = $data['id_etablissement'];
        if (isset($data['nom_etablissement'])) $this->nom_etablissement = $data['nom_etablissement'];
        if (isset($data['adresse_etablissement'])) $this->adresse_etablissement = $data['adresse_etablissement'];
        if (isset($data['site_web_etablissement'])) $this->site_web_etablissement = $data['site_web_etablissement'];
    }

    // Getters & Setters
    public function getIdEtablissement()
    {
        return $this->id_etablissement;
    }

    public function setIdEtablissement($id_etablissement)
    {
        $this->id_etablissement = $id_etablissement;
    }

    public function getNomEtablissement()
    {
        return $this->nom_etablissement;
    }

    public function setNomEtablissement($nom_etablissement)
    {
        $this->nom_etablissement = $nom_etablissement;
    }

    public function getAdresseEtablissement()
    {
        return $this->adresse_etablissement;
    }

    public function setAdresseEtablissement($adresse_etablissement)
    {
        $this->adresse_etablissement = $adresse_etablissement;
    }

    public function getSiteWebEtablissement()
    {
        return $this->site_web_etablissement;
    }

    public function setSiteWebEtablissement($site_web_etablissement)
    {
        $this->site_web_etablissement = $site_web_etablissement;
    }
}
