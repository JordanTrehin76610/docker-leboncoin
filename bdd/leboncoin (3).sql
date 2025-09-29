-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 29 sep. 2025 à 11:38
-- Version du serveur : 8.0.43
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `leboncoin`
--

-- --------------------------------------------------------

--
-- Structure de la table `Achat`
--

CREATE TABLE `Achat` (
  `ach_id` int NOT NULL,
  `a_id` int NOT NULL,
  `u_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Achat`
--

INSERT INTO `Achat` (`ach_id`, `a_id`, `u_id`) VALUES
(9, 4, 1),
(10, 11, 3),
(11, 12, 3),
(12, 30, 2),
(13, 36, 2),
(14, 29, 1),
(15, 47, 3);

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

CREATE TABLE `annonces` (
  `a_id` int NOT NULL,
  `a_title` varchar(255) NOT NULL,
  `a_description` text NOT NULL,
  `a_price` decimal(10,2) NOT NULL,
  `a_picture` varchar(255) DEFAULT NULL,
  `a_publication` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_id` int NOT NULL,
  `a_statut` varchar(10) DEFAULT 'A vendre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`a_id`, `a_title`, `a_description`, `a_price`, `a_picture`, `a_publication`, `u_id`, `a_statut`) VALUES
(4, 'Micro bon marché', 'Je vend mon micro acheté à la foirefouille car je ne l\'utilise plus', 20.00, 'uploads/2_ssf-sm58-lce-b.png', '2025-09-15 08:23:33', 2, 'vendu'),
(11, 'TABLE A QUATRE PIEDS', 'Je vend ma table à quatre pieds car j\'en ai trouvé une à cinq pieds.', 40.00, 'uploads/1_20250917_50804T_P144COB_3492.webp', '2025-09-15 09:41:58', 1, 'vendu'),
(12, 'Armoire en bois', 'Je vais raconter ma vie pour faire le teste', 15.00, 'uploads/1_71G6rsKTXQL._UF1000,1000_QL80_.jpg', '2025-09-15 09:42:39', 1, 'vendu'),
(14, 'Nain de jardin', 'Je vend un nain de jardin mais je l\'ai perdu', 19.99, 'uploads/default.png', '2025-09-15 12:46:08', 2, 'A vendre'),
(28, 'NE VOIS TU PAS QUE JE VEND DE L\'ARGENT', 'ACHETE MA PIECE STP', 1000000.00, 'uploads/3_20250922_monnaie.png', '2025-09-16 09:28:30', 3, 'A vendre'),
(29, 'UN ORC MINIMISER PAR UN DEGAT DE DONNER PAR UN ADVERSAIRE INCONNU', 'asasasasasasassasasasas asasasasasasassasasasas asasasasasasassasasasas asasasasasasassasasasas ', 20.00, 'uploads/3_20250916_orcDamage.png', '2025-09-16 09:29:08', 3, 'vendu'),
(30, 'JE VOUS DONNE MON COEUR', 'Je vous aime !', 999.51, 'uploads/3_20250916_vie.png', '2025-09-16 09:32:21', 3, 'vendu'),
(36, 'Post It', 'Je vend mon post-it préféré, il est de couleur jaune avec un diamètre de 2mm par 2mm.', 5.00, 'uploads/1_20250922_postIt.png', '2025-09-22 07:46:14', 1, 'vendu'),
(37, 'Terrain à vendre', 'Vend terrain possédant qu&#039;un unique arbre, permis de construire non fournis. Diamètre du terrain 4km².', 1000000.00, 'uploads/1_20250922_TESTTESTTESTTESTTEST.jpg', '2025-09-22 09:07:36', 1, 'A vendre'),
(47, 'test alerte', 'test alerte', 5.00, 'uploads/default.png', '2025-09-23 07:09:22', 1, 'vendu'),
(48, 'Téléphone trop bien', 'ohlalala', 69.69, 'uploads/1_20250923_mm35d1.webp', '2025-09-23 12:53:13', 1, 'A vendre'),
(52, 'Gnée', 'Gnééééééééééééééééééééééééééééééé', 15.48, 'uploads/default.png', '2025-09-24 07:56:04', 0, 'A vendre');

-- --------------------------------------------------------

--
-- Structure de la table `FAVORIS`
--

CREATE TABLE `FAVORIS` (
  `u_id` int NOT NULL,
  `a_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `FAVORIS`
--

INSERT INTO `FAVORIS` (`u_id`, `a_id`) VALUES
(3, 11),
(3, 12),
(3, 14),
(1, 28),
(2, 28);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `u_id` int NOT NULL,
  `u_email` varchar(50) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_username` varchar(25) NOT NULL,
  `u_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_monney` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`u_id`, `u_email`, `u_password`, `u_username`, `u_inscription`, `u_monney`) VALUES
(0, 'Anonyme@gmail.com', 'azerty', 'Anonyme', '2025-09-24 07:51:47', 0),
(1, 'jordantrehin@gmail.com', '$2y$10$Fi2LPjpdGyR7vZJj9f/CIOjwIsTO517107685nnUnXQKHFVmn8jVK', 'Jordan', '2025-09-15 08:09:49', 100.24),
(2, 'MichelleJaquefils@outlook.fr', '$2y$10$/u9rFWGhOasfhJTtNXuGx.UjJoekEcW1E8n3ZFePFEuQQWxu3MHRS', 'MJDu32', '2025-09-15 08:18:52', 98995.5),
(3, 'spammeur@gmail.com', '$2y$10$G0fxcLEbwxUUHyqaMVi3Hu2S/a.V3TH4VdQq.5ds7aZk50zPFmgcy', 'spamspamspamspamspam', '2025-09-16 09:11:58', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Achat`
--
ALTER TABLE `Achat`
  ADD PRIMARY KEY (`ach_id`),
  ADD KEY `a_id` (`a_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Index pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Index pour la table `FAVORIS`
--
ALTER TABLE `FAVORIS`
  ADD PRIMARY KEY (`u_id`,`a_id`),
  ADD KEY `FAVORIS_ibfk_2` (`a_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `u_email` (`u_email`),
  ADD UNIQUE KEY `u_username` (`u_username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Achat`
--
ALTER TABLE `Achat`
  MODIFY `ach_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `a_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Achat`
--
ALTER TABLE `Achat`
  ADD CONSTRAINT `Achat_ibfk_1` FOREIGN KEY (`a_id`) REFERENCES `annonces` (`a_id`),
  ADD CONSTRAINT `Achat_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`);

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `FAVORIS`
--
ALTER TABLE `FAVORIS`
  ADD CONSTRAINT `FAVORIS_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `FAVORIS_ibfk_2` FOREIGN KEY (`a_id`) REFERENCES `annonces` (`a_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
