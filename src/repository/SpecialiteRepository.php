<?php
require_once 'Specialite.php';

class SpecialiteRepository {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function ajouter($specialite) {
        $stmt = $this->bdd->prepare("INSERT INTO specialite (libelle) VALUES (?)");
        return $stmt->execute([$specialite->getLibelle()]);
    }

    public function modifier($specialite) {
        $stmt = $this->bdd->prepare("UPDATE specialite SET libelle = ? WHERE id_specialite = ?");
        return $stmt->execute([$specialite->getLibelle(), $specialite->getIdSpecialite()]);
    }

    public function supprimer($id_specialite) {
        $stmt = $this->bdd->prepare("DELETE FROM specialite WHERE id_specialite = ?");
        return $stmt->execute([$id_specialite]);
    }

    public function trouverTous() {
        $stmt = $this->bdd->query("SELECT * FROM specialite ORDER BY libelle");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function trouverParId($id) {
        $stmt = $this->bdd->prepare("SELECT * FROM specialite WHERE id_specialite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>