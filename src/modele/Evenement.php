<?php

namespace modele;

class Evenement
{
    private $id_evenement;
    private $titre;
    private $description;
    private $type_evenement;
    private $lieu;
    private $nb_place;
    private $date_evenement;

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
    /**
     * @return mixed
     */
    public function getIdEvenement()
    {
        return $this->id_evenement;
    }

    /**
     * @param mixed $id_evenement
     */
    public function setIdEvenement($id_evenement)
    {
        $this->id_evenement = $id_evenement;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getTypeEvenement()
    {
        return $this->type_evenement;
    }

    /**
     * @param mixed $type_evenement
     */
    public function setTypeEvenement($type_evenement)
    {
        $this->type_evenement = $type_evenement;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getNbPlace()
    {
        return $this->nb_place;
    }

    /**
     * @param mixed $nb_place
     */
    public function setNbPlace($nb_place)
    {
        $this->nb_place = $nb_place;
    }

    /**
     * @return mixed
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param mixed $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * @return mixed
     */
    public function getDateEvenement()
    {
        return $this->date_evenement;
    }

    /**
     * @param mixed $date_evenement
     */
    public function setDateEvenement($date_evenement)
    {
        $this->date_evenement = $date_evenement;
    }


}