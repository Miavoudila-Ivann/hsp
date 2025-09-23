<?php
class Utilisateur
{
    private $nom_uti;
    private $idUtilisateur;
    private $prenom_uti;
    private $mail_uti;
    private $mdp;
    private $role;

    /**
     * @return mixed
     */
    public function getNomUti()
    {
        return $this->nom_uti;
    }

    /**
     * @param mixed $nom_uti
     */
    public function setNomUti($nom_uti)
    {
        $this->nom_uti = $nom_uti;
    }

    /**
     * @return mixed
     */
    public function getPrenomUti()
    {
        return $this->prenom_uti;
    }

    /**
     * @param mixed $prenom_uti
     */
    public function setPrenomUti($prenom_uti)
    {
        $this->prenom_uti = $prenom_uti;
    }

    /**
     * @return mixed
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * @param mixed $idUtilisateur
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    /**
     * @return mixed
     */
    public function getMailUti()
    {
        return $this->mail_uti;
    }

    /**
     * @param mixed $mail_uti
     */
    public function setMailUti($mail_uti)
    {
        $this->mail_uti = $mail_uti;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }




    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }
    public function hydrate($donnees)
    {
        foreach ($donnees as $key => $value)
        {
            $method = 'set'.ucfirst($key);

            if (is_callable(array($this, $method)))
            {
                $this->$method($value);
            }
        }
    }


}
?>