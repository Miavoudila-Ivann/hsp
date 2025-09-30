<?php

class Post {
    private $id_post;
    private $canal;
    private $titre;
    private $contenu;
    private $date_post;

    public function __construct($id_post, $canal, $titre, $contenu, $date_post) {
        $this->id_post = $id_post;
        $this->canal = $canal;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->date_post = $date_post;
    }

    public function getIdPost() {
        return $this->id_post;
    }

    public function getCanal() {
        return $this->canal;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getDatePost() {
        return $this->date_post;
    }

    public function setIdPost($id_post) {
        $this->id_post = $id_post;
    }

    public function setCanal($canal) {
        $this->canal = $canal;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    public function setDatePost($date_post) {
        $this->date_post = $date_post;
    }
}
?>
