<?php

namespace repository;

require_once __DIR__ . '/../modele/Evenement.php';

use \PDO;
use \PDOException;
use modele\Evenement;

class EvenementRepository
{
    private PDO $bdd;

    public function __construct(PDO $bdd) {
        $this->bdd = $bdd;
    }

    // Méthode pour créer un événement
    public function creerEvenement(Evenement $evenement): bool
    {
        $sql = "
            INSERT INTO evenement (date_evenement, description, lieu, nb_place, titre, type_evenement)
            VALUES (:date_evenement, :description, :lieu, :nb_place, :titre, :type_evenement)
        ";

        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            'date_evenement' => $evenement->getDateEvenement(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getLieu(),
            'nb_place' => $evenement->getNbPlace(),
            'titre' => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()
        ]);
    }

    // Méthode pour modifier un événement
    public function modifierEvenement(Evenement $evenement): bool
    {
        $sql = "
            UPDATE evenement SET 
                date_evenement = :date_evenement,
                description = :description,
                lieu = :lieu,
                nb_place = :nb_place,
                titre = :titre,
                type_evenement = :type_evenement
            WHERE id_evenement = :id_evenement
        ";

        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            'id_evenement' => $evenement->getIdEvenement(),
            'date_evenement' => $evenement->getDateEvenement(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getLieu(),
            'nb_place' => $evenement->getNbPlace(),
            'titre' => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()
        ]);
    }

    // Méthode pour supprimer un événement
    public function supprimerEvenement(int $id_evenement): bool
    {
        $stmt = $this->bdd->prepare("DELETE FROM evenement WHERE id_evenement = :id_evenement");
        return $stmt->execute(['id_evenement' => $id_evenement]);
    }

    // Méthode pour récupérer un événement par son ID
    public function getEvenementById(int $id): ?Evenement
    {
        $stmt = $this->bdd->prepare("SELECT * FROM evenement WHERE id_evenement = :id_evenement");
        $stmt->execute(['id_evenement' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Evenement($data);
        }

        return null;
    }

    // 🔹 Méthode pour récupérer tous les événements sous forme d'objets Evenement
    public function getAllEvenements(): array
    {
        try {
            $stmt = $this->bdd->query("SELECT * FROM evenement ORDER BY date_evenement DESC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $evenements = [];
            foreach ($rows as $row) {
                $evenements[] = new Evenement($row);
            }

            return $evenements;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des événements : " . $e->getMessage());
            return [];
        }
    }
}
