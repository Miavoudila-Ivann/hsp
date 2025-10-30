<?php

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Utilisateur.php';

class UtilisateurRepository
{
    private $bdd;

    public function __construct(PDO $bdd)
    {
        $this->bdd = $bdd;
    }

    // les méthodes, comme connexion(), etc.


    /**
     * Inscription d'un nouvel utilisateur
     */

    public function inscription(array $data): array
    {
        // Nettoyage du mot de passe et de l'email
        $password = trim($data['password']);
        $email = trim($data['email']);

        // Validation mot de passe
        if (strlen($password) < 8) {
            return ['success' => false, 'error' => "Le mot de passe doit contenir au moins 8 caractères."];
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return ['success' => false, 'error' => "Le mot de passe doit contenir au moins une majuscule."];
        }
        if (!preg_match('/[a-z]/', $password)) {
            return ['success' => false, 'error' => "Le mot de passe doit contenir au moins une minuscule."];
        }
        if (!preg_match('/[0-9]/', $password)) {
            return ['success' => false, 'error' => "Le mot de passe doit contenir au moins un chiffre."];
        }
        if (!preg_match('/[\W_]/', $password)) {
            return ['success' => false, 'error' => "Le mot de passe doit contenir au moins un caractère spécial."];
        }

        // Vérifier si l’email existe déjà
        $sql = "SELECT id_utilisateur FROM utilisateur WHERE email = ?";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => "Cet email est déjà utilisé."];
        }

        // Hasher le mot de passe correctement
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l’utilisateur en base
        $sql = "INSERT INTO utilisateur (nom, prenom, email, rue, cd, ville, mdp) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->bdd->prepare($sql);
        $result = $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $email,
            $data['rue'],
            $data['code_postal'],
            $data['ville'],
            $hashedPassword
        ]);

        if ($result) {
            return ['success' => true, 'error' => null];
        } else {
            return ['success' => false, 'error' => "Erreur lors de l'inscription."];
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
        $email = trim($email);
        $password = trim($password);

        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // Email non trouvé
        }

        // Vérification mot de passe avec hash
        if (password_verify($password, $user['mdp'])) {
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
            var_dump("Mot de passe incorrect");
            var_dump("Saisi :", $password);
            var_dump("Hash base :", $user['mdp']);
            var_dump("password_verify :", password_verify($password, $user['mdp']));
            return false;
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
    public function supprimerUtilisateur(Utilisateur $utilisateur)
    {
        $req = $this->bdd->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur');
        return $req->execute([':id_utilisateur' => $utilisateur->getIdUtilisateur()]);
    }
}
?>
