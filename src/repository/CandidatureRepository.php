<?php
require_once __DIR__ . '/../modele/Candidature.php';
require_once __DIR__ . '/../bdd/Bdd.php';

class CandidatureRepository
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
    }

    public function creerCandidature(Candidature $candidature)
    {
        $req = $this->bdd->getBdd()->prepare('
            INSERT INTO candidatures (id_utilisateur, id_offre, motivation)
            VALUES (:id_utilisateur, :id_offre, :motivation)
        ');

        return $req->execute([
            'id_utilisateur' => $candidature->getIdUtilisateur(),
            'id_offre'       => $candidature->getIdOffre(),
            'motivation'     => $candidature->getMotivation()
        ]);
    }

    public function getCandidaturesParOffre($idOffre)
    {
        $req = $this->bdd->getBdd()->prepare('SELECT * FROM candidatures WHERE id_offre = :id_offre');
        $req->execute(['id_offre' => $idOffre]);
        $data = $req->fetchAll(PDO::FETCH_ASSOC);

        $liste = [];
        foreach ($data as $row) {
            $liste[] = new Candidature($row);
        }
        return $liste;
    }

    public function supprimerCandidature($id)
    {
        $req = $this->bdd->getBdd()->prepare('DELETE FROM candidatures WHERE id = :id');
        return $req->execute(['id' => $id]);
    }
}
