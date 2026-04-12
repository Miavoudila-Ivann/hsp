<?php

/**
 * Classe Utilisateur — Représente un utilisateur du système d'information hospitalier.
 *
 * Un utilisateur peut avoir différents rôles selon son profil : administrateur, médecin,
 * secrétaire ou patient. Son accès à l'application est conditionné par son statut de validation
 * (ex : 'Actif', 'Suspendu'). Les informations d'adresse permettent de localiser l'utilisateur.
 *
 * Note : la méthode setStatus() présente un comportement incorrect (retourne au lieu d'affecter) ;
 * elle doit être corrigée pour être fonctionnelle.
 */
class Utilisateur {

    /** @var mixed Identifiant unique de l'utilisateur dans le système */
    private $id_utilisateur;

    /** @var mixed Prénom de l'utilisateur */
    private $prenom;

    /** @var mixed Nom de famille de l'utilisateur */
    private $nom;

    /** @var mixed Adresse e-mail servant d'identifiant de connexion */
    private $email;

    /** @var mixed Mot de passe hashé de l'utilisateur */
    private $mdp;

    /** @var mixed Rôle attribué à l'utilisateur (ex : 'admin', 'medecin', 'secretaire', 'utilisateur') */
    private $role;

    /** @var mixed Rue de l'adresse postale de l'utilisateur */
    private $rue;

    /** @var mixed Code postal de l'adresse postale de l'utilisateur */
    private $cd;

    /** @var mixed Ville de résidence de l'utilisateur */
    private $ville;

    /** @var mixed Statut du compte utilisateur (ex : 'Actif', 'Suspendu', 'En attente') */
    private $status;

    /**
     * Initialise un utilisateur avec ses informations personnelles, d'accès et d'adresse.
     *
     * @param mixed $id_utilisateur Identifiant de l'utilisateur
     * @param mixed $prenom         Prénom
     * @param mixed $nom            Nom de famille
     * @param mixed $email          Adresse e-mail (identifiant de connexion)
     * @param mixed $mdp            Mot de passe hashé
     * @param mixed $role           Rôle dans l'application
     * @param mixed $rue            Rue de l'adresse
     * @param mixed $cd             Code postal
     * @param mixed $ville          Ville de résidence
     * @param mixed $status         Statut du compte
     */
    public function __construct($id_utilisateur = null, $prenom = null, $nom = null, $email = null, $mdp = null, $role = null, $rue = null, $cd = null, $ville = null, $status = null) {
        $this->id_utilisateur = $id_utilisateur;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->rue = $rue;
        $this->cd = $cd;
        $this->ville = $ville;
        $this->status = $status;
    }

    // --- Getters ---

    /** @return mixed */
    public function getIdUtilisateur() {
        return $this->id_utilisateur;
    }

    /** @return mixed */
    public function getPrenom() {
        return $this->prenom;
    }

    /** @return mixed */
    public function getNom() {
        return $this->nom;
    }

    /** @return mixed */
    public function getEmail() {
        return $this->email;
    }

    /** @return mixed */
    public function getMdp() {
        return $this->mdp;
    }

    /** @return mixed */
    public function getRole() {
        return $this->role;
    }

    /** @return mixed */
    public function getRue() {
        return $this->rue;
    }

    /** @return mixed */
    public function getCd() {
        return $this->cd;
    }

    /** @return mixed */
    public function getVille() {
        return $this->ville;
    }

    /** @return string|null */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    // --- Setters ---

    /** @param mixed $id_utilisateur */
    public function setIdUtilisateur($id_utilisateur) {
        $this->id_utilisateur = $id_utilisateur;
    }

    /** @param mixed $prenom */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    /** @param mixed $nom */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /** @param mixed $email */
    public function setEmail($email) {
        $this->email = $email;
    }

    /** @param mixed $mdp */
    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    /** @param mixed $role */
    public function setRole($role) {
        $this->role = $role;
    }

    /** @param mixed $rue */
    public function setRue($rue) {
        $this->rue = $rue;
    }

    /** @param mixed $cd */
    public function setCd($cd) {
        $this->cd = $cd;
    }

    /** @param mixed $ville */
    public function setVille($ville) {
        $this->ville = $ville;
    }

    /**
     * Affecte le statut du compte utilisateur.
     *
     * @param string|null $status Statut à définir
     *
     * @todo Corriger la signature originale qui retournait $this->status au lieu d'affecter la valeur.
     */
    public function setStatus(): ?string
    {
        return $this->status;
    }
}
?>
