<?php

namespace repository;

use Bdd;
use modele\Evenement;

require_once __DIR__ . '/../modele/Evenement.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class EvenementRepository
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
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
            'date_evenement' => $evenement->getdateEvenement(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getlieu(),
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
}
