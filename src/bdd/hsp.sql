-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 04 nov. 2025 à 08:51
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
-- Structure de la table `candidature`
--

DROP TABLE IF EXISTS `candidature`;
CREATE TABLE IF NOT EXISTS `candidature` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `motivation` text CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `statut` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `date_candidature` date NOT NULL,
  `ref_offre` int NOT NULL,
  `ref_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_candidature`),
  KEY `ref_offre_candidature` (`ref_offre`),
  KEY `ref_utilisateur_candidature` (`ref_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id_contact` int NOT NULL,
  `sujet` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `message` varchar(100) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `date_envoie` date NOT NULL,
  `status` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

DROP TABLE IF EXISTS `contrat`;
CREATE TABLE IF NOT EXISTS `contrat` (
  `id_contrat` int NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `salaire` double NOT NULL,
  `ref_post` int NOT NULL,
  `ref_candidature` int NOT NULL,
  PRIMARY KEY (`id_contrat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

DROP TABLE IF EXISTS `entreprise`;
CREATE TABLE IF NOT EXISTS `entreprise` (
  `id_entreprise` int NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `rue_entreprise` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `ville_entreprise` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `cd_entreprise` int NOT NULL,
  `site_web` varchar(100) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  PRIMARY KEY (`id_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `etablissement`
--

DROP TABLE IF EXISTS `etablissement`;
CREATE TABLE IF NOT EXISTS `etablissement` (
  `id_etablissement` int NOT NULL AUTO_INCREMENT,
  `nom_etablissement` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `adresse_etablissement` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `site_web_etablissement` varchar(100) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  PRIMARY KEY (`id_etablissement`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

--
-- Déchargement des données de la table `etablissement`
--

INSERT INTO `etablissement` (`id_etablissement`, `nom_etablissement`, `adresse_etablissement`, `site_web_etablissement`) VALUES
(1, 'UFR NANTE', '28 rue de l esplanade', 'https://www.larousse.fr/dictionnaires/francais/site/72964');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `description` varchar(100) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `type_evenement` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `lieu` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `nb_place` int NOT NULL,
  `date_evenement` date NOT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `hopital`
--

DROP TABLE IF EXISTS `hopital`;
CREATE TABLE IF NOT EXISTS `hopital` (
  `id_hopital` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `adresse_hopital` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `ville_hopital` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  PRIMARY KEY (`id_hopital`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `inscription_evenement`
--

DROP TABLE IF EXISTS `inscription_evenement`;
CREATE TABLE IF NOT EXISTS `inscription_evenement` (
  `id_inscription_evenement` int NOT NULL AUTO_INCREMENT,
  `ref_evenement` int NOT NULL,
  `ref_utilisateur` int NOT NULL,
  `status` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  PRIMARY KEY (`id_inscription_evenement`),
  KEY `ref_evenement` (`ref_evenement`),
  KEY `ref_utilisateur_evenement` (`ref_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

DROP TABLE IF EXISTS `medecin`;
CREATE TABLE IF NOT EXISTS `medecin` (
  `id_medecin` int NOT NULL AUTO_INCREMENT,
  `ref_specialite` int NOT NULL,
  `ref_hopital` int NOT NULL,
  `ref_etablissement` int NOT NULL,
  PRIMARY KEY (`id_medecin`),
  KEY `ref_etablissement_medecin` (`ref_etablissement`),
  KEY `ref_hopital_medecin` (`ref_hopital`),
  KEY `ref_specialite_medecin` (`ref_specialite`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `titre` int NOT NULL,
  `description` int NOT NULL,
  `mission` int NOT NULL,
  `salaire` int NOT NULL,
  `type_offre` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `etat` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `ref_utilisateur` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `ref_entreprise` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `date_publication` date NOT NULL,
  PRIMARY KEY (`id_offre`),
  KEY `ref_utilisateur_offre` (`ref_utilisateur`),
  KEY `ref_entreprise_offre` (`ref_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `canal` int NOT NULL,
  `titre` varchar(50) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `contenu` int NOT NULL,
  `date_post` date NOT NULL,
  PRIMARY KEY (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `id_reponse` int NOT NULL AUTO_INCREMENT,
  `contenue` varchar(100) CHARACTER SET latin2 COLLATE latin2_bin NOT NULL,
  `date_post` date NOT NULL,
  `ref_post` int NOT NULL,
  `ref_auteur` int NOT NULL,
  PRIMARY KEY (`id_reponse`),
  UNIQUE KEY `ref_post_reponse` (`ref_post`),
  KEY `ref_auteur_reponse` (`ref_auteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

-- --------------------------------------------------------

--
-- Structure de la table `specialite`
--

DROP TABLE IF EXISTS `specialite`;
CREATE TABLE IF NOT EXISTS `specialite` (
  `id_specialite` int NOT NULL AUTO_INCREMENT,
  `libelle` int NOT NULL,
  PRIMARY KEY (`id_specialite`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

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
  `role` enum('admin','user','medecin') CHARACTER SET latin2 COLLATE latin2_bin DEFAULT 'user',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin2 COLLATE=latin2_bin;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `rue`, `cd`, `ville`, `mdp`, `role`) VALUES
(17, 'paparadzi', 'broo', 'panormalfakeee@gmail.com', '28', 93200, 'ST-Denis', '$2y$10$XXVSSjYCQEJOtrvbm5Rw5uJAOc9zo1YotJrEDw8xS5cnNZwXZFWLG', 'medecin'),
(18, 'Rodriguez', 'Escobar', 'panormalfake@gmail.com', '28', 93200, 'ST-Denis', '$2y$10$ES8QUhoZuOF.DMHhj7gI2uBN.KLCYETJZnrbowavEQXKxL0ETCYlK', 'medecin'),
(19, 'Toujini', 'Mehdi', 'i.touzanine@lprs.fr', '13', 93200, 'Dugny', '$2y$10$9j/vIgWi3RFYVHFnGiCHneHvZK6xIYyaRaCVjWxLVpw/Um6iWgLJa', 'admin');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `candidature_ibfk_1` FOREIGN KEY (`ref_offre`) REFERENCES `offre` (`id_offre`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD CONSTRAINT `medecin_ibfk_1` FOREIGN KEY (`ref_specialite`) REFERENCES `specialite` (`id_specialite`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medecin_ibfk_2` FOREIGN KEY (`ref_hopital`) REFERENCES `hopital` (`id_hopital`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`ref_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
