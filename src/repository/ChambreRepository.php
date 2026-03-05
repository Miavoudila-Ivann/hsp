<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Chambre.php';

use \PDO;
use PDOException;
use \Chambre;

class ChambreRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM chambre ORDER BY id_chambre ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll chambres : ' . $e->getMessage());
            return [];
        }
    }

    public function findById($id): ?Chambre
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM chambre WHERE id_chambre = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Chambre(
                    $data['id_chambre'],
                    $data['numero'],
                    $data['disponible'],
                    $data['ref_hopital']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById chambre : ' . $e->getMessage());
            return null;
        }
    }

    public function findDisponibles(): array
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM chambre WHERE disponible = 1 ORDER BY id_chambre ASC");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findDisponibles chambre : ' . $e->getMessage());
            return [];
        }
    }

    public function setDisponible($id, bool $bool): bool
    {
        try {
            $stmt = $this->bdd->prepare("UPDATE chambre SET disponible = :disponible WHERE id_chambre = :id");
            return $stmt->execute([':disponible' => $bool ? 1 : 0, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur setDisponible chambre : ' . $e->getMessage());
            return false;
        }
    }

    public function ajouter(Chambre $chambre): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO chambre (numero, disponible, ref_hopital)
                VALUES (:numero, :disponible, :ref_hopital)
            ");
            return $stmt->execute([
                ':numero'      => $chambre->getNumero(),
                ':disponible'  => $chambre->getDisponible() ?? 1,
                ':ref_hopital' => $chambre->getRefHopital(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter chambre : ' . $e->getMessage());
            return false;
        }
    }

    public function modifier(Chambre $chambre): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE chambre
                SET numero = :numero,
                    disponible = :disponible,
                    ref_hopital = :ref_hopital
                WHERE id_chambre = :id_chambre
            ");
            return $stmt->execute([
                ':id_chambre'  => $chambre->getIdChambre(),
                ':numero'      => $chambre->getNumero(),
                ':disponible'  => $chambre->getDisponible(),
                ':ref_hopital' => $chambre->getRefHopital(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier chambre : ' . $e->getMessage());
            return false;
        }
    }

    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM chambre WHERE id_chambre = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer chambre : ' . $e->getMessage());
            return false;
        }
    }
}
?>
