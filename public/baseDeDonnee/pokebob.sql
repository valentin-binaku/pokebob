-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 01 mai 2025 à 09:55
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
-- Base de données : `pokebob`
--

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

CREATE TABLE `carte` (
  `carteID` int(11) NOT NULL,
  `carteNom` varchar(255) NOT NULL,
  `carteDescription` text DEFAULT NULL,
  `carteAttaque` int(11) DEFAULT NULL,
  `carteRareté` enum('common','uncommun','rare','legendary') NOT NULL,
  `cartePV` int(11) DEFAULT NULL,
  `image` varchar(99) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `carte`
--

INSERT INTO `carte` (`carteID`, `carteNom`, `carteDescription`, `carteAttaque`, `carteRareté`, `cartePV`, `image`) VALUES
(1, 'Bob', 'Bob l\'unique, le premier bob sur terre, il incarne le debut de cette histoire.', 40, 'legendary', 180, NULL),
(2, 'Bobinette', 'L\'unique femme de Bob. Possede une sagesse infini.', 45, 'legendary', 170, NULL),
(3, 'Keylian', 'Le stagiaire legendaire au multiple pouvoir de destruction.', 25, 'rare', 140, NULL),
(4, 'CookieBob', 'Le CookieBob est une espece fort en chocolat.', 30, 'rare', 130, NULL),
(5, 'DictaBob', 'La terreur en personne.', 20, 'uncommun', 100, NULL),
(6, 'Bob le bricoleur', 'Un probleme de tuyauterie appeler le il saura vous aidez', 35, 'uncommun', 90, NULL),
(7, 'Bob l\'Eponge', 'Il absorbe toutes vos attaques... vous ne pourrez rien faire contre lui', 20, 'uncommun', 100, NULL),
(8, 'FootBobbeur', 'Le footbob est le sport national. Voici le joueur le plus prestigieux.', 20, 'common', 70, NULL),
(9, 'HackeurBob', 'Faites gaffes a vos donnees et laissez aucune trace sur internet.', 30, 'common', 60, NULL),
(10, 'Bob dirigeant de la RGPD', 'L\'ennemi de HackeurBob. Il connait les points faible de celui-ci', 20, 'common', 90, NULL),
(11, 'Bobo Parisien', 'Le bob de la capital. il est tres faineant', 10, 'common', 50, NULL),
(12, 'Narbob', 'Ce bob possede aucun talent.', 5, 'common', 40, NULL),
(13, 'Bob Marley', 'Le Bob Marley est la pour fumer ses adversaires.', 15, 'common', 50, NULL),
(14, 'Bowbzer', 'Ce bob est terrifiant n\'essayez pas de sympathise avec lui', 10, 'common', 110, NULL),
(15, 'BobYlone', 'L\'ennemi de Bob Marley. ils ne peuvent cohabiter ensemble.', 20, 'common', 45, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `inventaire`
--

CREATE TABLE `inventaire` (
  `utilID` int(11) DEFAULT NULL,
  `carteID` int(11) DEFAULT NULL,
  `Stock` int(55) DEFAULT NULL,
  `idInventory` int(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inventaire`
--

INSERT INTO `inventaire` (`utilID`, `carteID`, `Stock`, `idInventory`) VALUES
(2, 9, 1, 95),
(2, 11, 1, 96),
(2, 15, 1, 97);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `utilID` int(11) NOT NULL,
  `utilNom` varchar(255) NOT NULL,
  `utilEmail` varchar(255) NOT NULL,
  `utilMdp` varchar(255) NOT NULL,
  `utilType` enum('user','admin') DEFAULT 'user',
  `utilMoney` int(11) DEFAULT 0,
  `utilDateCreation` timestamp NOT NULL DEFAULT current_timestamp(),
  `utilDerniereOuverture` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilID`, `utilNom`, `utilEmail`, `utilMdp`, `utilType`, `utilMoney`, `utilDateCreation`, `utilDerniereOuverture`) VALUES
(2, 'admin', 'admin@admin.com', '$2y$10$VTxVn.dGgpGd199aq8RoYeX/AnEGgAjdr/JjszMbA/38NkhXIZxcy', 'user', 0, '2025-03-24 22:49:28', '2025-05-01 07:49:35.000000');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `carte`
--
ALTER TABLE `carte`
  ADD PRIMARY KEY (`carteID`);

--
-- Index pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD PRIMARY KEY (`idInventory`),
  ADD UNIQUE KEY `utilID` (`utilID`,`carteID`),
  ADD KEY `carteID` (`carteID`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`utilID`),
  ADD UNIQUE KEY `utilEmail` (`utilEmail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `carte`
--
ALTER TABLE `carte`
  MODIFY `carteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `inventaire`
--
ALTER TABLE `inventaire`
  MODIFY `idInventory` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `utilID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inventaire`
--
ALTER TABLE `inventaire`
  ADD CONSTRAINT `inventaire_ibfk_1` FOREIGN KEY (`utilID`) REFERENCES `utilisateur` (`utilID`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventaire_ibfk_2` FOREIGN KEY (`carteID`) REFERENCES `carte` (`carteID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
