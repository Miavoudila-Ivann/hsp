<?php
class Utilisateur {
    private $id_utilisateur;
    private $prenom;
    private $nom;
    private $email;
    private $mdp;
    private $role;
    private $rue;
    private $cd;
    private $ville;


    public function __construct($id_utilisateur = null, $prenom = null, $nom = null, $email = null, $mdp = null, $role = null, $rue = null, $cd = null, $ville = null) {
        $this->id_utilisateur = $id_utilisateur;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->role = $role;
        $this->rue = $rue;
        $this->cd = $cd;
        $this->ville = $ville;
    }


    public function getIdUtilisateur() {

        return $this->id_utilisateur;

    }
    public function getPrenom() {

        return $this->prenom;
    }
    public function getNom() {

        return $this->nom;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getMdp() {
        return $this->mdp;
    }
    public function getRole() {
        return $this->role;
    }
    public function getRue() {
        return $this->rue;
    }
    public function getCd() {
        return $this->cd;
    }
    public function getVille() {
        return $this->ville;
    }


    public function setIdUtilisateur($id_utilisateur) {
        $this->id_utilisateur = $id_utilisateur;
    }
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }
    public function setRole($role) {
        $this->role = $role;
    }
    public function setRue($rue) {
        $this->rue = $rue;
    }
    public function setCd($cd) {
        $this->cd = $cd;
    }
    public function setVille($ville) {
        $this->ville = $ville;
    }
}
?>
