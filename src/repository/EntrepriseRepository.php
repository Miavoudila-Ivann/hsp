<?php
require_once __DIR__ . '/../modele/Entreprise.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class EntrepriseRepository
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
    }

    public function creerEntreprise(Entreprise $entreprise)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO entreprises (nom, adresse, site_web)
            VALUES (:nom, :adresse, :site_web)
        ');

        return $req->execute([
            'nom'      => $entreprise->getNom(),
            'adresse'  => $entreprise->getAdresse(),
            'site_web' => $entreprise->getSiteWeb()
        ]);
    }

    public function getEntrepriseParId($id)
    {
        $req = $this->bdd->getBdd()->prepare('SELECT * FROM entreprises WHERE id = :id');
        $req->execute(['id' => $id]);
        $data = $req->fetch(PDO::FETCH_ASSOC);

        return $data ? new Entreprise($data) : null;
    }

    public function getToutesLesEntreprises()
    {
        $req = $this->bdd->getBdd()->query('SELECT * FROM entreprises');
        $donnees = $req->fetchAll(PDO::FETCH_ASSOC);

        $listeEntreprises = [];
        foreach ($donnees as $data) {
            $listeEntreprises[] = new Entreprise($data);
        }

        return $listeEntreprises;
    }

    public function supprimerEntreprise($id)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM entreprises WHERE id = :id');
        return $req->execute(['id' => $id]);
    }

    public function majEntreprise(Entreprise $entreprise)
    {
        $req = $this->bdd->getBdd()->prepare('
            UPDATE entreprises
            SET nom = :nom, adresse = :adresse, site_web = :site_web
            WHERE id = :id
        ');

        return $req->execute([
            'nom'      => $entreprise->getNom(),
            'adresse'  => $entreprise->getAdresse(),
            'site_web' => $entreprise->getSiteWeb(),
            'id'       => $entreprise->getId()
        ]);
    }
}
?>
