<?php

class PostRepository {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function ajouter($post) {
        $stmt = $this->bdd->prepare("INSERT INTO post (id_post, canal, titre, contenu, date_post) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $post->getIdPost(),
            $post->getCanal(),
            $post->getTitre(),
            $post->getContenu(),
            $post->getDatePost()
        ]);
    }

    public function modifier($post) {
        $stmt = $this->bdd->prepare("UPDATE post SET canal = ?, titre = ?, contenu = ?, date_post = ? WHERE id_post = ?");
        return $stmt->execute([
            $post->getCanal(),
            $post->getTitre(),
            $post->getContenu(),
            $post->getDatePost(),
            $post->getIdPost()
        ]);
    }

    public function supprimer($id_post) {
        $stmt = $this->bdd->prepare("DELETE FROM post WHERE id_post = ?");
        return $stmt->execute([$id_post]);
    }
}
?>
