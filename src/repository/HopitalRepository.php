<?php

namespace repository;

use modele\Hopital;
use PDO;
use PDOException;
require_once __DIR__ . '/../modele/Hopital.php';

/**
 * Gère les requêtes SQL liées aux hôpitaux.
 *
 * Permet de créer, modifier, supprimer et lister les hôpitaux
 * référencés dans l'application de gestion hospitalière.
 */
class HopitalRepository
{
    /** @var PDO Instance de connexion à la base de données */
    private PDO $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param \PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Insère un nouvel hôpital en base de données.
     *
     * @param Hopital $hopital L'objet hôpital à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function creerHopital(Hopital $hopital): bool
    {
        $req = $this->bdd->prepare('
            INSERT INTO hopital (nom, adresse_hopital, ville_hopital)
            VALUES (:nom, :adresse_hopital, :ville_hopital)
        ');

        return $req->execute([
            'nom'             => $hopital->getNom(),
            'adresse_hopital' => $hopital->getAdresseHopital(),
            'ville_hopital'   => $hopital->getVilleHopital()
        ]);
    }

    /**
     * Met à jour les informations d'un hôpital existant.
     *
     * @param Hopital $hopital L'objet hôpital avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifierHopital(Hopital $hopital): bool
    {
        $sql = "UPDATE hopital SET
                    nom = :nom,
                    adresse_hopital = :adresse_hopital,
                    ville_hopital = :ville_hopital
                WHERE id_hopital = :id_hopital";

        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            'id_hopital'      => $hopital->getIdHopital(),
            'nom'             => $hopital->getNom(),
            'adresse_hopital' => $hopital->getAdresseHopital(),
            'ville_hopital'   => $hopital->getVilleHopital()
        ]);
    }

    /**
     * Supprime un hôpital par son identifiant.
     *
     * @param int $id_hopital Identifiant de l'hôpital à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimerHopital(int $id_hopital): bool
    {
        $req = $this->bdd->prepare('DELETE FROM hopital WHERE id_hopital = :id_hopital');
        return $req->execute(['id_hopital' => $id_hopital]);
    }

    /**
     * Récupère tous les hôpitaux avec leurs informations principales, triés par identifiant.
     *
     * @return array Tableau associatif de tous les hôpitaux
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT id_hopital, nom, adresse_hopital, ville_hopital FROM hopital ORDER BY id_hopital ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur findAll hopitaux : " . $e->getMessage());
            return [];
        }
    }
}
