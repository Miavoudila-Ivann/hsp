<?php

namespace modele;

/**
 * Classe Evenement — Représente un événement organisé par ou pour l'hôpital.
 *
 * Un événement peut être une conférence médicale, une formation, une journée portes ouvertes, etc.
 * Il est caractérisé par un titre, un type, un lieu, une date et un nombre de places disponibles.
 * Le constructeur accepte un tableau associatif pour faciliter l'hydratation depuis la base de données.
 */
class Evenement
{
    /** @var mixed Identifiant unique de l'événement */
    private $id_evenement;

    /** @var mixed Titre de l'événement affiché aux utilisateurs */
    private $titre;

    /** @var mixed Description détaillée du contenu et des objectifs de l'événement */
    private $description;

    /** @var mixed Catégorie de l'événement (ex : 'Conférence', 'Formation', 'Séminaire') */
    private $type_evenement;

    /** @var mixed Lieu physique où se déroule l'événement */
    private $lieu;

    /** @var mixed Nombre de places disponibles pour les participants */
    private $nb_place;

    /** @var mixed Date à laquelle l'événement est prévu */
    private $date_evenement;

    /**
     * Initialise un événement à partir d'un tableau associatif de données.
     *
     * Seules les clés présentes dans le tableau sont affectées aux propriétés correspondantes.
     *
     * @param array $data Tableau associatif issu de la base de données
     */
    public function __construct(array $data = [])
    {
        if (isset($data['id_evenement'])) $this->id_evenement = $data['id_evenement'];
        if (isset($data['titre'])) $this->titre = $data['titre'];
        if (isset($data['description'])) $this->description = $data['description'];
        if (isset($data['type_evenement'])) $this->type_evenement = $data['type_evenement'];
        if (isset($data['lieu'])) $this->lieu = $data['lieu'];
        if (isset($data['nb_place'])) $this->nb_place = $data['nb_place'];
        if (isset($data['date_evenement'])) $this->date_evenement = $data['date_evenement'];
    }

    // --- Getters & Setters ---

    /** @return mixed */
    public function getIdEvenement()
    {
        return $this->id_evenement;
    }

    /** @param mixed $id_evenement */
    public function setIdEvenement($id_evenement)
    {
        $this->id_evenement = $id_evenement;
    }

    /** @return mixed */
    public function getTitre()
    {
        return $this->titre;
    }

    /** @param mixed $titre */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /** @return mixed */
    public function getDescription()
    {
        return $this->description;
    }

    /** @param mixed $description */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /** @return mixed */
    public function getTypeEvenement()
    {
        return $this->type_evenement;
    }

    /** @param mixed $type_evenement */
    public function setTypeEvenement($type_evenement)
    {
        $this->type_evenement = $type_evenement;
    }

    /** @return mixed */
    public function getNbPlace()
    {
        return $this->nb_place;
    }

    /** @param mixed $nb_place */
    public function setNbPlace($nb_place)
    {
        $this->nb_place = $nb_place;
    }

    /** @return mixed */
    public function getLieu()
    {
        return $this->lieu;
    }

    /** @param mixed $lieu */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /** @return mixed */
    public function getDateEvenement()
    {
        return $this->date_evenement;
    }

    /** @param mixed $date_evenement */
    public function setDateEvenement($date_evenement)
    {
        $this->date_evenement = $date_evenement;
    }
}
