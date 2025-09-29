<?php

class Entreprise
{
    private $id;
    private $nom;
    private $adresse;
    private $siteWeb;

    public function __construct(array $data = [])
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['nom'])) {
            $this->nom = $data['nom'];
        }
        if (isset($data['adresse'])) {
            $this->adresse = $data['adresse'];
        }
        if (isset($data['site_web'])) {
            $this->siteWeb = $data['site_web'];
        }
    }

    // --- Getters ---
    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function getSiteWeb()
    {
        return $this->siteWeb;
    }

    // --- Setters ---
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    public function setSiteWeb($siteWeb)
    {
        $this->siteWeb = $siteWeb;
    }
}
