<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Utilisateur.php';

use \PDO;
use PDOException;
use \Utilisateur;

/**
 * Gère les requêtes SQL liées aux utilisateurs du système (médecins, secrétaires, admins).
 *
 * Prend en charge l'inscription, la connexion, la modification du profil,
 * la suppression et la récupération des comptes utilisateurs.
 */
class UtilisateurRepository
{
    /** @var \PDO Instance de connexion à la base de données */
    private $bdd;

    /**
     * Initialise le repository avec une connexion PDO.
     *
     * @param \PDO $bdd Instance de connexion à la base de données
     */
    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    /**
     * Inscrit un nouvel utilisateur après validation du mot de passe et vérification de l'unicité de l'email.
     *
     * Valide côté serveur que le mot de passe contient au moins 12 caractères, une majuscule,
     * une minuscule, un chiffre et un caractère spécial. Hache le mot de passe avant insertion.
     * Le compte est créé avec le statut "Attente" en attendant validation par un administrateur.
     *
     * @param array $data Tableau de données du formulaire d'inscription (prenom, nom, email, password, rue, cd, ville)
     * @return array Tableau avec les clés 'success' (bool) et 'error' (string)
     */
    public function inscription(array $data): array {
        try {
            // Validation de la complexité du mot de passe côté serveur
            $pwd = $data['password'];
            if (strlen($pwd) < 12 || !preg_match('/[A-Z]/', $pwd) || !preg_match('/[a-z]/', $pwd) || !preg_match('/[0-9]/', $pwd) || !preg_match('/[\W_]/', $pwd)) {
                return ['success' => false, 'error' => 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.'];
            }

            // Vérifie que l'adresse email n'est pas déjà enregistrée
            $stmt = $this->bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
            $stmt->execute(['email' => $data['email']]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'error' => 'Email déjà utilisé.'];
            }

            // Hachage sécurisé du mot de passe avec BCrypt
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            // Insertion du nouvel utilisateur avec le statut "Attente"
            $stmt = $this->bdd->prepare("
    INSERT INTO utilisateur (prenom, nom, email, mdp, rue, cd, ville, status)
    VALUES (:prenom, :nom, :email, :mdp, :rue, :cd, :ville, 'Attente')
");

            $stmt->execute([
                'prenom' => $data['prenom'],
                'nom'    => $data['nom'],
                'email'  => $data['email'],
                'mdp'    => $hashedPassword,
                'rue'    => $data['rue'],
                'cd'     => $data['cd'],
                'ville'  => $data['ville'],
            ]);

            return ['success' => true, 'error' => ''];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Erreur base de données : ' . $e->getMessage()];
        }
    }

    /**
     * Récupère un utilisateur à partir de son adresse email.
     *
     * Retourne un objet Utilisateur complet si l'email est trouvé, null sinon.
     *
     * @param string $email Adresse e-mail de l'utilisateur
     * @return Utilisateur|null L'objet Utilisateur correspondant, ou null si non trouvé
     */
    public function getUtilisateurParMail($email)
    {
        $stmt = $this->bdd->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($userData['id_utilisateur']);
            $utilisateur->setPrenom($userData['prenom']);
            $utilisateur->setNom($userData['nom']);
            $utilisateur->setEmail($userData['email']);
            $utilisateur->setMdp($userData['mdp']);
            $utilisateur->setRole($userData['role'] ?? 'user');
            $utilisateur->setRue($userData['rue']);
            $utilisateur->setCd($userData['cd']);
            $utilisateur->setVille($userData['ville']);
            return $utilisateur;
        }

        return null;
    }

    /**
     * Authentifie un utilisateur par email et mot de passe.
     *
     * Nettoie les entrées, recherche l'utilisateur en base, puis vérifie le mot de passe
     * haché via password_verify(). Retourne un objet Utilisateur complet si les identifiants sont corrects.
     *
     * @param string $email    Adresse e-mail de l'utilisateur
     * @param string $password Mot de passe en clair saisi par l'utilisateur
     * @return Utilisateur|false L'objet Utilisateur si la connexion est valide, faux sinon
     */
    public function connexion(string $email, string $password)
    {
        // Suppression des espaces superflus en début/fin de saisie
        $email    = trim($email);
        $password = trim($password);

        // Vérifie que les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            return false;
        }

        try {
            $sql  = "SELECT * FROM utilisateur WHERE email = ?";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                return false;
            }

            // Vérifie le mot de passe en le comparant au hash stocké en base
            if (password_verify($password, $user['mdp'])) {
                return new Utilisateur(
                    $user['id_utilisateur'],
                    $user['prenom'],
                    $user['nom'],
                    $user['email'],
                    $user['mdp'],
                    $user['role'],
                    $user['rue'],
                    $user['cd'],
                    $user['ville'],
                    $user['status']
                );
            } else {
                return false;
            }

        } catch (PDOException $e) {
            error_log("Erreur connexion utilisateur : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les utilisateurs avec leurs informations principales, triés par identifiant.
     *
     * Exclut le mot de passe des colonnes sélectionnées pour des raisons de sécurité.
     *
     * @return array Tableau associatif de tous les utilisateurs
     */
    public function findAll(): array
    {
        try {
            // Sélectionne uniquement les colonnes non sensibles (sans le mot de passe)
            $sql  = "SELECT id_utilisateur, nom, prenom, email, role, ville, status
                FROM utilisateur
                ORDER BY id_utilisateur ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll utilisateurs : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour les informations d'un utilisateur existant.
     *
     * Permet de modifier le profil, le rôle et le statut d'un utilisateur.
     * Les champs optionnels (rue, cd, ville, role, status) ont des valeurs par défaut.
     *
     * @param Utilisateur $utilisateur L'objet utilisateur avec les nouvelles données
     * @return bool Vrai si la modification a réussi, faux sinon
     */
    public function modifierUtilisateur(Utilisateur $utilisateur)
    {
        $req = $this->bdd->prepare('
        UPDATE utilisateur
        SET prenom = :prenom,
            nom = :nom,
            email = :email,
            rue = :rue,
            cd = :cd,
            ville = :ville,
            role = :role,
            status = :status
        WHERE id_utilisateur = :id_utilisateur
    ');

        return $req->execute([
            ':id_utilisateur' => $utilisateur->getIdUtilisateur(),
            ':prenom'         => $utilisateur->getPrenom(),
            ':nom'            => $utilisateur->getNom(),
            ':email'          => $utilisateur->getEmail(),
            ':rue'            => $utilisateur->getRue() ?? '',
            ':cd'             => $utilisateur->getCd() ?? 0,
            ':ville'          => $utilisateur->getVille() ?? '',
            ':role'           => $utilisateur->getRole() ?? 'user',
            ':status'         => $utilisateur->getStatus() ?? 'Attente',
        ]);
    }

    /**
     * Supprime un utilisateur par son identifiant.
     *
     * @param mixed $id Identifiant de l'utilisateur à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    public function supprimerUtilisateur($id)
    {
        $req = $this->bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id');
        return $req->execute([':id' => $id]);
    }
}
?>
