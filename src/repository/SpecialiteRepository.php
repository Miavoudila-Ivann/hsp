<?php

namespace repository;
require_once 'Specialite.php';

class SpecialiteRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Ajouter une spécialité
    public function ajouter(Specialite $specialite)
    {
        $sql = "INSERT INTO specialite (idSpecialite, libelle)
                VALUES (:idSpecialite, :libelle)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'idSpecialite' => $specialite->getIdSpecialite(),
            'libelle' => $specialite->getLibelle()
        ]);
    }

    // Modifier une spécialité
    public function modifier(Specialite $specialite)
    {
        $sql = "UPDATE specialite SET 
                    libelle = :libelle
                WHERE idSpecialite = :idSpecialite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'libelle' => $specialite->getLibelle(),
            'idSpecialite' => $specialite->getIdSpecialite()
        ]);
    }

    // Supprimer une spécialité
    public function supprimer($idSpecialite)
    {
        $sql = "DELETE FROM specialite WHERE idSpecialite = :idSpecialite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idSpecialite' => $idSpecialite]);
    }
}
