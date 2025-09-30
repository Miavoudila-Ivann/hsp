<?php

namespace repository;
use modele\Etablissement;
use modele\Evenement;
use modele\Hopital;

require_once __DIR__ . '/../modele/Evenement.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class HopitalRepository
{

    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
    }

    public function creerHopital(Hopital $hopital)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO hopital (id_hopital, adresse_hopital, nom, ville_hopital)
            VALUES (:id_hopital, :adresse_hopital, :nom, :ville_hopital)
        ');

        return $req->execute([
            'id_hopital' => $hopital->getIdHopital(),
            'adresse_hopital' => $hopital->getAdresseHopital(),
            'nom' => $hopital->getNom(),
            'ville_hopital' => $hopital->getVilleHopital()
        ]);
    }

    public function modifierHopital(Hopital $hopital)
    {
        $sql = "UPDATE Hopital SET 
                    adresse_hopital = :adresse_hopital,nom = :nom,lieu = :lieu,ville_hopital = :ville_hopital
                WHERE id_hopital = :id_hopital";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            '$id_hopital' => $hopital->getIdHopital(),
            'adresse_hopital' => $hopital->getAdresseHopital(),
            'nom' => $hopital->getNom(),
            'ville_hopital' => $hopital->getVilleHopital()

        ]);
    }

    public function supprimerHopital($id_hopital)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM hopital WHERE $id_hopital = :id_hopital');
        return $req->execute(['id_hopital' => $id_hopital]);
    }

}