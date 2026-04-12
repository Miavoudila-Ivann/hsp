<?php

/**
 * Classe Reponse — Représente une réponse publiée en réaction à un post du forum interne.
 *
 * Une réponse est associée à un post parent via une clé étrangère et identifie
 * l'utilisateur auteur de la réponse. Elle est horodatée à sa publication.
 */
class Reponse
{
    /** @var mixed Identifiant unique de la réponse */
    private $idReponse;

    /** @var mixed Contenu textuel de la réponse rédigée par l'auteur */
    private $contenue;

    /** @var mixed Date et heure de publication de la réponse */
    private $date_post;

    /** @var mixed Clé étrangère vers le post auquel cette réponse est rattachée */
    private $idref_post;

    /** @var mixed Clé étrangère vers l'utilisateur ayant rédigé la réponse */
    private $idRef_auteur;

    /**
     * Initialise une réponse de forum avec toutes ses informations.
     *
     * @param mixed $idReponse    Identifiant de la réponse
     * @param mixed $contenue     Contenu de la réponse
     * @param mixed $date_post    Date et heure de publication
     * @param mixed $idref_post   Référence du post parent
     * @param mixed $idRef_auteur Référence de l'utilisateur auteur
     */
    public function __construct($idReponse, $contenue, $date_post, $idref_post, $idRef_auteur)
    {
        $this->idReponse = $idReponse;
        $this->contenue = $contenue;
        $this->date_post = $date_post;
        $this->idref_post = $idref_post;
        $this->idRef_auteur = $idRef_auteur;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdReponse()
    {
        return $this->idReponse;
    }

    /** @return mixed */
    public function getContenue()
    {
        return $this->contenue;
    }

    /** @return mixed */
    public function getDatePost()
    {
        return $this->date_post;
    }

    /** @return mixed */
    public function getIdrefPost()
    {
        return $this->idref_post;
    }

    /** @return mixed */
    public function getIdRefAuteur()
    {
        return $this->idRef_auteur;
    }

    // --- Setters ---

    /** @param mixed $idReponse */
    public function setIdReponse($idReponse)
    {
        $this->idReponse = $idReponse;
    }

    /** @param mixed $contenue */
    public function setContenue($contenue)
    {
        $this->contenue = $contenue;
    }

    /** @param mixed $date_post */
    public function setDatePost($date_post)
    {
        $this->date_post = $date_post;
    }

    /** @param mixed $idref_post */
    public function setIdrefPost($idref_post)
    {
        $this->idref_post = $idref_post;
    }

    /** @param mixed $idRef_auteur */
    public function setIdRefAuteur($idRef_auteur)
    {
        $this->idRef_auteur = $idRef_auteur;
    }
}
