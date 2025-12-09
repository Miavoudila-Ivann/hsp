<?php
class Bdd {
    private static $instance = null;
    private $bdd;

    private function __construct($host = 'localhost', $dbname = 'hsp', $username = 'root', $password = '') {
        try {
            $this->bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getBdd() {
        return $this->bdd;
    }
}

// Fonction globale pour faciliter l'utilisation
function getBdd() {
    return Bdd::getInstance()->getBdd();
}
?>