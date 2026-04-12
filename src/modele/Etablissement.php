<?php

namespace modele;

/**
 * Classe Etablissement — Représente un établissement médical (clinique, cabinet, etc.) lié à l'hôpital.
 *
 * Un établissement peut être associé à des médecins exerçant en dehors de l'hôpital principal.
 * Le constructeur accepte un tableau associatif pour faciliter l'hydratation depuis la base de données.
 */
class Etablissement
{
    /** @var mixed Identifiant unique de l'établissement */
    private $id_etablissement;

    /** @var mixed Nom officiel de l'établissement */
    private $nom_etablissement;

    /** @var mixed Adresse postale complète de l'établissement */
    private $adresse_etablissement;

    /** @var mixed URL du site web de l'établissement (optionnel) */
    private $site_web_etablissement;

    /**
     * Initialise un établissement à partir d'un tableau associatif de données.
     *
     * Seules les clés présentes dans le tableau sont affectées aux propriétés correspondantes.
     *
     * @param array $data Tableau associatif issu de la base de données
     */
    public function __construct(array $data = [])
    {
        if (isset($data['id_etablissement'])) $this->id_etablissement = $data['id_etablissement'];
        if (isset($data['nom_etablissement'])) $this->nom_etablissement = $data['nom_etablissement'];
        if (isset($data['adresse_etablissement'])) $this->adresse_etablissement = $data['adresse_etablissement'];
        if (isset($data['site_web_etablissement'])) $this->site_web_etablissement = $data['site_web_etablissement'];
    }

    // --- Getters & Setters ---

    /** @return mixed */
    public function getIdEtablissement()
    {
        return $this->id_etablissement;
    }

    /** @param mixed $id_etablissement */
    public function setIdEtablissement($id_etablissement)
    {
        $this->id_etablissement = $id_etablissement;
    }

    /** @return mixed */
    public function getNomEtablissement()
    {
        return $this->nom_etablissement;
    }

    /** @param mixed $nom_etablissement */
    public function setNomEtablissement($nom_etablissement)
    {
        $this->nom_etablissement = $nom_etablissement;
    }

    /** @return mixed */
    public function getAdresseEtablissement()
    {
        return $this->adresse_etablissement;
    }

    /** @param mixed $adresse_etablissement */
    public function setAdresseEtablissement($adresse_etablissement)
    {
        $this->adresse_etablissement = $adresse_etablissement;
    }

    /** @return mixed */
    public function getSiteWebEtablissement()
    {
        return $this->site_web_etablissement;
    }

    /** @param mixed $site_web_etablissement */
    public function setSiteWebEtablissement($site_web_etablissement)
    {
        $this->site_web_etablissement = $site_web_etablissement;
    }
}
