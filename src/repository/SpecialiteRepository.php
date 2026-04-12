<?php
require_once 'Specialite.php';

/**
 * Gère les requêtes SQL liées aux spécialités médicales.
 *
 * Permet de créer, modifier, supprimer et lister les spécialités
 * assignées aux médecins dans le système hospitalier.
 */
class SpecialiteRepository {
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
     * Insère une nouvelle spécialité médicale en base de données.
     *
     * @param mixed $specialite L'objet spécialité à enregistrer (doit exposer getLibelle)
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter($specialite) {
        $stmt = $this->bdd->prepare("INSERT INTO specialite (libelle) VALUES (?)");
        return $stmt->execute([$specialite->getLibelle()]);
    }

    /**
     * Met à jour le libellé d'une spécialité existante.
     *
     * @param mixed $specialite L'objet spécialité avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifier($specialite) {
        $stmt = $this->bdd->prepare("UPDATE specialite SET libelle = ? WHERE id_specialite = ?");
        return $stmt->execute([$specialite->getLibelle(), $specialite->getIdSpecialite()]);
    }

    /**
     * Supprime une spécialité par son identifiant.
     *
     * @param mixed $id_specialite Identifiant de la spécialité à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id_specialite) {
        $stmt = $this->bdd->prepare("DELETE FROM specialite WHERE id_specialite = ?");
        return $stmt->execute([$id_specialite]);
    }

    /**
     * Récupère toutes les spécialités, triées alphabétiquement par libellé.
     *
     * @return array Tableau associatif de toutes les spécialités
     */
    public function trouverTous() {
        $stmt = $this->bdd->query("SELECT * FROM specialite ORDER BY libelle");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une spécialité par son identifiant.
     *
     * @param mixed $id Identifiant de la spécialité
     * @return array|false Tableau associatif de la spécialité, ou false si non trouvée
     */
    public function trouverParId($id) {
        $stmt = $this->bdd->prepare("SELECT * FROM specialite WHERE id_specialite = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
