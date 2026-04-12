<?php
namespace modele;

/**
 * Classe Offre — Représente une offre d'emploi publiée dans le cadre du recrutement hospitalier.
 *
 * Une offre est créée par un utilisateur administrateur ou une entreprise partenaire.
 * Elle décrit un poste à pourvoir avec ses missions, son salaire, son type de contrat
 * et son état de publication (active, archivée, etc.).
 */
class Offre {

    /** @var mixed Identifiant unique de l'offre d'emploi */
    private $id_offre;

    /** @var mixed Intitulé du poste proposé */
    private $titre;

    /** @var mixed Présentation générale du poste et du contexte de recrutement */
    private $description;

    /** @var mixed Détail des missions et responsabilités attendues pour ce poste */
    private $mission;

    /** @var mixed Rémunération proposée pour ce poste (montant brut ou fourchette) */
    private $salaire;

    /** @var mixed Type de contrat proposé (ex : 'CDI', 'CDD', 'Stage', 'Alternance') */
    private $type_offre;

    /** @var mixed État de visibilité de l'offre (ex : 'Active', 'Archivée', 'Brouillon') */
    private $etat;

    /** @var mixed Clé étrangère vers l'utilisateur ayant publié l'offre */
    private $ref_utilisateur;

    /** @var mixed Clé étrangère vers l'entreprise associée à cette offre */
    private $ref_entreprise;

    /** @var mixed Date à laquelle l'offre a été publiée */
    private $date_publication;

    /**
     * Initialise une offre d'emploi à partir d'un tableau associatif de données.
     *
     * Toutes les clés du tableau sont obligatoires pour l'instanciation.
     *
     * @param array $data Tableau associatif contenant les champs de l'offre
     */
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

    // --- Getters ---

    /** @return mixed */
    public function getIdOffre() { return $this->id_offre; }

    /** @return mixed */
    public function getTitre() { return $this->titre; }

    /** @return mixed */
    public function getDescription() { return $this->description; }

    /** @return mixed */
    public function getMission() { return $this->mission; }

    /** @return mixed */
    public function getSalaire() { return $this->salaire; }

    /** @return mixed */
    public function getTypeOffre() { return $this->type_offre; }

    /** @return mixed */
    public function getEtat() { return $this->etat; }

    /** @return mixed */
    public function getRefUtilisateur() { return $this->ref_utilisateur; }

    /** @return mixed */
    public function getRefEntreprise() { return $this->ref_entreprise; }

    /** @return mixed */
    public function getDatePublication() { return $this->date_publication; }

    // --- Setters ---

    /** @param mixed $id */
    public function setIdOffre($id) { $this->id_offre = $id; }

    /** @param mixed $val */
    public function setTitre($val) { $this->titre = $val; }

    /** @param mixed $val */
    public function setDescription($val) { $this->description = $val; }

    /** @param mixed $val */
    public function setMission($val) { $this->mission = $val; }

    /** @param mixed $val */
    public function setSalaire($val) { $this->salaire = $val; }

    /** @param mixed $val */
    public function setTypeOffre($val) { $this->type_offre = $val; }

    /** @param mixed $val */
    public function setEtat($val) { $this->etat = $val; }

    /** @param mixed $val */
    public function setRefUtilisateur($val) { $this->ref_utilisateur = $val; }

    /** @param mixed $val */
    public function setRefEntreprise($val) { $this->ref_entreprise = $val; }

    /** @param mixed $val */
    public function setDatePublication($val) { $this->date_publication = $val; }
}
?>
