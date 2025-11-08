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
    public function ajouter(Entreprise $entreprise) {
        $stmt = $this->bdd->prepare(
            "INSERT INTO entreprise (nom_entreprise, rue_entreprise, ville_entreprise, cd_entreprise, site_web)
         VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $entreprise->getNom(),
            $entreprise->getRue(),
            $entreprise->getVille(),
            $entreprise->getCd(),
            $entreprise->getSiteWeb()
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
        rue_entreprise, 
        ville_entreprise, 
        cd_entreprise AS cd, 
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
    // --- Mettre à jour une entreprise ---
    public function modifierEntreprise(Entreprise $entreprise)
    {
        $req = $this->bdd->prepare('
        UPDATE entreprise 
        SET nom_entreprise = :nom, 
            rue_entreprise = :rue, 
            ville_entreprise = :ville, 
            cd_entreprise = :cd, 
            site_web = :site_web
        WHERE id_entreprise = :id
    ');

        return $req->execute([
            'nom'      => $entreprise->getNom(),
            'rue'      => $entreprise->getRue(),
            'ville'    => $entreprise->getVille(),
            'cd'       => $entreprise->getCd(),
            'site_web' => $entreprise->getSiteWeb(),
            'id'       => $entreprise->getId()
        ]);
    }


    // --- Supprimer une entreprise ---
    public function supprimerEntreprise($id)
    {
        $req = $this->bdd->prepare('DELETE FROM entreprise WHERE id_entreprise = :id_entreprise');
        return $req->execute(['id_entreprise' => $id]);
    }

    // --- Récupérer une entreprise par son ID ---
    public function findById(int $id): ?Entreprise
    {
        $stmt = $this->bdd->prepare('SELECT * FROM entreprise WHERE id_entreprise = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return new Entreprise($data); // $data est un tableau
        }

        return null;
    }


}
