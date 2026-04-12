<?php

/**
 * Classe Bdd — Gestionnaire de connexion à la base de données.
 *
 * Fournit une instance PDO partagée pour l'ensemble de l'application.
 * La connexion est établie dans le constructeur via PDO avec le charset UTF-8.
 * En cas d'échec, l'exécution est interrompue avec un message d'erreur.
 */
class Bdd {

    /** @var PDO Instance PDO représentant la connexion active à la base de données */
    private $bdd;

    /**
     * Initialise la connexion PDO à la base de données MySQL.
     *
     * @param string $host     Hôte du serveur MySQL (défaut : 'localhost')
     * @param string $dbname   Nom de la base de données (défaut : 'hsp')
     * @param string $username Nom d'utilisateur MySQL (défaut : 'root')
     * @param string $password Mot de passe MySQL (défaut : '')
     */
    public function __construct($host = 'localhost', $dbname = 'hsp', $username = 'root', $password = '') {
        try {
            $this->bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance PDO de la connexion à la base de données.
     *
     * @return PDO
     */
    public function getBdd() {
        return $this->bdd;
    }
}
?>
