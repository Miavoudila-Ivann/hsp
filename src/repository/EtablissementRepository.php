<?php
namespace repository;
use Bdd;
use modele\Etablissement;
use PDOException;


require_once __DIR__ . '/../modele/Etablissement.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class EtablissementRepository
{

    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function creerEtablissement(Etablissement $etablissement)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO etablissement (id_etablissement, nom_etablissement, adresse_etablissement, site_web_etablissement)
            VALUES (:id_etablissement, :nom_etablissement, :adresse_etablissement, :site_web_etablissement)
        ');

        return $req->execute([
            'id_etablissement' => $etablissement->getIdEtablissement(),
            'nom_etablissement' => $etablissement->getNomEtablissement(),
            'adresse_etablissement' => $etablissement->getAdresseEtablissement(),
            'site_web_etablissement' => $etablissement->getSiteWebEtablissement()
        ]);
    }
    public function modifierEtablissement(Etablissement $etablissement)
    {
        $sql = "UPDATE etablissement SET 
                    nom_etablissement = :nom_etablissement,adresse_etablissement = :adresse_etablissement,site_web_etablissement = :site_web_etablissement
                WHERE id_etablissement = :id_etablissement";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_etablissement' => $etablissement->getIdSpecialite(),
            'nom_etablissement' => $etablissement->getNomEtablissement(),
            'adress_etablissement' => $etablissement->getAdresseEtablissement(),
            'site_web_etablissement' => $etablissement->getSiteWebEtablissement()

        ]);
    }
    public function supprimerEtablissement($id_etablissement)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM etablissement WHERE id_etablissement = :id_etablissement');
        return $req->execute(['id_etablissement' => $id_etablissement]);
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT id_etablissement, nom_etablisement, site_web_etablissement FROM etablissement ORDER BY id_etablissement ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur findAll établissement : " . $e->getMessage());
            return [];
        }
    }


}