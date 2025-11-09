<?php
namespace repository;

require_once __DIR__ . '/../modele/Offre.php';
use modele\Offre;

class OffreRepository {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function ajouter($offre) {
        $stmt = $this->bdd->prepare(
            "INSERT INTO offre (id_offre, titre, description, mission, salaire, type_offre, etat, ref_entreprise, date_publication) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $offre->getIdOffre(),
            $offre->getTitre(),
            $offre->getDescription(),
            $offre->getMission(),
            $offre->getSalaire(),
            $offre->getTypeOffre(),
            $offre->getEtat(),
            $offre->getRefEntreprise(),
            $offre->getDatePublication()
        ]);
    }

    public function modifier($offre) {
        $stmt = $this->bdd->prepare(
            "UPDATE offre SET titre=?, description=?, mission=?, salaire=?, type_offre=?, etat=?, ref_entreprise=?, date_publication=? WHERE id_offre=?"
        );
        return $stmt->execute([
            $offre->getTitre(),
            $offre->getDescription(),
            $offre->getMission(),
            $offre->getSalaire(),
            $offre->getTypeOffre(),
            $offre->getEtat(),
            $offre->getRefEntreprise(),
            $offre->getDatePublication(),
            $offre->getIdOffre()
        ]);
    }

    public function supprimer($id) {
        $stmt = $this->bdd->prepare("DELETE FROM offre WHERE id_offre = ?");
        return $stmt->execute([$id]);
    }

    public function findAll(): array {
        $stmt = $this->bdd->prepare("SELECT * FROM offre");
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $offres = [];
        foreach ($results as $row) {
            $offres[] = new Offre($row); // crée un objet Offre à partir de chaque ligne
        }

        return $offres;
    }


    public function findById(int $id) {
        $stmt = $this->bdd->prepare("SELECT * FROM entreprise WHERE id_entreprise = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return $data['nom_entreprise']; // retourne juste le nom
        }

        return null; // si l'entreprise n'existe pas
    }
}
?>
