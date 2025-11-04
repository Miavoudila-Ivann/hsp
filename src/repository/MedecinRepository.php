<?php
require_once 'Medecin.php';

class MedecinRepository {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function ajouter($medecin) {
        $stmt = $this->bdd->prepare("INSERT INTO medecin (ref_specialite, ref_hopital, ref_etablissement) VALUES (?, ?, ?)");
        return $stmt->execute([
            $medecin->getRefSpecialite(),
            $medecin->getRefHopital(),
            $medecin->getRefEtablissement()
        ]);
    }

    public function modifier($medecin) {
        $stmt = $this->bdd->prepare("UPDATE medecin SET ref_specialite = ?, ref_hopital = ?, ref_etablissement = ? WHERE id_medecin = ?");
        return $stmt->execute([
            $medecin->getRefSpecialite(),
            $medecin->getRefHopital(),
            $medecin->getRefEtablissement(),
            $medecin->getIdMedecin()
        ]);
    }

    public function supprimer($id_medecin) {
        $stmt = $this->bdd->prepare("DELETE FROM medecin WHERE id_medecin = ?");
        return $stmt->execute([$id_medecin]);
    }

    public function trouverTous() {
        $sql = "
            SELECT 
                m.id_medecin,
                s.libelle AS specialite,
                h.nom AS hopital,
                e.nom_etablissement AS etablissement
            FROM medecin m
            LEFT JOIN specialite s ON m.ref_specialite = s.id_specialite
            LEFT JOIN hopital h ON m.ref_hopital = h.id_hopital
            LEFT JOIN etablissement e ON m.ref_etablissement = e.id_etablissement
        ";
        $stmt = $this->bdd->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function trouverParId($id) {
        $stmt = $this->bdd->prepare("SELECT * FROM medecin WHERE id_medecin = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSpecialites() {
        return $this->bdd->query("SELECT id_specialite, libelle FROM specialite")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHopitaux() {
        return $this->bdd->query("SELECT id_hopital, nom FROM hopital")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEtablissements() {
        return $this->bdd->query("SELECT id_etablissement, nom_etablissement FROM etablissement")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>