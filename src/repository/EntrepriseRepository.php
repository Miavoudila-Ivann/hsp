<?php
namespace repository;

require_once __DIR__ . '/../modele/Entreprise.php';

use modele\Entreprise;
use PDO;
use PDOException;

class EntrepriseRepository
{
    private $bdd;

    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd; // $bdd est un objet PDO
    }

    // --- Créer une entreprise ---
    public function creerEntreprise(Entreprise $entreprise)
    {
        $req = $this->bdd->prepare('
            INSERT INTO entreprise (nom, adresse, site_web)
            VALUES (:nom, :adresse, :site_web)
        ');

        return $req->execute([
            'nom' => $entreprise->getNom(),
            'adresse' => $entreprise->getAdresse(),
            'site_web' => $entreprise->getSiteWeb()
        ]);
    }

    // --- Récupérer une entreprise par ID ---
    public function getEntrepriseParId($id)
    {
        $req = $this->bdd->prepare('SELECT * FROM entreprise WHERE id = :id');
        $req->execute(['id' => $id]);
        $data = $req->fetch(PDO::FETCH_ASSOC);

        return $data ? new Entreprise($data) : null;
    }

    // --- Récupérer toutes les entreprises ---
    public function findAll(): array
    {
        try {
            $stmt = $this->bdd->query('
            SELECT 
                id_entreprise AS id, 
                nom_entreprise AS nom, 
                CONCAT(rue_entreprise, ", ", ville_entreprise, " ", cd_entreprise) AS adresse, 
                site_web
            FROM entreprise 
            ORDER BY nom_entreprise ASC
        ');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $entreprises = [];
            foreach ($results as $row) {
                $entreprises[] = new Entreprise($row);
            }
            return $entreprises;
        } catch (PDOException $e) {
            error_log('Erreur findAll entreprises : ' . $e->getMessage());
            return [];
        }
    }


    // --- Mettre à jour une entreprise ---
    public function majEntreprise(Entreprise $entreprise)
    {
        $req = $this->bdd->prepare('
            UPDATE entreprise
            SET nom = :nom, adresse = :adresse, site_web = :site_web
            WHERE id = :id
        ');

        return $req->execute([
            'nom' => $entreprise->getNom(),
            'adresse' => $entreprise->getAdresse(),
            'site_web' => $entreprise->getSiteWeb(),
            'id' => $entreprise->getId()
        ]);
    }

    // --- Supprimer une entreprise ---
    public function supprimerEntreprise($id)
    {
        $req = $this->bdd->prepare('DELETE FROM entreprise WHERE id = :id');
        return $req->execute(['id' => $id]);
    }
}
