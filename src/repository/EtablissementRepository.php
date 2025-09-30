<?php
namespace repository;
use modele\Etablissement;

require_once __DIR__ . '/../modele/Etablissement.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class EtablissementRepository
{

    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
    }

    public function creerEtablissement(Etablissement $etablissement)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO etablissement (id_etablissement, nom_etablissement, adresse_etablissement, site_web_etablissement)
            VALUES (:id_etablissement, :nom_etablissement, :adresse_etablissement, :site_web_etablissement)
        ');

        return $req->execute([
            'id_etablissement' => $etablissement->getIdEtablissement(),
            'nom_etablissement' => $etablissement->getNomEtablissemnet(),
            'adresse_etablissement' => $etablissement->getAdresseEtablissement(),
            'site_web_etablissement' => $etablissement->getSiteWebEtablissement()
        ]);
    }

    public function supprimerEtablissement($id_etablissement)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM etablissement WHERE id_etablissement = :id_etablissement');
        return $req->execute(['id_etablissement' => $id_etablissement]);
    }

}