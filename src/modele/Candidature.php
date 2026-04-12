<?php
namespace modele;

/**
 * Classe Candidature — Représente une candidature déposée par un utilisateur pour une offre d'emploi.
 *
 * Contient les informations relatives à la lettre de motivation, au statut de traitement,
 * à la date de dépôt, ainsi que les références vers l'offre et l'utilisateur concernés.
 * Un chemin vers le CV joint peut également être renseigné.
 */
class Candidature
{
    /** @var int|null Identifiant unique de la candidature (null avant insertion en base) */
    private ?int $id_candidature;

    /** @var string Lettre de motivation rédigée par le candidat */
    private string $motivation;

    /** @var string Statut de la candidature (ex : 'En attente', 'Acceptée', 'Refusée') */
    private string $statut;

    /** @var string Date à laquelle la candidature a été soumise */
    private string $date_candidature;

    /** @var int Clé étrangère vers l'offre d'emploi concernée */
    private int $ref_offre;

    /** @var int Clé étrangère vers l'utilisateur ayant déposé la candidature */
    private int $ref_utilisateur;

    /** @var string|null Chemin vers le fichier CV téléversé par le candidat (optionnel) */
    private ?string $cv_path;

    /**
     * Initialise une candidature avec toutes ses informations.
     *
     * @param int|null    $id_candidature  Identifiant de la candidature (null pour une nouvelle)
     * @param string      $motivation      Lettre de motivation du candidat
     * @param string      $statut          Statut de traitement de la candidature
     * @param string      $date_candidature Date de soumission de la candidature
     * @param int         $ref_offre       Référence de l'offre d'emploi visée
     * @param int         $ref_utilisateur Référence de l'utilisateur candidat
     * @param string|null $cv_path         Chemin vers le CV joint (optionnel)
     */
    public function __construct(?int $id_candidature, string $motivation, string $statut, string $date_candidature, int $ref_offre, int $ref_utilisateur, ?string $cv_path)
    {
        $this->id_candidature = $id_candidature;
        $this->motivation = $motivation;
        $this->statut = $statut;
        $this->date_candidature = $date_candidature;
        $this->ref_offre = $ref_offre;
        $this->ref_utilisateur = $ref_utilisateur;
        $this->cv_path = $cv_path;
    }

    /**
     * Retourne le chemin vers le fichier CV joint à la candidature.
     *
     * @return string|null
     */
    public function getCvPath(): ?string
    {
        return $this->cv_path;
    }

    /**
     * Définit le chemin vers le fichier CV joint à la candidature.
     *
     * @param string|null $cv_path
     */
    public function setCvPath(?string $cv_path): void
    {
        $this->cv_path = $cv_path;
    }

    // --- Getters ---

    /** @return int|null */
    public function getIdCandidature(): ?int {
        return $this->id_candidature;
    }

    /** @return string */
    public function getMotivation(): string {
        return $this->motivation;
    }

    /** @return string */
    public function getStatut(): string {
        return $this->statut;
    }

    /** @return string */
    public function getDateCandidature(): string {
        return $this->date_candidature;
    }

    /** @return int */
    public function getRefOffre(): int {
        return $this->ref_offre;
    }

    /** @return int */
    public function getRefUtilisateur(): int {
        return $this->ref_utilisateur;
    }

    // --- Setters ---

    /** @param int|null $id_candidature */
    public function setIdCandidature(?int $id_candidature): void {
        $this->id_candidature = $id_candidature;
    }

    /** @param string $motivation */
    public function setMotivation(string $motivation): void {
        $this->motivation = $motivation;
    }

    /** @param string $statut */
    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

    /** @param string $date_candidature */
    public function setDateCandidature(string $date_candidature): void {
        $this->date_candidature = $date_candidature;
    }

    /** @param int $ref_offre */
    public function setRefOffre(int $ref_offre): void {
        $this->ref_offre = $ref_offre;
    }

    /** @param int $ref_utilisateur */
    public function setRefUtilisateur(int $ref_utilisateur): void {
        $this->ref_utilisateur = $ref_utilisateur;
    }
}
?>
