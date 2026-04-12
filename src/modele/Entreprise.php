<?php
namespace modele;

/**
 * Classe Entreprise — Représente une entreprise partenaire ou prestataire de l'hôpital.
 *
 * Une entreprise peut publier des offres d'emploi et candidater pour des partenariats.
 * Son statut de validation (ex : 'Attente', 'Validée') est géré par l'administration.
 * Le constructeur accepte un tableau associatif issu de la base de données.
 */
class Entreprise
{
    /** @var int Identifiant unique de l'entreprise */
    private int $id;

    /** @var string Raison sociale de l'entreprise */
    private string $nom;

    /** @var string Rue du siège social de l'entreprise */
    private string $rue;

    /** @var string Ville du siège social de l'entreprise */
    private string $ville;

    /** @var int Code postal du siège social de l'entreprise */
    private int $cd;

    /** @var string URL du site web de l'entreprise */
    private string $siteWeb;

    /** @var string|null Adresse e-mail de contact de l'entreprise (optionnel) */
    private ?string $email;

    /** @var string|null Mot de passe hashé pour l'accès au compte entreprise (optionnel) */
    private ?string $mdp;

    /** @var string Statut de validation du compte entreprise (ex : 'Attente', 'Validée') */
    private string $status;

    /**
     * Initialise une entreprise à partir d'un tableau associatif de données.
     *
     * Accepte les clés issues de différentes conventions de nommage de la base de données
     * (ex : 'id_entreprise' ou 'id', 'nom_entreprise' ou 'nom').
     *
     * @param array $data Tableau associatif contenant les données de l'entreprise
     */
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

    /** @return int|null */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return string|null */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /** @return string|null */
    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    /** @return string */
    public function getNom(): string
    {
        return $this->nom;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    /** @return string */
    public function getRue(): string
    {
        return $this->rue;
    }

    /** @return string */
    public function getVille(): string
    {
        return $this->ville;
    }

    /** @return int|null */
    public function getCd(): ?int
    {
        return $this->cd;
    }

    /** @return string */
    public function getSiteWeb(): string
    {
        return $this->siteWeb;
    }

    /**
     * Construit et retourne l'adresse complète de l'entreprise (rue, ville, code postal).
     *
     * Les champs vides ou nuls sont ignorés dans la concaténation.
     *
     * @return string Adresse formatée
     */
    public function getAdresse(): string
    {
        $parts = array_filter([$this->rue, $this->ville, $this->cd]);
        return implode(', ', $parts);
    }

    // --- Setters ---

    /** @param string|null $email */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /** @param string|null $mdp */
    public function setMdp(?string $mdp): void
    {
        $this->mdp = $mdp;
    }

    /** @param string $nom */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /** @param string $status */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /** @param string $rue */
    public function setRue(string $rue): void
    {
        $this->rue = $rue;
    }

    /** @param string $ville */
    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    /** @param int $cd */
    public function setCd(int $cd): void
    {
        $this->cd = $cd;
    }

    /** @param string $siteWeb */
    public function setSiteWeb(string $siteWeb): void
    {
        $this->siteWeb = $siteWeb;
    }
}
