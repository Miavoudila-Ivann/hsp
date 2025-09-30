<?php

class Reponse
{
    private $idReponse;
    private $contenue;
    private $date_post;
    private $idref_post;
    private $idRef_auteur;

    /**
     * @param $idReponse
     * @param $contenue
     * @param $date_post
     * @param $idref_post
     * @param $idRef_auteur
     */
    public function __construct($idReponse, $contenue, $date_post, $idref_post, $idRef_auteur)
    {
        $this->idReponse = $idReponse;
        $this->contenue = $contenue;
        $this->date_post = $date_post;
        $this->idref_post = $idref_post;
        $this->idRef_auteur = $idRef_auteur;
    }

    /**
     * @return mixed
     */
    public function getIdReponse()
    {
        return $this->idReponse;
    }

    /**
     * @param mixed $idReponse
     */
    public function setIdReponse($idReponse)
    {
        $this->idReponse = $idReponse;
    }

    /**
     * @return mixed
     */
    public function getContenue()
    {
        return $this->contenue;
    }

    /**
     * @param mixed $contenue
     */
    public function setContenue($contenue)
    {
        $this->contenue = $contenue;
    }

    /**
     * @return mixed
     */
    public function getDatePost()
    {
        return $this->date_post;
    }

    /**
     * @param mixed $date_post
     */
    public function setDatePost($date_post)
    {
        $this->date_post = $date_post;
    }

    /**
     * @return mixed
     */
    public function getIdrefPost()
    {
        return $this->idref_post;
    }

    /**
     * @param mixed $idref_post
     */
    public function setIdrefPost($idref_post)
    {
        $this->idref_post = $idref_post;
    }

    /**
     * @return mixed
     */
    public function getIdRefAuteur()
    {
        return $this->idRef_auteur;
    }

    /**
     * @param mixed $idRef_auteur
     */
    public function setIdRefAuteur($idRef_auteur)
    {
        $this->idRef_auteur = $idRef_auteur;
    }
}

