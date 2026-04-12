<?php

namespace repository;
require_once 'Reponse.php';

/**
 * Gère les requêtes SQL liées aux réponses du forum.
 *
 * Permet d'ajouter, modifier et supprimer les réponses
 * publiées par les utilisateurs en réaction à un post du forum.
 */
class ReponseRepository
{
    /** @var \PDO Instance de connexion PDO à la base de données */
    private $pdo;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param PDO $pdo Instance de connexion à la base de données
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insère une nouvelle réponse en base de données.
     *
     * @param Reponse $reponse L'objet réponse à enregistrer
     * @return void
     */
    public function ajouter(Reponse $reponse)
    {
        $sql = "INSERT INTO reponse (idReponse, contenue, date_post, idref_post, idRef_auteur)
                VALUES (:idReponse, :contenue, :date_post, :idref_post, :idRef_auteur)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'idReponse'    => $reponse->getIdReponse(),
            'contenue'     => $reponse->getContenue(),
            'date_post'    => $reponse->getDatePost(),
            'idref_post'   => $reponse->getIdrefPost(),
            'idRef_auteur' => $reponse->getIdRefAuteur()
        ]);
    }

    /**
     * Met à jour le contenu d'une réponse existante.
     *
     * @param Reponse $reponse L'objet réponse avec les nouvelles données
     * @return void
     */
    public function modifier(Reponse $reponse)
    {
        $sql = "UPDATE reponse SET
                    contenue = :contenue,
                    date_post = :date_post,
                    idref_post = :idref_post,
                    idRef_auteur = :idRef_auteur
                WHERE idReponse = :idReponse";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'contenue'     => $reponse->getContenue(),
            'date_post'    => $reponse->getDatePost(),
            'idref_post'   => $reponse->getIdrefPost(),
            'idRef_auteur' => $reponse->getIdRefAuteur(),
            'idReponse'    => $reponse->getIdReponse()
        ]);
    }

    /**
     * Supprime une réponse par son identifiant.
     *
     * @param mixed $idReponse Identifiant de la réponse à supprimer
     * @return void
     */
    public function supprimer($idReponse)
    {
        $sql = "DELETE FROM reponse WHERE idReponse = :idReponse";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idReponse' => $idReponse]);
    }
}
