<?php

class MedecinRepository {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    public function ajouter($medecin) {
        $stmt = $this->bdd->prepare("INSERT INTO medecin (id_medecin, ref_specialite, ref_hopital, ref_etablissement) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $medecin->getIdMedecin(),
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
}
?>
