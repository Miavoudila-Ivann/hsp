<?php

/**
 * Gère les requêtes SQL liées aux posts du forum.
 *
 * Permet de créer, modifier et supprimer les publications
 * rédigées par les utilisateurs dans les différents canaux du forum.
 */
class PostRepository {
    /** @var \PDO Instance de connexion à la base de données */
    private $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param \PDO $bdd Instance de connexion à la base de données
     */
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Insère un nouveau post dans le forum.
     *
     * @param mixed $post L'objet post à enregistrer (doit exposer getId, getCanal, getTitre, getContenu, getDatePost)
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
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

    /**
     * Met à jour le contenu d'un post existant.
     *
     * @param mixed $post L'objet post avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
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

    /**
     * Supprime un post par son identifiant.
     *
     * @param mixed $id_post Identifiant du post à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id_post) {
        $stmt = $this->bdd->prepare("DELETE FROM post WHERE id_post = ?");
        return $stmt->execute([$id_post]);
    }
}
?>
