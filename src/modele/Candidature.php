<?php
namespace modele;

class Candidature
{
    private ?int $id_candidature;
    private string $motivation;
    private string $statut;
    private string $date_candidature;
    private int $ref_offre;
    private int $ref_utilisateur;
    private ?string $cv_path; // <--- ajouté

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

    public function getCvPath(): ?string
    {
        return $this->cv_path;
    }

    public function setCvPath(?string $cv_path): void
    {
        $this->cv_path = $cv_path;
    }

    // --- Getters ---
    public function getIdCandidature(): ?int {
        return $this->id_candidature;
    }

    public function getMotivation(): string {
        return $this->motivation;
    }

    public function getStatut(): string {
        return $this->statut;
    }

    public function getDateCandidature(): string {
        return $this->date_candidature;
    }

    public function getRefOffre(): int {
        return $this->ref_offre;
    }

    public function getRefUtilisateur(): int {
        return $this->ref_utilisateur;
    }

    // --- Setters ---
    public function setIdCandidature(?int $id_candidature): void {
        $this->id_candidature = $id_candidature;
    }

    public function setMotivation(string $motivation): void {
        $this->motivation = $motivation;
    }

    public function setStatut(string $statut): void {
        $this->statut = $statut;
    }

    public function setDateCandidature(string $date_candidature): void {
        $this->date_candidature = $date_candidature;
    }

    public function setRefOffre(int $ref_offre): void {
        $this->ref_offre = $ref_offre;
    }

    public function setRefUtilisateur(int $ref_utilisateur): void {
        $this->ref_utilisateur = $ref_utilisateur;
    }
}
?>
