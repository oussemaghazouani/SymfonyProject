-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 11 déc. 2024 à 13:38
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `biblio_esprit`
--

-- --------------------------------------------------------

--
-- Structure de la table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `competitions`
--

CREATE TABLE `competitions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_c` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `type_competition_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `competitions`
--

INSERT INTO `competitions` (`id`, `name`, `date_c`, `type`, `type_competition_id`) VALUES
(27, 'geherherh4-01', '2024-11-06', 'kickboxing', 1),
(28, 'hthteht', '2024-01-13', 'bodybuilding', 1),
(35, 'hzryeuzyzry', '2024-11-07', 'kickboxing', 1),
(36, 'hjtjtrjrj', '2024-11-07', 'kickboxing', 2),
(37, 'real gym', '2024-11-06', 'boxing', 2),
(41, 'hjrtjr(--i(hg', '2024-12-18', 'bodybuilding', 1),
(42, 'gehetrkyryz', '2024-12-12', 'boxing', 2),
(43, 'gym mindset2', '2024-06-17', 'kickboxing', 1),
(45, 'bbbb66', '2024-12-15', 'kickboxing', 2),
(46, 'gymshark', '2024-12-13', 'bodybuilding', 2),
(49, 'validation2', '2024-12-05', 'bodybuilding', 2);

-- --------------------------------------------------------

--
-- Structure de la table `involved_events`
--

CREATE TABLE `involved_events` (
  `id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `is_participating` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `involved_events`
--

INSERT INTO `involved_events` (`id`, `competition_id`, `is_participating`) VALUES
(5, 28, 1),
(6, 27, 0),
(7, 35, 1),
(8, 37, 0),
(10, 41, 1),
(11, 45, 0),
(12, 46, 0),
(13, 42, 1),
(14, 49, 0);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_competitions`
--

CREATE TABLE `type_competitions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_competitions`
--

INSERT INTO `type_competitions` (`id`, `name`) VALUES
(1, 'martial art'),
(2, 'non martial art'),
(9, 'collective'),
(10, 'test');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A7DD463D2DFAFA86` (`type_competition_id`);

--
-- Index pour la table `involved_events`
--
ALTER TABLE `involved_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_301A2FDF7B39D312` (`competition_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `type_competitions`
--
ALTER TABLE `type_competitions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `involved_events`
--
ALTER TABLE `involved_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_competitions`
--
ALTER TABLE `type_competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `competitions`
--
ALTER TABLE `competitions`
  ADD CONSTRAINT `FK_A7DD463D2DFAFA86` FOREIGN KEY (`type_competition_id`) REFERENCES `type_competitions` (`id`);

--
-- Contraintes pour la table `involved_events`
--
ALTER TABLE `involved_events`
  ADD CONSTRAINT `FK_301A2FDF7B39D312` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
