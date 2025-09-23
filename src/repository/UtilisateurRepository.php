
<?php
class UtilisateurRepository
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = new Bdd();
    }

    public function inscription(Utilisateur $utilisateur)
    {
        $hashedPassword = password_hash($utilisateur->getMdp(), PASSWORD_DEFAULT);

        $req = $this->bdd->getBdd()->prepare('INSERT INTO utilisateur (prenom_uti, nom_uti, mail_uti, mdp) VALUES (:prenom_uti, :nom_uti, :mail_uti, :mdp)');
        $success = $req->execute([
            "nom_uti" => $utilisateur->getNom(),
            "prenom_uti" => $utilisateur->getPrenom(),
            "email_uti" => $utilisateur->getEmail(),
            "mdp" => $hashedPassword
        ]);

        return $success;
    }

    public function connexion($mail_uti, $mdp)
    {
        $req = $this->bdd->getBdd()->prepare('SELECT * FROM utilisateur WHERE mail_uti = :mail_uti');
        $req->execute(['mail' => $mail_uti]);

        $utilisateur = $req->fetch(PDO::FETCH_ASSOC);
        var_dump($utilisateur);
        var_dump(password_verify($mdp, $utilisateur['mdp']));
        if ($utilisateur && password_verify($mdp, $utilisateur['mdp'])) {
            return $utilisateur;
        }

        return false;
    }

    public function getUtilisateur($email) {
        $query = "SELECT * FROM utilisateur WHERE mail_uti = :mail_uti";
        $stmt = $this->bdd->getBdd()->prepare($query);
        $stmt->bindValue(':mail_uti', $mail_uti);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            return new Utilisateur($userData);
        }
        return null;
    }
    public function modifierUtilisateur(Utilisateur $utilisateur)
    {

        $req = $this->bdd->getBdd()->prepare('UPDATE utilisateur 
            SET prenom_uti = :prenom_uti, nom_uti = :nom, $mail_uti = :mail_uti, role = :role
            WHERE id_utilisateur = :id_utilisateur');
        return $req->execute([
            "id_utilisateur" => $utilisateur->getIdUtilisateur(),
            "nom_uti" => $utilisateur->getNom(),
            "prenom_uti" => $utilisateur->getPrenom(),
            "mail_uti" => $utilisateur->getEmail(),
            "role" => $utilisateur->getRole()
        ]);
    }
    public function supprimerUtilisateur(Utilisateur $utilisateur){

        $req = $this->bdd->getBdd()->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur');

        return $req->execute([
            "id_utilisateur" => $utilisateur->getIdUtilisateur(),
        ]);
    }

}
?>
