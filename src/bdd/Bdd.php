<?php
class   Bdd {
    private $bdd;

    public function __construct($host = 'localhost', $dbname = 'hsp', $username = 'root', $password = '') {
        try {
            $this->bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getBdd() {
        return $this->bdd;
    }
}
?>