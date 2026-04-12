<?php
namespace repository;
use Bdd;
use modele\Etablissement;
use PDOException;


require_once __DIR__ . '/../modele/Etablissement.php';
require_once __DIR__ . '/../bdd/Bdd.php';

/**
 * Gère les requêtes SQL liées aux établissements de santé.
 *
 * Permet de créer, modifier, supprimer et lister les établissements
 * rattachés à l'application hospitalière.
 */
class EtablissementRepository
{
    /** @var \PDO Instance de connexion à la base de données */
    private $bdd;

    /** @var \PDO|null Instance PDO alternative (non utilisée activement) */
    private $pdo;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param \PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Insère un nouvel établissement en base de données.
     *
     * @param Etablissement $etablissement L'objet établissement à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function creerEtablissement(Etablissement $etablissement)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO etablissement (id_etablissement, nom_etablissement, adresse_etablissement, site_web_etablissement)
            VALUES (:id_etablissement, :nom_etablissement, :adresse_etablissement, :site_web_etablissement)
        ');

        return $req->execute([
            'id_etablissement'       => $etablissement->getIdEtablissement(),
            'nom_etablissement'      => $etablissement->getNomEtablissement(),
            'adresse_etablissement'  => $etablissement->getAdresseEtablissement(),
            'site_web_etablissement' => $etablissement->getSiteWebEtablissement()
        ]);
    }

    /**
     * Met à jour les informations d'un établissement existant.
     *
     * @param Etablissement $etablissement L'objet établissement avec les nouvelles données
     * @return void
     */
    public function modifierEtablissement(Etablissement $etablissement)
    {
        $sql = "UPDATE etablissement SET
                    nom_etablissement = :nom_etablissement,adresse_etablissement = :adresse_etablissement,site_web_etablissement = :site_web_etablissement
                WHERE id_etablissement = :id_etablissement";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_etablissement'       => $etablissement->getIdEtablissement(),
            'nom_etablissement'      => $etablissement->getNomEtablissement(),
            'adress_etablissement'   => $etablissement->getAdresseEtablissement(),
            'site_web_etablissement' => $etablissement->getSiteWebEtablissement()
        ]);
    }

    /**
     * Supprime un établissement par son identifiant.
     *
     * @param mixed $id_etablissement Identifiant de l'établissement à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimerEtablissement($id_etablissement)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM etablissement WHERE id_etablissement = :id_etablissement');
        return $req->execute(['id_etablissement' => $id_etablissement]);
    }

    /**
     * Récupère tous les établissements avec leurs informations principales, triés par identifiant.
     *
     * @return array Tableau associatif de tous les établissements
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT id_etablissement, nom_etablissement, site_web_etablissement FROM etablissement ORDER BY id_etablissement ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur findAll établissement : " . $e->getMessage());
            return [];
        }
    }
}
