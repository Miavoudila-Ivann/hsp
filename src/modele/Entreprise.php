<?php
namespace modele;

class Entreprise
{
    private int $id;
    private string $nom;
    private string $rue;
    private string $ville;
    private int $cd;
    private string $siteWeb;
    private ?string $email;
    private ?string $mdp;
    private string $status;

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): void
    {
        $this->mdp = $mdp;
    }

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? $data['id_entreprise'] ?? 0;
        $this->nom = $data['nom_entreprise'] ?? $data['nom'] ?? '';
        $this->rue = $data['rue_entreprise'] ?? '';
        $this->ville = $data['ville_entreprise'] ?? '';
        $this->cd = $data['cd_entreprise'] ?? 00000;
        $this->siteWeb = $data['site_web'] ?? '';
        $this->email = $data['email'] ?? null;
        $this->mdp = $data['mdp'] ?? null;
        $this->status = $data['status'] ?? 'Attente';
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getRue(): string
    {
        return $this->rue;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function getCd(): ?int
    {
        return $this->cd;
    }

    public function getSiteWeb(): string
    {
        return $this->siteWeb;
    }

    public function getAdresse(): string
    {
        $parts = array_filter([$this->rue, $this->ville, $this->cd]);
        return implode(', ', $parts);
    }

    // --- Setters ---
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setRue(string $rue): void
    {
        $this->rue = $rue;
    }

    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    public function setCd(int $cd): void
    {
        $this->cd = $cd;
    }

    public function setSiteWeb(string $siteWeb): void
    {
        $this->siteWeb = $siteWeb;
    }
}
