<?php
namespace modele;
class Offre {
    private $id_offre;
    private $titre;
    private $description;
    private $mission;
    private $salaire;
    private $type_offre;
    private $etat;
    private $ref_utilisateur;
    private $ref_entreprise;
    private $date_publication;

    public function __construct(array $data) {
        $this->id_offre = $data['id_offre'];
        $this->titre = $data['titre'];
        $this->description = $data['description'];
        $this->mission = $data['mission'];
        $this->salaire = $data['salaire'];
        $this->type_offre = $data['type_offre'];
        $this->etat = $data['etat'];
        $this->ref_utilisateur = $data['ref_utilisateur'];
        $this->ref_entreprise = $data['ref_entreprise'];
        $this->date_publication = $data['date_publication'];
    }


    // Getters (à faire pour chaque attribut, pareil pour les setters)

    public function getIdOffre() { return $this->id_offre; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getMission() { return $this->mission; }
    public function getSalaire() { return $this->salaire; }
    public function getTypeOffre() { return $this->type_offre; }
    public function getEtat() { return $this->etat; }
    public function getRefUtilisateur() { return $this->ref_utilisateur; }
    public function getRefEntreprise() { return $this->ref_entreprise; }
    public function getDatePublication() { return $this->date_publication; }

    public function setIdOffre($id) { $this->id_offre = $id; }
    public function setTitre($val) { $this->titre = $val; }
    public function setDescription($val) { $this->description = $val; }
    public function setMission($val) { $this->mission = $val; }
    public function setSalaire($val) { $this->salaire = $val; }
    public function setTypeOffre($val) { $this->type_offre = $val; }
    public function setEtat($val) { $this->etat = $val; }
    public function setRefUtilisateur($val) { $this->ref_utilisateur = $val; }
    public function setRefEntreprise($val) { $this->ref_entreprise = $val; }
    public function setDatePublication($val) { $this->date_publication = $val; }
}
?>
