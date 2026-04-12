<?php

namespace repository;

require_once __DIR__ . '/../modele/Evenement.php';

use \PDO;
use \PDOException;
use modele\Evenement;

/**
 * Gère les requêtes SQL liées aux événements hospitaliers.
 *
 * Permet de créer, modifier, supprimer et récupérer les événements
 * (conférences, formations, journées portes ouvertes, etc.) organisés par l'hôpital.
 */
class EvenementRepository
{
    /** @var PDO Instance de connexion à la base de données */
    private PDO $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(PDO $bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Insère un nouvel événement en base de données.
     *
     * @param Evenement $evenement L'objet événement à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function creerEvenement(Evenement $evenement): bool
    {
        $sql = "
            INSERT INTO evenement (date_evenement, description, lieu, nb_place, titre, type_evenement)
            VALUES (:date_evenement, :description, :lieu, :nb_place, :titre, :type_evenement)
        ";

        $stmt = $this->bdd->prepare($sql);

        return $stmt->execute([
            'date_evenement' => $evenement->getDateEvenement(),
            'description'    => $evenement->getDescription(),
            'lieu'           => $evenement->getLieu(),
            'nb_place'       => $evenement->getNbPlace(),
            'titre'          => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()
        ]);
    }

    /**
     * Met à jour les informations d'un événement existant.
     *
     * @param Evenement $evenement L'objet événement avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
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
            'id_evenement'   => $evenement->getIdEvenement(),
            'date_evenement' => $evenement->getDateEvenement(),
            'description'    => $evenement->getDescription(),
            'lieu'           => $evenement->getLieu(),
            'nb_place'       => $evenement->getNbPlace(),
            'titre'          => $evenement->getTitre(),
            'type_evenement' => $evenement->getTypeEvenement()
        ]);
    }

    /**
     * Supprime un événement par son identifiant.
     *
     * @param int $id_evenement Identifiant de l'événement à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimerEvenement(int $id_evenement): bool
    {
        $stmt = $this->bdd->prepare("DELETE FROM evenement WHERE id_evenement = :id_evenement");
        return $stmt->execute(['id_evenement' => $id_evenement]);
    }

    /**
     * Récupère un événement par son identifiant.
     *
     * @param int $id Identifiant de l'événement
     * @return Evenement|null L'objet Evenement correspondant, ou null si non trouvé
     */
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

    /**
     * Récupère tous les événements sous forme d'objets Evenement, triés par date décroissante.
     *
     * @return array Tableau d'objets Evenement
     */
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
