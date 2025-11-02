<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Utilisateur.php';

namespace repository;

use \PDO;
use \Utilisateur; // si ta classe Utilisateur est dans le namespace global

class UtilisateurRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }


    // les méthodes, comme connexion(), etc.


    /**
     * Inscription d'un nouvel utilisateur
     */

    public function inscription(array $data): array {
        try {
            // Vérifie si l'email existe déjà
            $stmt = $this->bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
            $stmt->execute(['email' => $data['email']]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'error' => 'Email déjà utilisé.'];
            }

            // Hash du mot de passe
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            // Insertion en base
            $stmt = $this->bdd->prepare("
            INSERT INTO utilisateur (prenom, nom, email, mdp, role, rue, cd, ville)
            VALUES (:prenom, :nom, :email, :mdp, :role, :rue, :cd, :ville)
        ");

            $stmt->execute([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'mdp' => $hashedPassword,
                'role' => $data['role'],   // rôle bien pris en compte
                'rue' => $data['rue'],
                'cd' => $data['cd'],
                'ville' => $data['ville']
            ]);

            return ['success' => true, 'error' => ''];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Erreur base de données : ' . $e->getMessage()];
        }
    }





    /**
     * Récupération d’un utilisateur par email
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
     * Connexion d’un utilisateur
     */
    public function connexion(string $email, string $password)
    {
        // Nettoyage des entrées
        $email = trim($email);
        $password = trim($password);

        // Vérifie que les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            return false;
        }

        try {
            // Prépare et exécute la requête
            $sql = "SELECT * FROM utilisateur WHERE email = ?";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Si aucun utilisateur trouvé
            if (!$user) {
                return false;
            }

            // Vérifie le mot de passe (haché)
            if (password_verify($password, $user['mdp'])) {

                // Retourne un objet Utilisateur si le login est valide
                return new Utilisateur(
                    $user['id_utilisateur'],
                    $user['prenom'],
                    $user['nom'],
                    $user['email'],
                    $user['mdp'], // hash stocké
                    $user['role'],
                    $user['rue'],
                    $user['cd'],
                    $user['ville']
                );

            } else {
                // En mode debug uniquement :
                 var_dump("Mot de passe incorrect", $password, $email);
                return false;
            }

        } catch (PDOException $e) {
            // Log en interne ou retour d’erreur
            error_log("Erreur connexion utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT id_utilisateur, nom, prenom, email, role, ville FROM utilisateur ORDER BY id_utilisateur ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur findAll utilisateurs : " . $e->getMessage());
            return [];
        }
    }




    /**
     * Modification d’un utilisateur
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
                ville = :ville
            WHERE id_utilisateur = :id_utilisateur
        ');

        return $req->execute([
            ':id_utilisateur' => $utilisateur->getIdUtilisateur(),
            ':prenom' => $utilisateur->getPrenom(),
            ':nom'    => $utilisateur->getNom(),
            ':email'  => $utilisateur->getEmail(),
            ':rue'    => $utilisateur->getRue() ?? '',
            ':cd'     => $utilisateur->getCd() ?? 0,
            ':ville'  => $utilisateur->getVille() ?? ''
        ]);
    }

    /**
     * Suppression d’un utilisateur
     */
    public function supprimerUtilisateur($id)
    {
        $req = $this->bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id');
        return $req->execute([':id' => $id]);
    }

}

include __DIR__ . '/../vue/footer.php';

?>
