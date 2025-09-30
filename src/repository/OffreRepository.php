<?php

class OffreRepository {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function ajouter($offre) {
        $stmt = $this->bdd->prepare("INSERT INTO offre (id_offre, titre, description, mission, salaire, type_offre, etat, ref_utilisateur, ref_entreprise, date_publication) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $offre->getIdOffre(),
            $offre->getTitre(),
            $offre->getDescription(),
            $offre->getMission(),
            $offre->getSalaire(),
            $offre->getTypeOffre(),
            $offre->getEtat(),
            $offre->getRefUtilisateur(),
            $offre->getRefEntreprise(),
            $offre->getDatePublication()
        ]);
    }

    public function modifier($offre) {
        $stmt = $this->bdd->prepare("UPDATE offre SET titre=?, description=?, mission=?, salaire=?, type_offre=?, etat=?, ref_utilisateur=?, ref_entreprise=?, date_publication=? WHERE id_offre=?");
        return $stmt->execute([
            $offre->getTitre(),
            $offre->getDescription(),
            $offre->getMission(),
            $offre->getSalaire(),
            $offre->getTypeOffre(),
            $offre->getEtat(),
            $offre->getRefUtilisateur(),
            $offre->getRefEntreprise(),
            $offre->getDatePublication(),
            $offre->getIdOffre()
        ]);
    }

    public function supprimer($id) {
        $stmt = $this->bdd->prepare("DELETE FROM offre WHERE id_offre = ?");
        return $stmt->execute([$id]);
    }
}
?>
