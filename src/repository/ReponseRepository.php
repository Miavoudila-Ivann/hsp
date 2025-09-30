<?php

namespace repository;
require_once 'Reponse.php';

class ReponseRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Ajouter une réponse
    public function ajouter(Reponse $reponse)
    {
        $sql = "INSERT INTO reponse (idReponse, contenue, date_post, idref_post, idRef_auteur)
                VALUES (:idReponse, :contenue, :date_post, :idref_post, :idRef_auteur)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'idReponse' => $reponse->getIdReponse(),
            'contenue' => $reponse->getContenue(),
            'date_post' => $reponse->getDatePost(),
            'idref_post' => $reponse->getIdrefPost(),
            'idRef_auteur' => $reponse->getIdRefAuteur()
        ]);
    }

    // Modifier une réponse
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
            'contenue' => $reponse->getContenue(),
            'date_post' => $reponse->getDatePost(),
            'idref_post' => $reponse->getIdrefPost(),
            'idRef_auteur' => $reponse->getIdRefAuteur(),
            'idReponse' => $reponse->getIdReponse()
        ]);
    }

    // Supprimer une réponse
    public function supprimer($idReponse)
    {
        $sql = "DELETE FROM reponse WHERE idReponse = :idReponse";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idReponse' => $idReponse]);
    }
}
