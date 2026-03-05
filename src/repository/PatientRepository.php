<?php
namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../modele/Patient.php';

use \PDO;
use PDOException;
use \Patient;

class PatientRepository
{
    private $bdd;

    public function __construct(\PDO $bdd) {
        $this->bdd = $bdd;
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM patient ORDER BY id_patient ASC";
            $stmt = $this->bdd->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur findAll patients : ' . $e->getMessage());
            return [];
        }
    }

    public function findById($id): ?Patient
    {
        try {
            $stmt = $this->bdd->prepare("SELECT * FROM patient WHERE id_patient = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($data) {
                return new Patient(
                    $data['id_patient'],
                    $data['nom'],
                    $data['prenom'],
                    $data['num_secu'],
                    $data['email'],
                    $data['telephone'],
                    $data['adresse']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log('Erreur findById patient : ' . $e->getMessage());
            return null;
        }
    }

    public function ajouter(Patient $patient): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                INSERT INTO patient (nom, prenom, num_secu, email, telephone, adresse)
                VALUES (:nom, :prenom, :num_secu, :email, :telephone, :adresse)
            ");
            return $stmt->execute([
                ':nom'       => $patient->getNom(),
                ':prenom'    => $patient->getPrenom(),
                ':num_secu'  => $patient->getNumSecu(),
                ':email'     => $patient->getEmail(),
                ':telephone' => $patient->getTelephone(),
                ':adresse'   => $patient->getAdresse(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur ajouter patient : ' . $e->getMessage());
            return false;
        }
    }

    public function modifier(Patient $patient): bool
    {
        try {
            $stmt = $this->bdd->prepare("
                UPDATE patient
                SET nom = :nom,
                    prenom = :prenom,
                    num_secu = :num_secu,
                    email = :email,
                    telephone = :telephone,
                    adresse = :adresse
                WHERE id_patient = :id_patient
            ");
            return $stmt->execute([
                ':id_patient' => $patient->getIdPatient(),
                ':nom'        => $patient->getNom(),
                ':prenom'     => $patient->getPrenom(),
                ':num_secu'   => $patient->getNumSecu(),
                ':email'      => $patient->getEmail(),
                ':telephone'  => $patient->getTelephone(),
                ':adresse'    => $patient->getAdresse(),
            ]);
        } catch (PDOException $e) {
            error_log('Erreur modifier patient : ' . $e->getMessage());
            return false;
        }
    }

    public function supprimer($id): bool
    {
        try {
            $stmt = $this->bdd->prepare("DELETE FROM patient WHERE id_patient = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('Erreur supprimer patient : ' . $e->getMessage());
            return false;
        }
    }
}
?>
