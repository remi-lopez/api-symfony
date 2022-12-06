-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 06 déc. 2022 à 06:56
-- Version du serveur :  5.7.32
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api_symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20221129105230', '2022-11-29 10:52:41', 35),
('DoctrineMigrations\\Version20221129105645', '2022-11-29 10:56:50', 39),
('DoctrineMigrations\\Version20221129105921', '2022-11-29 10:59:25', 119),
('DoctrineMigrations\\Version20221129123357', '2022-11-29 12:34:01', 108),
('DoctrineMigrations\\Version20221129123500', '2022-11-29 12:35:03', 105),
('DoctrineMigrations\\Version20221129135039', '2022-11-29 13:50:43', 84),
('DoctrineMigrations\\Version20221129141817', '2022-11-29 14:18:21', 98),
('DoctrineMigrations\\Version20221129150909', '2022-11-29 15:10:31', 118),
('DoctrineMigrations\\Version20221130071102', '2022-11-30 07:11:56', 45),
('DoctrineMigrations\\Version20221130133601', '2022-11-30 13:36:06', 64);

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Javascript', '2022-11-29 10:59:36', '2022-11-29 10:59:36'),
(2, 'Php', '2022-11-29 10:59:36', '2022-11-29 10:59:36'),
(3, 'Python', '2022-11-29 10:59:36', '2022-11-29 10:59:36'),
(4, 'Java', '2022-11-29 10:59:36', '2022-11-29 10:59:36'),
(6, 'React', '2022-11-30 10:14:27', '2022-11-30 13:58:39'),
(7, 'Django', '2022-11-30 10:22:08', NULL),
(8, 'Groupe', '2022-12-01 14:36:51', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupes_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `groupes_id`, `created_at`, `updated_at`) VALUES
(13, 'dix@gmail.com', '[]', '$2y$13$jpIqChoxjLHqJx4U0xeROOQ/TAlWT9MoplSwUdHaeukpgiIOuM6Vi', 'rgzrgzer', 'Doe', 3, '2022-11-30 08:32:13', '2022-12-01 15:08:09'),
(14, 'onze@gmail.com', '[]', '$2y$13$gE8st7FuQT1wxau7mP27x.9nMb8fsD9x6Jw34Xxyu2kKo.bqq5ZHK', 'Mireille', 'Doe', 2, '2022-11-30 09:01:08', NULL),
(15, 'vingt@gmail.com', '[]', '$2y$13$OAL5XwuBEdJW2WQ0c7dEAewgx0M3hUIWh1R761RmtdAFJFL7YUrES', 'Cesar', 'Doe', 3, '2022-11-30 09:03:18', '2022-12-01 13:47:54'),
(16, 'treize@gmail.com', '[]', '$2y$13$ZDc/dBw0kN.hROy7IdvfS.D.SjtIVHqHRcG5t6nMGUxgVCmVsqjuS', 'Patrick', 'Doe', 2, '2022-11-30 10:28:28', NULL),
(17, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$RYTyP7hKScN49FwDTuDrfeUjPwpvn8e1z7CFhrjaPfLfpsGlTuMhC', 'Admin', 'Istrateur', 3, '2022-12-01 10:43:59', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D649305371B` (`groupes_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `groupe`
--
ALTER TABLE `groupe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649305371B` FOREIGN KEY (`groupes_id`) REFERENCES `groupe` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
