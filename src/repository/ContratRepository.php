<?php
namespace repository; // Doit être en première ligne avant tout require/include

use Contrat;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Contrat.php';

class ContratRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) { // ✅ backslash pour la classe globale
        $this->bdd = $bdd;
    }

    public function findAll(): array {
        $stmt = $this->bdd->query("SELECT * FROM contrat ORDER BY date_debut DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // ✅ fetchAll aussi avec \PDO
    }



    public function findById(int $id) {
        $stmt = $this->bdd->prepare("SELECT * FROM contrat WHERE id_contrat = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function ajouter(Contrat $contrat): bool {
        $stmt = $this->bdd->prepare(
            "INSERT INTO contrat (ref_utilisateur, date_debut, date_fin) 
             VALUES (:ref_utilisateur, :date_debut, :date_fin)"
        );
        return $stmt->execute([
            ':ref_utilisateur' => $contrat->getRefUtilisateur(),
            ':date_debut' => $contrat->getDateDebut(),
            ':date_fin' => $contrat->getDateFin()
        ]);
    }

    public function modifier(Contrat $contrat): bool {
        $stmt = $this->bdd->prepare(
            "UPDATE contrat SET ref_utilisateur = :ref_utilisateur, date_debut = :date_debut, date_fin = :date_fin 
             WHERE id_contrat = :id"
        );
        return $stmt->execute([
            ':ref_utilisateur' => $contrat->getRefUtilisateur(),
            ':date_debut' => $contrat->getDateDebut(),
            ':date_fin' => $contrat->getDateFin(),
            ':id' => $contrat->getIdContrat()
        ]);
    }

    public function supprimer(int $id): bool {
        $stmt = $this->bdd->prepare("DELETE FROM contrat WHERE id_contrat = :id");
        return $stmt->execute([':id' => $id]);
    }
}
