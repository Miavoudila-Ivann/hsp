-- Tables du forum HSP
-- A exécuter dans phpMyAdmin sur la base hsp

CREATE TABLE IF NOT EXISTS `forum_post` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `forum_type`   ENUM('utilisateur','medecin','entreprise','admin') NOT NULL,
  `titre`        VARCHAR(200) NOT NULL,
  `contenu`      TEXT NOT NULL,
  `auteur_id`    INT NOT NULL,
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_forum_type` (`forum_type`),
  CONSTRAINT `fk_forum_post_auteur` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `forum_reponse` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `post_id`      INT NOT NULL,
  `contenu`      TEXT NOT NULL,
  `auteur_id`    INT NOT NULL,
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_post_id` (`post_id`),
  CONSTRAINT `fk_forum_reponse_post`   FOREIGN KEY (`post_id`)   REFERENCES `forum_post` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_forum_reponse_auteur` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
