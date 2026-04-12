<?php
namespace repository;

require_once __DIR__ . '/../modele/Entreprise.php';

use modele\Entreprise;
use PDO;
use PDOException;

/**
 * Gère les requêtes SQL liées aux entreprises partenaires.
 *
 * Permet d'inscrire, authentifier, lister, modifier et supprimer
 * les entreprises qui publient des offres d'emploi sur la plateforme.
 */
class EntrepriseRepository
{
    /** @var \PDO Instance de connexion à la base de données */
    private $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd;
    }

    /**
     * Insère une nouvelle entreprise en base de données.
     *
     * @param Entreprise $entreprise L'objet entreprise à enregistrer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    public function ajouter(Entreprise $entreprise) {
        $stmt = $this->bdd->prepare(
            "INSERT INTO entreprise (nom_entreprise, rue_entreprise, ville_entreprise, cd_entreprise, site_web, email, mdp, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $entreprise->getNom(),
            $entreprise->getRue(),
            $entreprise->getVille(),
            $entreprise->getCd(),
            $entreprise->getSiteWeb(),
            $entreprise->getEmail(),
            $entreprise->getMdp(),
            $entreprise->getStatus()
        ]);
    }

    /**
     * Inscrit une nouvelle entreprise (délègue à la méthode ajouter).
     *
     * @param Entreprise $entreprise L'objet entreprise à inscrire
     * @return bool Vrai si l'inscription a réussi, faux sinon
     */
    public function inscrireEntreprise(Entreprise $entreprise): bool {
        return $this->ajouter($entreprise);
    }

    /**
     * Authentifie une entreprise par email et mot de passe.
     *
     * Vérifie que l'email existe en base puis compare le mot de passe
     * avec le hash stocké via password_verify().
     *
     * @param string $email    Adresse e-mail de l'entreprise
     * @param string $password Mot de passe en clair
     * @return Entreprise|false L'objet Entreprise si la connexion est valide, faux sinon
     */
    public function connexionEntreprise(string $email, string $password) {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM entreprise WHERE email = ?");
            $stmt->execute([$email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return false;
            }

            // Vérifie le mot de passe haché stocké en base
            if (password_verify($password, $data['mdp'])) {
                return new Entreprise($data);
            }

            return false;
        } catch (PDOException $e) {
            error_log("Erreur connexion entreprise : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère une entreprise par son identifiant (utilise la colonne "id").
     *
     * @param mixed $id Identifiant de l'entreprise
     * @return Entreprise|null L'objet Entreprise correspondant, ou null si non trouvé
     */
    public function getEntrepriseParId($id)
    {
        $req = $this->bdd->prepare('SELECT * FROM entreprise WHERE id = :id');
        $req->execute(['id' => $id]);
        $data = $req->fetch(PDO::FETCH_ASSOC);

        return $data ? new Entreprise($data) : null;
    }

    /**
     * Récupère toutes les entreprises avec leurs informations principales, triées par nom.
     *
     * Sélectionne uniquement les colonnes utiles à l'affichage (pas le mot de passe).
     *
     * @return array Tableau d'objets Entreprise
     */
    public function findAll(): array
    {
        try {
            // Sélectionne les champs d'affichage uniquement (sans données sensibles)
            $stmt = $this->bdd->query('
    SELECT
        id_entreprise AS id,
        nom_entreprise AS nom,
        rue_entreprise,
        ville_entreprise,
        cd_entreprise AS cd,
        site_web
    FROM entreprise
    ORDER BY nom_entreprise ASC
');

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $entreprises = [];
            foreach ($results as $row) {
                $entreprises[] = new Entreprise($row);
            }
            return $entreprises;
        } catch (PDOException $e) {
            error_log('Erreur findAll entreprises : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour les informations d'une entreprise existante.
     *
     * @param Entreprise $entreprise L'objet entreprise avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifierEntreprise(Entreprise $entreprise)
    {
        $req = $this->bdd->prepare('
        UPDATE entreprise
        SET nom_entreprise = :nom,
            rue_entreprise = :rue,
            ville_entreprise = :ville,
            cd_entreprise = :cd,
            site_web = :site_web
        WHERE id_entreprise = :id
    ');

        return $req->execute([
            'nom'      => $entreprise->getNom(),
            'rue'      => $entreprise->getRue(),
            'ville'    => $entreprise->getVille(),
            'cd'       => $entreprise->getCd(),
            'site_web' => $entreprise->getSiteWeb(),
            'id'       => $entreprise->getId()
        ]);
    }

    /**
     * Supprime une entreprise par son identifiant.
     *
     * @param mixed $id Identifiant de l'entreprise à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimerEntreprise($id)
    {
        $req = $this->bdd->prepare('DELETE FROM entreprise WHERE id_entreprise = :id_entreprise');
        return $req->execute(['id_entreprise' => $id]);
    }

    /**
     * Récupère une entreprise par son identifiant (utilise la colonne "id_entreprise").
     *
     * @param int $id Identifiant de l'entreprise
     * @return Entreprise|null L'objet Entreprise correspondant, ou null si non trouvé
     */
    public function findById(int $id): ?Entreprise
    {
        $stmt = $this->bdd->prepare('SELECT * FROM entreprise WHERE id_entreprise = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return new Entreprise($data);
        }

        return null;
    }
}
