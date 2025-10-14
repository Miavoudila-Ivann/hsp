<?php

namespace repository;

use modele\Hopital;
use PDO;

require_once __DIR__ . '/../modele/Hopital.php';

class HopitalRepository
{
    private PDO $bdd;

    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd;
    }

    public function creerHopital(Hopital $hopital): bool
    {
        $req = $this->bdd->prepare('
            INSERT INTO hopital (nom, adresse_hopital, ville_hopital)
            VALUES (:nom, :adresse_hopital, :ville_hopital)
        ');

        return $req->execute([
            'nom' => $hopital->getNom(),
            'adresse_hopital' => $hopital->getAdresseHopital(),
            'ville_hopital' => $hopital->getVilleHopital()
        ]);
    }

    public function modifierHopital(Hopital $hopital): bool
    {
        $sql = "UPDATE hopital SET 
                    nom = :nom,
                    adresse_hopital = :adresse_hopital,
                    ville_hopital = :ville_hopital
                WHERE id_hopital = :id_hopital";

        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            'id_hopital' => $hopital->getIdHopital(),
            'nom' => $hopital->getNom(),
            'adresse_hopital' => $hopital->getAdresseHopital(),
            'ville_hopital' => $hopital->getVilleHopital()
        ]);
    }

    public function supprimerHopital(int $id_hopital): bool
    {
        $req = $this->bdd->prepare('DELETE FROM hopital WHERE id_hopital = :id_hopital');
        return $req->execute(['id_hopital' => $id_hopital]);
    }
}
