<?php

namespace repository;
use modele\Etablissement;
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

    public function modifierEvenement(Evenement $evenement)
    {
        $sql = "UPDATE evenement SET 
                    date_evenement = :date_evenement,description = :description,lieu = :lieu,nb_place = :nb_place,titre = :titre
                WHERE id_evenement = :id_evenement";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_evenement' => $evenement->getIdEvenement(),
            'description' => $evenement->getdescription(),
            'lieu' => $evenement->getlieu(),
            'nb_place' => $evenement->getNbPlace(),
            'titre' => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()

        ]);
    }

    public function supprimerEvenement($id_evenement)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM evenement WHERE id_evenement = :id_evenement');
        return $req->execute(['id_evenement' => id_evenement]);
    }

}