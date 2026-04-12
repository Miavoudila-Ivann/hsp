<?php

/**
 * Classe Post — Représente un message publié dans le forum interne de l'hôpital.
 *
 * Un post est organisé par canal thématique (ex : médecins, administratif) et comporte
 * un titre ainsi qu'un contenu textuel. Les réponses associées sont gérées via la classe Reponse.
 */
class Post {

    /** @var mixed Identifiant unique du post dans le forum */
    private $id_post;

    /** @var mixed Canal thématique auquel appartient le post (ex : 'medecin', 'admin', 'utilisateur') */
    private $canal;

    /** @var mixed Titre résumant le sujet du post */
    private $titre;

    /** @var mixed Corps du message rédigé par l'auteur */
    private $contenu;

    /** @var mixed Date et heure de publication du post */
    private $date_post;

    /**
     * Initialise un post avec toutes ses informations de publication.
     *
     * @param mixed $id_post   Identifiant du post
     * @param mixed $canal     Canal thématique de publication
     * @param mixed $titre     Titre du post
     * @param mixed $contenu   Contenu du message
     * @param mixed $date_post Date de publication
     */
    public function __construct($id_post, $canal, $titre, $contenu, $date_post) {
        $this->id_post = $id_post;
        $this->canal = $canal;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->date_post = $date_post;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdPost() {
        return $this->id_post;
    }

    /** @return mixed */
    public function getCanal() {
        return $this->canal;
    }

    /** @return mixed */
    public function getTitre() {
        return $this->titre;
    }

    /** @return mixed */
    public function getContenu() {
        return $this->contenu;
    }

    /** @return mixed */
    public function getDatePost() {
        return $this->date_post;
    }

    // --- Setters ---

    /** @param mixed $id_post */
    public function setIdPost($id_post) {
        $this->id_post = $id_post;
    }

    /** @param mixed $canal */
    public function setCanal($canal) {
        $this->canal = $canal;
    }

    /** @param mixed $titre */
    public function setTitre($titre) {
        $this->titre = $titre;
    }

    /** @param mixed $contenu */
    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    /** @param mixed $date_post */
    public function setDatePost($date_post) {
        $this->date_post = $date_post;
    }
}
?>
