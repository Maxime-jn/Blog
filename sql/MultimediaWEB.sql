-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 05 mars 2025 à 07:36
-- Version du serveur : 10.11.8-MariaDB-0ubuntu0.24.04.1
-- Version de PHP : 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `MultimediaWEB`
--

-- --------------------------------------------------------

--
-- Structure de la table `multimedia`
--

CREATE TABLE `multimedia` (
  `idmultimedia` int(11) NOT NULL,
  `path_ficher` varchar(1000) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `idPosts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `multimedia`
--

INSERT INTO `multimedia` (`idmultimedia`, `path_ficher`, `nom`, `idPosts`) VALUES
(57, './multimedia/video/file_example_MP4_480_1_5MG.mp4', 'file_example_MP4_480_1_5MG.mp4', 59),
(58, './multimedia/image/52bc5e5af6f880e4f2b351ecc2a8519e.jpg', '52bc5e5af6f880e4f2b351ecc2a8519e.jpg', 60),
(59, './multimedia/image/Elden_Ring_keyart678.png', 'Elden_Ring_keyart678.png', 61),
(61, './multimedia/image/elden_ring.jpg', 'elden_ring.jpg', 63),
(63, './multimedia/image/MaxCaulfield.png', 'MaxCaulfield.png', 65);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `idPosts` int(11) NOT NULL,
  `Titre` varchar(500) NOT NULL,
  `commentaire` varchar(1000) NOT NULL,
  `iduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`idPosts`, `Titre`, `commentaire`, `iduser`) VALUES
(59, 'tests', 'tests', 1),
(60, 'bobo', 'bbo', 1),
(61, 'noboob', 'bfdbd', 1),
(63, 'test', 'setest', 1),
(65, 'Life is strange', 'Un très bon jeu', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`iduser`, `name`, `password`, `token`) VALUES
(1, 'admin', 'test', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `multimedia`
--
ALTER TABLE `multimedia`
  ADD PRIMARY KEY (`idmultimedia`,`idPosts`) USING BTREE,
  ADD KEY `fk_multimedia_posts1_idx` (`idPosts`) USING BTREE;

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`idPosts`,`iduser`),
  ADD KEY `fk_posts_user1_idx` (`iduser`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `multimedia`
--
ALTER TABLE `multimedia`
  MODIFY `idmultimedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `idPosts` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `multimedia`
--
ALTER TABLE `multimedia`
  ADD CONSTRAINT `fk_multimedia_posts1` FOREIGN KEY (`idPosts`) REFERENCES `posts` (`idPosts`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user1` FOREIGN KEY (`iduser`) REFERENCES `user` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

