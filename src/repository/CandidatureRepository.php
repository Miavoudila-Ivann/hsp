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
            INSERT INTO candidatures (motivation, statut, date_candidature, ref_offre, ref_utilisateur)
            VALUES (:motivation, :statut, :date_candidature, :ref_offre, :ref_utilisateur)
        ');

        return $req->execute([
            ':motivation' => $candidature->getMotivation(),
            ':statut' => $candidature->getStatut(),
            ':date_candidature' => $candidature->getDateCandidature(),
            ':ref_offre' => $candidature->getRefOffre(),
            ':ref_utilisateur' => $candidature->getRefUtilisateur(),
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
