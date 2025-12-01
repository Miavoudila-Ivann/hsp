-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 01 déc. 2025 à 09:21
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hsp`
--

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
                                             `id_utilisateur` int NOT NULL AUTO_INCREMENT,
                                             `nom` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
    `prenom` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
    `email` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
    `rue` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
    `cd` int NOT NULL,
    `ville` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
    `mdp` varchar(255) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
    `role` enum('admin','user','medecin','attente de confirmation','entreprise') CHARACTER SET latin2 COLLATE latin2_bin DEFAULT 'attente de confirmation',
    `status` enum('Attente','accepter','refuser') CHARACTER SET latin2 COLLATE latin2_bin DEFAULT 'Attente',
    `reset_token` varchar(255) COLLATE latin2_bin DEFAULT NULL,
    `reset_expires` datetime DEFAULT NULL,
    PRIMARY KEY (`id_utilisateur`),
    UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `rue`, `cd`, `ville`, `mdp`, `role`, `status`, `reset_token`, `reset_expires`) VALUES
                                                                                                                                                          (18, 'Rodri', 'ROh ROh', 'panormalfake@gmail.com', '28', 92000, 'ST-Denis', '$2y$10$ES8QUhoZuOF.DMHhj7gI2uBN.KLCYETJZnrbowavEQXKxL0ETCYlK', 'user', 'refuser', NULL, NULL),
                                                                                                                                                          (19, 'Touzanine', 'Issa', 'issatouzanine@hotmail.fr', '07', 93200, 'ST-Denis', '$2y$10$jaHUSuxJAcIP25jXs6Dt4eL4i3FI8ZUnXgu2Bjgg0qc.alqSX3KCS', 'admin', 'accepter', NULL, NULL),
                                                                                                                                                          (20, 'DupontDeLigoness', 'Dody', 'proto@gmail.com', '44', 93200, 'Pierrefitte', '$2y$10$xSq7A.7tq25rF2EUTtE2IeuMb//8eH2qGS0Aop/4yJKz2c5xou5j2', 'user', 'Attente', NULL, NULL),
                                                                                                                                                          (21, 'Touzanine', 'Mohamed', 'MohamedTouzanine@gmail.com', '33', 44500, 'Nante', '$2y$10$In/iCMFOBnhKJYTHmmG9reEd/MDDkGAXDN023oR6UP.UirepuOVOq', 'medecin', 'Attente', NULL, NULL),
                                                                                                                                                          (22, 'azert', 'azertyu', 'azazaz@proto.fr', '28', 93200, 'ST-Denis', '$2y$10$7CJRZRhK/QQLBn7MvAsbp.nx9l76Ci5iRfwvVAdoDal90Q5l7nS9q', 'attente de confirmation', 'Attente', NULL, NULL),
                                                                                                                                                          (23, 'bave', 'bave', 'e.idbraim@lprs.fr', 'bave', 95200, 'baveville', '$2y$10$gGPwllvH5UNJ4exs6djlzeP95w/wyimb1aQvdRXOYgZggluWXpjke', 'admin', 'Attente', '3ea9281dffb816684b41504409621f92f5c6a638cc46b615ba6a347b77d3c264', '2025-11-23 19:17:43');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
