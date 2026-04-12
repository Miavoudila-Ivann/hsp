<?php

namespace modele;

/**
 * Classe Hopital — Représente un hôpital du réseau Hopital Sud Paris.
 *
 * Un hôpital regroupe des chambres, des médecins et des dossiers de prise en charge.
 * Le constructeur accepte un tableau associatif pour faciliter l'hydratation depuis la base de données.
 */
class Hopital
{
    /** @var mixed Identifiant unique de l'hôpital */
    private $id_hopital;

    /** @var mixed Nom officiel de l'hôpital */
    private $nom;

    /** @var mixed Adresse postale de l'hôpital (rue et numéro) */
    private $adresse_hopital;

    /** @var mixed Ville où est situé l'hôpital */
    private $ville_hopital;

    /**
     * Initialise un hôpital à partir d'un tableau associatif de données.
     *
     * Seules les clés présentes dans le tableau sont affectées aux propriétés correspondantes.
     *
     * @param array $data Tableau associatif issu de la base de données
     */
    public function __construct(array $data = [])
    {
        if (isset($data['id_hopital'])) $this->id_hopital = $data['id_hopital'];
        if (isset($data['nom'])) $this->nom = $data['nom'];
        if (isset($data['adresse_hopital'])) $this->adresse_hopital = $data['adresse_hopital'];
        if (isset($data['ville_hopital'])) $this->ville_hopital = $data['ville_hopital'];
    }

    // --- Getters & Setters ---

    /** @return mixed */
    public function getIdHopital()
    {
        return $this->id_hopital;
    }

    /** @param mixed $id_hopital */
    public function setIdHopital($id_hopital)
    {
        $this->id_hopital = $id_hopital;
    }

    /** @return mixed */
    public function getNom()
    {
        return $this->nom;
    }

    /** @param mixed $nom */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /** @return mixed */
    public function getAdresseHopital()
    {
        return $this->adresse_hopital;
    }

    /** @param mixed $adresse_hopital */
    public function setAdresseHopital($adresse_hopital)
    {
        $this->adresse_hopital = $adresse_hopital;
    }

    /** @return mixed */
    public function getVilleHopital()
    {
        return $this->ville_hopital;
    }

    /** @param mixed $ville_hopital */
    public function setVilleHopital($ville_hopital)
    {
        $this->ville_hopital = $ville_hopital;
    }
}
