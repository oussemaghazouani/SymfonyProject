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
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 11 déc. 2024 à 13:41
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
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `produit_id`, `content`, `created_at`) VALUES
(6, 15, 'est ce que c\'est encore disponible ?', '2024-12-09 15:06:32'),
(7, 20, 'i love it', '2024-12-10 14:54:56'),
(8, 15, '*****', '2024-12-11 09:49:52'),
(9, 15, '*****', '2024-12-11 12:29:33');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20241108154752', '2024-11-08 16:58:46', 36),
('DoctrineMigrations\\Version20241110154406', '2024-11-10 16:44:22', 26),
('DoctrineMigrations\\Version20241110155631', '2024-11-10 16:57:29', 71),
('DoctrineMigrations\\Version20241110161433', '2024-11-10 17:14:49', 19),
('DoctrineMigrations\\Version20241110162054', '2024-11-10 17:21:22', 21),
('DoctrineMigrations\\Version20241120160241', '2024-11-20 17:03:29', 5093),
('DoctrineMigrations\\Version20241206142624', '2024-12-06 15:26:58', 14),
('DoctrineMigrations\\Version20241206145916', '2024-12-06 15:59:26', 17),
('DoctrineMigrations\\Version20241206152707', '2024-12-06 16:28:13', 17),
('DoctrineMigrations\\Version20241206154232', '2024-12-06 16:42:38', 18),
('DoctrineMigrations\\Version20241209101238', '2024-12-09 11:12:49', 118),
('DoctrineMigrations\\Version20241209111535', '2024-12-09 13:10:56', 88),
('DoctrineMigrations\\Version20241209122233', '2024-12-09 13:22:43', 6),
('DoctrineMigrations\\Version20241209134052', '2024-12-09 14:41:01', 65);

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
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `type_p_id` int(11) NOT NULL,
  `prix` int(11) NOT NULL,
  `est_disponible` tinyint(1) NOT NULL,
  `description` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `type_p_id`, `prix`, `est_disponible`, `description`, `image`) VALUES
(14, 'L Carnitine', 1, 100, 1, 'EAFIT Pure L-Carnitine 2 g 90 gélules', '675343152c2ee.jpg'),
(15, 'Glutamine', 1, 90, 1, '100% de L-Glutamine pure\r\nSans lactose, sans gluten, sans sucre\r\nSans parfum\r\n5000 mg de L-glutamine', '675342e5c4a54.webp'),
(16, 'Proteine Impact', 1, 320, 1, 'Jusqu\'à 79% de protéines\r\n2 sources de protéines pour une assimilation graduelle\r\n5 g de BCAA par do', '6753437ac1a73.webp'),
(17, 'Gants de musculation Kong Sports Wear', 2, 50, 1, 'N°1 sur le marché des accessoires sportives en Tunisie\r\nFabrique en Tunisie\r\nConception 100% alleman', '67534501e766a.jpg'),
(18, 'Ceinture de levage  Kong Sports Wear', 2, 60, 1, 'N°1 sur le marché des accessoires sportives en Tunisie\r\nFabrique en Tunisie\r\nConception 100% alleman', '6753454964d3e.jpg'),
(19, 'Deadlift Straps', 2, 25, 0, 'Amélioration de la Prise en Main\r\nRéduction de la Tension dans les Poignets\r\nStabilisation et Suppor', '675345bd9575e.jpg'),
(20, 'Zink', 1, 20, 1, 'M-Nutrition Zink 25mg 60 tabl', '675847548d719.png');

-- --------------------------------------------------------

--
-- Structure de la table `type_produit`
--

CREATE TABLE `type_produit` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_produit`
--

INSERT INTO `type_produit` (`id`, `nom`) VALUES
(1, 'complément alimentaire'),
(2, 'Equipement'),
(3, 'Accesoires');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CF347EFB` (`produit_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC27474A6AA` (`type_p_id`);

--
-- Index pour la table `type_produit`
--
ALTER TABLE `type_produit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `type_produit`
--
ALTER TABLE `type_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC27474A6AA` FOREIGN KEY (`type_p_id`) REFERENCES `type_produit` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
