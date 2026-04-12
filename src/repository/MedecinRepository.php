<?php
require_once 'Medecin.php';

/**
 * Gère les requêtes SQL liées aux médecins.
 *
 * Permet d'ajouter, modifier, supprimer et récupérer les médecins
 * ainsi que les données de référence (spécialités, hôpitaux, établissements)
 * nécessaires à leur gestion.
 */
class MedecinRepository {
    /** @var \PDO Instance de connexion à la base de données */
    private $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param \PDO $bdd Instance de connexion à la base de données
     */
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Insère un nouveau médecin en base de données.
     *
     * @param mixed $medecin L'objet médecin à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter($medecin) {
        $stmt = $this->bdd->prepare("INSERT INTO medecin (ref_specialite, ref_hopital, ref_etablissement) VALUES (?, ?, ?)");
        return $stmt->execute([
            $medecin->getRefSpecialite(),
            $medecin->getRefHopital(),
            $medecin->getRefEtablissement()
        ]);
    }

    /**
     * Met à jour les informations d'un médecin existant.
     *
     * @param mixed $medecin L'objet médecin avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier($medecin) {
        $stmt = $this->bdd->prepare("UPDATE medecin SET ref_specialite = ?, ref_hopital = ?, ref_etablissement = ? WHERE id_medecin = ?");
        return $stmt->execute([
            $medecin->getRefSpecialite(),
            $medecin->getRefHopital(),
            $medecin->getRefEtablissement(),
            $medecin->getIdMedecin()
        ]);
    }

    /**
     * Supprime un médecin par son identifiant.
     *
     * @param mixed $id_medecin Identifiant du médecin à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id_medecin) {
        $stmt = $this->bdd->prepare("DELETE FROM medecin WHERE id_medecin = ?");
        return $stmt->execute([$id_medecin]);
    }

    /**
     * Récupère tous les médecins avec leur spécialité, hôpital et établissement associés.
     *
     * Jointure gauche entre medecin, specialite, hopital et etablissement pour afficher
     * les libellés lisibles plutôt que les identifiants de référence.
     *
     * @return array Tableau associatif de tous les médecins avec leurs informations liées
     */
    public function trouverTous() {
        // Jointures LEFT JOIN pour récupérer les labels de spécialité, hôpital et établissement
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

    /**
     * Récupère un médecin par son identifiant.
     *
     * @param mixed $id Identifiant du médecin
     * @return array|false Tableau associatif du médecin, ou false si non trouvé
     */
    public function trouverParId($id) {
        $stmt = $this->bdd->prepare("SELECT * FROM medecin WHERE id_medecin = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les spécialités médicales disponibles.
     *
     * @return array Tableau associatif de toutes les spécialités (id + libellé)
     */
    public function getSpecialites() {
        return $this->bdd->query("SELECT id_specialite, libelle FROM specialite")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les hôpitaux disponibles.
     *
     * @return array Tableau associatif de tous les hôpitaux (id + nom)
     */
    public function getHopitaux() {
        return $this->bdd->query("SELECT id_hopital, nom FROM hopital")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les établissements disponibles.
     *
     * @return array Tableau associatif de tous les établissements (id + nom)
     */
    public function getEtablissements() {
        return $this->bdd->query("SELECT id_etablissement, nom_etablissement FROM etablissement")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
