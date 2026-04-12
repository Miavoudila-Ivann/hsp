<?php
namespace repository;

require_once __DIR__ . '/../modele/Offre.php';
use modele\Offre;

/**
 * Gère les requêtes SQL liées aux offres d'emploi publiées par les entreprises.
 *
 * Permet de créer, modifier, supprimer et lister les offres d'emploi,
 * ainsi que de retrouver le nom d'une entreprise à partir d'un identifiant.
 */
class OffreRepository {
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
     * Insère une nouvelle offre d'emploi en base de données.
     *
     * @param mixed $offre L'objet offre à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
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

    /**
     * Met à jour les informations d'une offre d'emploi existante.
     *
     * @param mixed $offre L'objet offre avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
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

    /**
     * Supprime une offre d'emploi par son identifiant.
     *
     * @param mixed $id Identifiant de l'offre à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimer($id) {
        $stmt = $this->bdd->prepare("DELETE FROM offre WHERE id_offre = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupère toutes les offres d'emploi sous forme d'objets Offre.
     *
     * @return array Tableau d'objets Offre
     */
    public function findAll(): array {
        $stmt = $this->bdd->prepare("SELECT * FROM offre");
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $offres = [];
        foreach ($results as $row) {
            // Crée un objet Offre à partir de chaque ligne du résultat
            $offres[] = new Offre($row);
        }

        return $offres;
    }

    /**
     * Récupère le nom d'une entreprise à partir de son identifiant.
     *
     * Recherche dans la table entreprise et retourne uniquement le nom_entreprise.
     *
     * @param int $id Identifiant de l'entreprise
     * @return string|null Le nom de l'entreprise, ou null si non trouvée
     */
    public function findById(int $id) {
        $stmt = $this->bdd->prepare("SELECT * FROM entreprise WHERE id_entreprise = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return $data['nom_entreprise'];
        }

        return null;
    }
}
?>
