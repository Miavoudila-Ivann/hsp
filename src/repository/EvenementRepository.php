<?php

namespace repository;

require_once __DIR__ . '/../modele/Evenement.php';
require_once __DIR__ . '/../bdd/Bdd.php';

use \PDO;
use PDOException;
use modele\Evenement;
class EvenementRepository
{
    private PDO $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    // Méthode pour créer un événement
    public function creerEvenement(Evenement $evenement)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO evenement (id_evenement, date_evenement, description, lieu, nb_place, titre, type_evenement)
            VALUES (:id_evenement, :date_evenement, :description, :lieu, :nb_place, :titre, :type_evenement)
        ');

        return $req->execute([
            'id_evenement' => $evenement->getIdEvenement(),
            'date_evenement' => $evenement->getDateEvenement(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getLieu(),
            'nb_place' => $evenement->getNbPlace(),
            'titre' => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()
        ]);
    }

    // Méthode pour modifier un événement
    public function modifierEvenement(Evenement $evenement)
    {
        $sql = "UPDATE evenement SET 
                    date_evenement = :date_evenement,
                    description = :description,
                    lieu = :lieu,
                    nb_place = :nb_place,
                    titre = :titre,
                    type_evenement = :type_evenement
                WHERE id_evenement = :id_evenement";

        $stmt = $this->bdd->getBdd()->prepare($sql);
        $stmt->execute([
            'id_evenement' => $evenement->getIdEvenement(),
            'description' => $evenement->getdescription(),
            'lieu' => $evenement->getlieu(),
            'nb_place' => $evenement->getNbPlace(),
            'titre' => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()
        ]);
    }

    // Méthode pour supprimer un événement
    public function supprimerEvenement($id_evenement)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM evenement WHERE id_evenement = :id_evenement');
        return $req->execute(['id_evenement' => $id_evenement]);
    }

    // Méthode pour récupérer un événement par son ID
    public function getEvenementById($id)
    {
        $stmt = $this->bdd->getBdd()->prepare('SELECT * FROM evenement WHERE id_evenement = :id_evenement');
        $stmt->execute(['id_evenement' => $id]);
        $evenement = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($evenement) {
            return new Evenement($evenement); // Retourne un objet Evenement avec les données récupérées
        }

        return null; // Si l'événement n'est pas trouvé, retourne null
    }
    public function findAll(): array
    {
        try {
            $sql = "SELECT id_evenement, titre, description, type_evenement, lieu ,nb_place, date_evenement FROM evenement ORDER BY id_evenement ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur findAll évenement : " . $e->getMessage());
            return [];
        }
    }
}
