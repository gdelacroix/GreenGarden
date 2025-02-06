-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 20 jan. 2025 à 08:35
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
-- Base de données : `greengarden`
--
CREATE DATABASE IF NOT EXISTS `greengarden` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `greengarden`;

DELIMITER $$
--
-- Fonctions
--
DROP FUNCTION IF EXISTS `generate_slug`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_slug` (`input_text` VARCHAR(250)) RETURNS VARCHAR(250) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE slug VARCHAR(250);

    -- Remplacer les caractères accentués manuellement
    SET slug = input_text;
    SET slug = REPLACE(slug, 'à', 'a');
    SET slug = REPLACE(slug, 'á', 'a');
    SET slug = REPLACE(slug, 'â', 'a');
    SET slug = REPLACE(slug, 'ä', 'a');
    SET slug = REPLACE(slug, 'ã', 'a');
    SET slug = REPLACE(slug, 'å', 'a');
    SET slug = REPLACE(slug, 'è', 'e');
    SET slug = REPLACE(slug, 'é', 'e');
    SET slug = REPLACE(slug, 'ê', 'e');
    SET slug = REPLACE(slug, 'ë', 'e');
    SET slug = REPLACE(slug, 'ì', 'i');
    SET slug = REPLACE(slug, 'í', 'i');
    SET slug = REPLACE(slug, 'î', 'i');
    SET slug = REPLACE(slug, 'ï', 'i');
    SET slug = REPLACE(slug, 'ò', 'o');
    SET slug = REPLACE(slug, 'ó', 'o');
    SET slug = REPLACE(slug, 'ô', 'o');
    SET slug = REPLACE(slug, 'ö', 'o');
    SET slug = REPLACE(slug, 'õ', 'o');
    SET slug = REPLACE(slug, 'ù', 'u');
    SET slug = REPLACE(slug, 'ú', 'u');
    SET slug = REPLACE(slug, 'û', 'u');
    SET slug = REPLACE(slug, 'ü', 'u');
    SET slug = REPLACE(slug, 'ç', 'c');
    SET slug = REPLACE(slug, 'ñ', 'n');
    SET slug = REPLACE(slug, 'ý', 'y');
    SET slug = REPLACE(slug, 'ÿ', 'y');

    -- Convertir en minuscules
    SET slug = LOWER(slug);

    -- Remplacer les espaces et tirets multiples par un seul tiret
    SET slug = REPLACE(slug, ' ', '-');

    -- Supprimer les caractères non alphanumériques ou tirets
    SET slug = REGEXP_REPLACE(slug, '[^a-z0-9-]', '');

    RETURN slug;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_adresse`
--

DROP TABLE IF EXISTS `t_d_adresse`;
CREATE TABLE `t_d_adresse` (
  `Id_Adresse` int(11) NOT NULL,
  `Ligne1_Adresse` varchar(50) NOT NULL,
  `Ligne2_Adresse` varchar(50) DEFAULT NULL,
  `Ligne3_Adresse` varchar(50) DEFAULT NULL,
  `CP_Adresse` varchar(50) NOT NULL,
  `Ville_Adresse` varchar(150) NOT NULL,
  `Id_Client` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_adresse`
--

INSERT INTO `t_d_adresse` (`Id_Adresse`, `Ligne1_Adresse`, `Ligne2_Adresse`, `Ligne3_Adresse`, `CP_Adresse`, `Ville_Adresse`, `Id_Client`) VALUES
(1, 'ZAC de Conches', NULL, NULL, '27190', 'Conches en Ouche', 2),
(2, '26 rue Aristide Briand', NULL, NULL, '27000', 'Evreux', 3),
(3, '102 bis rue Chartraine', NULL, NULL, '27000', 'Evreux', 4),
(4, '112 bis rue Chartraine', NULL, NULL, '27000', 'Evreux', 4),
(5, '202 IMPASSE DU robert', '', '', '27190', 'LA BONNEVILLE SUR ITON', 2),
(6, '20 rue michel', '', '', '27190', 'LA BONNEVILLE SUR ITON', 4),
(7, '202 avenue leo de vinci', '', '', '27000', 'EVREUX', 4),
(8, '202 IMPASSE DE BATMAN', '', '', '27240', 'DAMVILLE', 4),
(9, '202 IMPASSE DE L HOMME D ACIER', '', '', '27000', 'EVREUX', 4);

-- --------------------------------------------------------

--
-- Structure de la table `t_d_adressecommande`
--

DROP TABLE IF EXISTS `t_d_adressecommande`;
CREATE TABLE `t_d_adressecommande` (
  `Id_Commande` int(11) NOT NULL,
  `Id_Adresse` int(11) NOT NULL,
  `Id_Type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_adressecommande`
--

INSERT INTO `t_d_adressecommande` (`Id_Commande`, `Id_Adresse`, `Id_Type`) VALUES
(1, 1, 1),
(1, 1, 2),
(2, 1, 1),
(2, 1, 2),
(3, 2, 1),
(3, 2, 2),
(4, 3, 1),
(4, 4, 2),
(5, 5, 1),
(6, 6, 1),
(6, 7, 2),
(7, 8, 1),
(7, 9, 2);

-- --------------------------------------------------------

--
-- Structure de la table `t_d_categorie`
--

DROP TABLE IF EXISTS `t_d_categorie`;
CREATE TABLE `t_d_categorie` (
  `Id_Categorie` int(11) NOT NULL,
  `Libelle` varchar(50) NOT NULL,
  `Id_Categorie_Parent` int(11) DEFAULT NULL,
  `Slug` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_categorie`
--

INSERT INTO `t_d_categorie` (`Id_Categorie`, `Libelle`, `Id_Categorie_Parent`, `Slug`) VALUES
(1, 'Outillage Jardin', NULL, 'outillage-jardin'),
(2, 'Bêche', 1, 'beche'),
(3, 'Pioche', 1, 'pioche'),
(4, 'Rateau', 1, 'rateau'),
(5, 'Pelle', 1, 'pelle'),
(6, 'Plant', NULL, 'plant'),
(7, 'Légume', 6, 'legume'),
(8, 'Fruit', 6, 'fruit'),
(9, 'Fleur', 6, 'fleur'),
(10, 'Pot', NULL, 'pot'),
(11, 'Luminaire Solaire', NULL, 'luminaire-solaire'),
(12, 'Tuyau d\'arrosage', NULL, 'tuyau-darrosage'),
(13, 'Serre', NULL, 'serre');

--
-- Déclencheurs `t_d_categorie`
--
DROP TRIGGER IF EXISTS `before_insert_t_d_categorie`;
DELIMITER $$
CREATE TRIGGER `before_insert_t_d_categorie` BEFORE INSERT ON `t_d_categorie` FOR EACH ROW BEGIN
    DECLARE base_slug VARCHAR(250);
    DECLARE unique_slug VARCHAR(250);
    DECLARE counter INT DEFAULT 0;

    -- Générer un slug de base
    SET base_slug = generate_slug(NEW.Libelle);

    -- Vérifier l'unicité et ajuster si nécessaire
    SET unique_slug = base_slug;
    WHILE EXISTS (SELECT 1 FROM t_d_categorie WHERE Slug = unique_slug) DO
        SET counter = counter + 1;
        SET unique_slug = CONCAT(base_slug, '-', counter);
    END WHILE;

    -- Attribuer le slug unique à la nouvelle ligne
    SET NEW.Slug = unique_slug;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_update_t_d_categorie`;
DELIMITER $$
CREATE TRIGGER `before_update_t_d_categorie` BEFORE UPDATE ON `t_d_categorie` FOR EACH ROW BEGIN
    DECLARE base_slug VARCHAR(250);
    DECLARE unique_slug VARCHAR(250);
    DECLARE counter INT DEFAULT 0;

    -- Vérifier si Nom_categorie a changé
    IF OLD.Libelle <> NEW.Libelle THEN
        -- Générer un slug de base
        SET base_slug = generate_slug(NEW.Libelle);

        -- Vérifier l'unicité et ajuster si nécessaire
        SET unique_slug = base_slug;
        WHILE EXISTS (SELECT 1 FROM t_d_categorie WHERE Slug = unique_slug AND Id_Categorie <> OLD.Id_Categorie) DO
            SET counter = counter + 1;
            SET unique_slug = CONCAT(base_slug, '-', counter);
        END WHILE;

        -- Attribuer le slug unique à la ligne mise à jour
        SET NEW.Slug = unique_slug;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_client`
--

DROP TABLE IF EXISTS `t_d_client`;
CREATE TABLE `t_d_client` (
  `Id_Client` int(11) NOT NULL,
  `Nom_Societe_Client` varchar(150) DEFAULT NULL,
  `Nom_Client` varchar(150) DEFAULT NULL,
  `Prenom_Client` varchar(150) DEFAULT NULL,
  `Mail_Client` varchar(150) DEFAULT NULL,
  `Tel_Client` varchar(50) DEFAULT NULL,
  `Id_Commercial` int(11) NOT NULL,
  `Id_Type_Client` int(11) NOT NULL,
  `DelaiPaiement_Client` int(11) NOT NULL,
  `Num_Client` varchar(45) NOT NULL,
  `Id_User` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_client`
--

INSERT INTO `t_d_client` (`Id_Client`, `Nom_Societe_Client`, `Nom_Client`, `Prenom_Client`, `Mail_Client`, `Tel_Client`, `Id_Commercial`, `Id_Type_Client`, `DelaiPaiement_Client`, `Num_Client`, `Id_User`) VALUES
(2, 'Gamm Vert', NULL, NULL, 'conches@gammvert.fr', NULL, 1, 2, 30, 'CLI0000001', 9),
(3, NULL, 'Truc', 'Muche', 'trucmuche@yahoo.fr', '0123456789', 1, 1, 0, 'CLI0000002', 10),
(4, NULL, 'Gonzales', 'Roberto', 'robertogonzales@gmail.com.fr', '0987654321', 2, 1, 0, 'CLI0000003', 11);

--
-- Déclencheurs `t_d_client`
--
DROP TRIGGER IF EXISTS `tr_generate_num_client`;
DELIMITER $$
CREATE TRIGGER `tr_generate_num_client` BEFORE INSERT ON `t_d_client` FOR EACH ROW BEGIN
    DECLARE prefix CHAR(3) DEFAULT 'CLI';
    DECLARE num INT;

    SELECT COUNT(*) INTO num FROM t_d_client;
    SET num = num + 1;

    SET NEW.num_client = CONCAT(prefix, LPAD(num, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_commande`
--

DROP TABLE IF EXISTS `t_d_commande`;
CREATE TABLE `t_d_commande` (
  `Id_Commande` int(11) NOT NULL,
  `Num_Commande` varchar(50) NOT NULL,
  `Date_Commande` datetime NOT NULL,
  `Id_Statut` int(11) NOT NULL,
  `Id_Client` int(11) NOT NULL,
  `Id_TypePaiement` int(11) NOT NULL,
  `Remise_Commande` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_commande`
--

INSERT INTO `t_d_commande` (`Id_Commande`, `Num_Commande`, `Date_Commande`, `Id_Statut`, `Id_Client`, `Id_TypePaiement`, `Remise_Commande`) VALUES
(1, 'CMD0000001', '2022-02-01 14:09:08', 2, 2, 2, 0.00),
(2, 'CMD0000002', '2022-02-03 07:09:35', 6, 2, 2, 10.00),
(3, 'CMD0000003', '2023-04-01 12:10:08', 5, 3, 1, 0.00),
(4, 'CMD0000004', '2023-05-03 21:24:28', 4, 4, 2, 0.00),
(5, 'CMD0000005', '2023-05-23 13:20:59', 1, 2, 1, 0.00),
(6, 'CMD0000006', '2023-05-23 13:22:57', 1, 4, 1, 0.00),
(7, 'CMD0000007', '2023-05-23 13:25:37', 1, 4, 1, 0.00);

--
-- Déclencheurs `t_d_commande`
--
DROP TRIGGER IF EXISTS `tr_generate_num_commande`;
DELIMITER $$
CREATE TRIGGER `tr_generate_num_commande` BEFORE INSERT ON `t_d_commande` FOR EACH ROW BEGIN
    DECLARE prefix CHAR(3) DEFAULT 'CMD';
    DECLARE num INT;

    SELECT COUNT(*) INTO num FROM t_d_commande;
    SET num = num + 1;

    SET NEW.num_commande = CONCAT(prefix, LPAD(num, 7, '0'));
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tr_insert_facture`;
DELIMITER $$
CREATE TRIGGER `tr_insert_facture` AFTER INSERT ON `t_d_commande` FOR EACH ROW BEGIN
    INSERT INTO t_d_facture (id_commande)
    VALUES (NEW.id_commande);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_commercial`
--

DROP TABLE IF EXISTS `t_d_commercial`;
CREATE TABLE `t_d_commercial` (
  `Id_Commercial` int(11) NOT NULL,
  `Nom_Commercial` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_commercial`
--

INSERT INTO `t_d_commercial` (`Id_Commercial`, `Nom_Commercial`) VALUES
(1, 'Jean Ventout'),
(2, 'Monique Rabais');

-- --------------------------------------------------------

--
-- Structure de la table `t_d_expedition`
--

DROP TABLE IF EXISTS `t_d_expedition`;
CREATE TABLE `t_d_expedition` (
  `Id_Expedition` int(11) NOT NULL,
  `Date_Expedition` datetime DEFAULT NULL,
  `NumBL` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_expedition`
--

INSERT INTO `t_d_expedition` (`Id_Expedition`, `Date_Expedition`, `NumBL`) VALUES
(1, '2022-02-07 14:14:39', 'EXP0000001'),
(2, '2023-04-01 14:15:03', 'EXP0000002'),
(3, '2023-05-06 08:15:24', 'EXP0000003'),
(4, '2023-05-09 08:30:58', 'EXP0000004'),
(5, NULL, 'EXP0000005'),
(6, NULL, 'EXP0000006'),
(7, NULL, 'EXP0000007'),
(8, NULL, 'EXP0000008');

--
-- Déclencheurs `t_d_expedition`
--
DROP TRIGGER IF EXISTS `tr_expedition_generate_numBL`;
DELIMITER $$
CREATE TRIGGER `tr_expedition_generate_numBL` BEFORE INSERT ON `t_d_expedition` FOR EACH ROW BEGIN
    DECLARE prefix CHAR(3) DEFAULT 'EXP';
    DECLARE num INT;

    SELECT COUNT(*) INTO num FROM t_d_expedition;
    SET num = num + 1;

    SET NEW.numBL = CONCAT(prefix, LPAD(num, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_facture`
--

DROP TABLE IF EXISTS `t_d_facture`;
CREATE TABLE `t_d_facture` (
  `Id_Facture` int(11) NOT NULL,
  `NumFacture` varchar(150) NOT NULL,
  `Date_Facture` datetime NOT NULL,
  `Id_Commande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_facture`
--

INSERT INTO `t_d_facture` (`Id_Facture`, `NumFacture`, `Date_Facture`, `Id_Commande`) VALUES
(1, 'FAC0000001', '0000-00-00 00:00:00', 1),
(2, 'FAC0000002', '2022-02-07 14:14:39', 2),
(3, 'FAC0000003', '2023-04-01 14:15:03', 3),
(4, 'FAC0000004', '2023-05-06 08:15:24', 4),
(5, 'FAC0000005', '0000-00-00 00:00:00', 5),
(6, 'FAC0000006', '0000-00-00 00:00:00', 6),
(7, 'FAC0000007', '0000-00-00 00:00:00', 7);

--
-- Déclencheurs `t_d_facture`
--
DROP TRIGGER IF EXISTS `tr_facture_generate_numfacture`;
DELIMITER $$
CREATE TRIGGER `tr_facture_generate_numfacture` BEFORE INSERT ON `t_d_facture` FOR EACH ROW BEGIN
    DECLARE prefix CHAR(3) DEFAULT 'FAC';
    DECLARE num INT;

    SELECT COUNT(*) INTO num FROM t_d_facture;
    SET num = num + 1;

    SET NEW.numFacture = CONCAT(prefix, LPAD(num, 7, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_fournisseur`
--

DROP TABLE IF EXISTS `t_d_fournisseur`;
CREATE TABLE `t_d_fournisseur` (
  `Id_Fournisseur` int(11) NOT NULL,
  `Nom_Fournisseur` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_fournisseur`
--

INSERT INTO `t_d_fournisseur` (`Id_Fournisseur`, `Nom_Fournisseur`) VALUES
(1, 'Pierre'),
(2, 'Paul'),
(3, 'Jacques');

-- --------------------------------------------------------

--
-- Structure de la table `t_d_lignecommande`
--

DROP TABLE IF EXISTS `t_d_lignecommande`;
CREATE TABLE `t_d_lignecommande` (
  `Id_Commande` int(11) NOT NULL,
  `Id_Produit` int(11) NOT NULL,
  `Id_Expedition` int(11) UNSIGNED NOT NULL,
  `Quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_lignecommande`
--

INSERT INTO `t_d_lignecommande` (`Id_Commande`, `Id_Produit`, `Id_Expedition`, `Quantite`) VALUES
(1, 1, 5, 1),
(2, 1, 1, 1),
(2, 2, 1, 1),
(3, 4, 2, 3),
(4, 5, 3, 10),
(4, 6, 4, 2),
(7, 4, 8, 1),
(7, 8, 8, 1),
(7, 10, 8, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_d_produit`
--

DROP TABLE IF EXISTS `t_d_produit`;
CREATE TABLE `t_d_produit` (
  `Id_Produit` int(11) NOT NULL,
  `Taux_TVA` decimal(15,2) NOT NULL,
  `Nom_Long` varchar(250) NOT NULL,
  `Nom_court` varchar(50) NOT NULL,
  `Ref_fournisseur` varchar(250) NOT NULL,
  `Photo` varchar(250) DEFAULT NULL,
  `Prix_Achat` decimal(15,2) NOT NULL,
  `Id_Fournisseur` int(11) NOT NULL,
  `Id_Categorie` int(11) NOT NULL,
  `Slug` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_produit`
--

INSERT INTO `t_d_produit` (`Id_Produit`, `Taux_TVA`, `Nom_Long`, `Nom_court`, `Ref_fournisseur`, `Photo`, `Prix_Achat`, `Id_Fournisseur`, `Id_Categorie`, `Slug`) VALUES
(1, 5.50, 'Bêche pour quelqu\'un qui serait assez grand, genre', 'Bêche pour grand', 'BZFR1589', 'photo1.jpg', 14.90, 1, 2, 'beche-pour-grand'),
(2, 5.50, 'Bêche pour quelqu\'un qui serait assez petit, genre', 'Bêche pour petit', 'BZFR1592', 'photo2.jpg', 9.90, 1, 2, 'beche-pour-petit'),
(3, 5.50, 'Le plant d\'aubergine qui déchire sa grand Mère', 'Plant Aubergine', 'JAFR1589', 'photo3.jpg', 1.90, 2, 7, 'plant-aubergine'),
(4, 5.50, 'Le plant de fraises qui déchire sa grand Mère', 'Plant Fraises', 'JAFR26895', 'photo4.jpg', 1.90, 2, 8, 'plant-fraises'),
(5, 19.60, 'Le set de 10 lampes qui permet d\'éclairer ton allé', 'Set de 10 lampes', 'LAM0001', 'photo5.jpg', 49.90, 3, 11, 'set-de-10-lampes'),
(6, 19.60, 'Le tuyau d\'arrosage dexception qui s\'allonge et se', 'Tuyai 20m', 'TUY0001', 'photo6.jpg', 24.90, 3, 12, 'tuyai-20m'),
(8, 19.60, 'le test de fichier de ouf2', 'le test2', 'FDFDFD35462', NULL, 15.28, 1, 5, 'le-test2'),
(9, 19.60, 'le test de fichier de ouf3', 'le test3', 'FDFDFD35463', NULL, 25.58, 1, 11, 'le-test3'),
(10, 19.60, 'c\'est super la life', 'coucou', 'dd28', NULL, 22.58, 1, 9, 'coucou'),
(11, 19.60, 'c\'est super la life', 'coucou', 'dd2812', NULL, 22.59, 1, 2, 'coucou-2'),
(12, 5.50, 'sfsdggsg', 'gdfgdfgdg', 'sgfsggf', 'gg.jpg', 25.58, 1, 5, 'gdfgdfgdg');

--
-- Déclencheurs `t_d_produit`
--
DROP TRIGGER IF EXISTS `before_insert_t_d_produit`;
DELIMITER $$
CREATE TRIGGER `before_insert_t_d_produit` BEFORE INSERT ON `t_d_produit` FOR EACH ROW BEGIN
    DECLARE base_slug VARCHAR(250);
    DECLARE unique_slug VARCHAR(250);
    DECLARE counter INT DEFAULT 1;

    -- Générer le slug de base
    SET base_slug = generate_slug(NEW.Nom_court);
    SET unique_slug = base_slug;

    -- Vérifier l'unicité
    WHILE EXISTS (SELECT 1 FROM `t_d_produit` WHERE `Slug` = unique_slug) DO
        SET unique_slug = CONCAT(base_slug, '-', counter);
        SET counter = counter + 1;
    END WHILE;

    -- Attribuer le slug unique
    SET NEW.Slug = unique_slug;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_update_t_d_produit`;
DELIMITER $$
CREATE TRIGGER `before_update_t_d_produit` BEFORE UPDATE ON `t_d_produit` FOR EACH ROW BEGIN
    DECLARE base_slug VARCHAR(250);
    DECLARE unique_slug VARCHAR(250);
    DECLARE counter INT DEFAULT 1;

    -- Générer le slug de base si le Nom_court a changé
    IF NEW.Nom_court <> OLD.Nom_court THEN
        SET base_slug = generate_slug(NEW.Nom_court);
        SET unique_slug = base_slug;

        -- Vérifier l'unicité
        WHILE EXISTS (SELECT 1 FROM `t_d_produit` WHERE `Slug` = unique_slug AND `Id_Produit` <> NEW.Id_Produit) DO
            SET unique_slug = CONCAT(base_slug, '-', counter);
            SET counter = counter + 1;
        END WHILE;

        -- Attribuer le slug unique
        SET NEW.Slug = unique_slug;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `t_d_statut_commande`
--

DROP TABLE IF EXISTS `t_d_statut_commande`;
CREATE TABLE `t_d_statut_commande` (
  `Id_Statut` int(11) NOT NULL,
  `Libelle_Statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_statut_commande`
--

INSERT INTO `t_d_statut_commande` (`Id_Statut`, `Libelle_Statut`) VALUES
(1, 'Saisie'),
(2, 'Annulée'),
(3, 'En préparation'),
(4, 'Expédiée'),
(5, 'Facturée'),
(6, 'Soldée');

-- --------------------------------------------------------

--
-- Structure de la table `t_d_type_adresse`
--

DROP TABLE IF EXISTS `t_d_type_adresse`;
CREATE TABLE `t_d_type_adresse` (
  `Id_Type` int(11) NOT NULL,
  `Libelle_Type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_type_adresse`
--

INSERT INTO `t_d_type_adresse` (`Id_Type`, `Libelle_Type`) VALUES
(1, 'Livraison'),
(2, 'Facturation');

-- --------------------------------------------------------

--
-- Structure de la table `t_d_type_client`
--

DROP TABLE IF EXISTS `t_d_type_client`;
CREATE TABLE `t_d_type_client` (
  `Id_Type_Client` int(11) NOT NULL,
  `Libelle_Type_Client` varchar(50) NOT NULL,
  `Taux_Penalite_Retard` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_type_client`
--

INSERT INTO `t_d_type_client` (`Id_Type_Client`, `Libelle_Type_Client`, `Taux_Penalite_Retard`) VALUES
(1, 'Particulier', 15.00),
(2, 'Professionnel', 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `t_d_type_paiement`
--

DROP TABLE IF EXISTS `t_d_type_paiement`;
CREATE TABLE `t_d_type_paiement` (
  `Id_TypePaiement` int(11) NOT NULL,
  `Libelle_TypePaiement` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_type_paiement`
--

INSERT INTO `t_d_type_paiement` (`Id_TypePaiement`, `Libelle_TypePaiement`) VALUES
(1, 'Carte Bancaire'),
(2, 'Chèque'),
(3, 'Espèces'),
(4, 'Virement');

-- --------------------------------------------------------

--
-- Structure de la table `t_d_user`
--

DROP TABLE IF EXISTS `t_d_user`;
CREATE TABLE `t_d_user` (
  `Id_User` int(11) NOT NULL,
  `Id_UserType` int(11) NOT NULL,
  `Login` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_user`
--

INSERT INTO `t_d_user` (`Id_User`, `Id_UserType`, `Login`, `Password`) VALUES
(6, 2, 'TATA', '$2y$10$Lcdm/IjztRlWqkiNkpSDbOdnzf6.y.Odfof/GI0/oEwotuJCKxMkW'),
(7, 1, 'TETE', '$2y$10$.WB8ClgVFG1TgxpvyqOHNOfE8YdVASuEQjHsfERkOSROn8COeDpFO'),
(8, 1, 'TITI', '$2y$10$LW3LfMM2aWbPNqsCdVdaVOpjEmGkI6WCDz6u1CQM4dETUQzq85fnW'),
(9, 1, 'TOTO', '$2y$10$o8GM7KPUrZspf7Nesw993.G4NijnlSQp69kVb5X756y1Y0VPibMt6'),
(10, 6, 'TUTU', '$2y$10$WNEpjJHt8EqMMu1hJ7e.Q.a9VschQ/4IUx07Wuci7FhgmNr4eMWqW'),
(11, 3, 'TYTY', '$2y$10$B.QXItF9zFL2mtqG2bJ.vOa3xTCAhmdMDiSUb8iSpY4eyXOY/2sJO');

-- --------------------------------------------------------

--
-- Structure de la table `t_d_usertype`
--

DROP TABLE IF EXISTS `t_d_usertype`;
CREATE TABLE `t_d_usertype` (
  `Id_UserType` int(11) NOT NULL,
  `Libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `t_d_usertype`
--

INSERT INTO `t_d_usertype` (`Id_UserType`, `Libelle`) VALUES
(1, 'Client'),
(2, 'Admin'),
(3, 'Commercial'),
(6, 'SAV');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_d_adresse`
--
ALTER TABLE `t_d_adresse`
  ADD PRIMARY KEY (`Id_Adresse`),
  ADD KEY `Id_Client` (`Id_Client`);

--
-- Index pour la table `t_d_adressecommande`
--
ALTER TABLE `t_d_adressecommande`
  ADD PRIMARY KEY (`Id_Commande`,`Id_Adresse`,`Id_Type`),
  ADD KEY `Id_Adresse` (`Id_Adresse`),
  ADD KEY `Id_Type` (`Id_Type`);

--
-- Index pour la table `t_d_categorie`
--
ALTER TABLE `t_d_categorie`
  ADD PRIMARY KEY (`Id_Categorie`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `Id_Categorie_Parent` (`Id_Categorie_Parent`);

--
-- Index pour la table `t_d_client`
--
ALTER TABLE `t_d_client`
  ADD PRIMARY KEY (`Id_Client`),
  ADD KEY `Id_Commercial` (`Id_Commercial`),
  ADD KEY `Id_Type_Client` (`Id_Type_Client`),
  ADD KEY `t_d_client_ibfk_user` (`Id_User`);

--
-- Index pour la table `t_d_commande`
--
ALTER TABLE `t_d_commande`
  ADD PRIMARY KEY (`Id_Commande`),
  ADD KEY `Id_Statut` (`Id_Statut`),
  ADD KEY `Id_TypePaiement` (`Id_TypePaiement`),
  ADD KEY `Id_Client` (`Id_Client`);

--
-- Index pour la table `t_d_commercial`
--
ALTER TABLE `t_d_commercial`
  ADD PRIMARY KEY (`Id_Commercial`);

--
-- Index pour la table `t_d_expedition`
--
ALTER TABLE `t_d_expedition`
  ADD PRIMARY KEY (`Id_Expedition`);

--
-- Index pour la table `t_d_facture`
--
ALTER TABLE `t_d_facture`
  ADD PRIMARY KEY (`Id_Facture`),
  ADD UNIQUE KEY `Id_Commande` (`Id_Commande`);

--
-- Index pour la table `t_d_fournisseur`
--
ALTER TABLE `t_d_fournisseur`
  ADD PRIMARY KEY (`Id_Fournisseur`);

--
-- Index pour la table `t_d_lignecommande`
--
ALTER TABLE `t_d_lignecommande`
  ADD PRIMARY KEY (`Id_Commande`,`Id_Produit`,`Id_Expedition`),
  ADD KEY `Id_Produit` (`Id_Produit`),
  ADD KEY `t_d_lignecommande_ibfk_3` (`Id_Expedition`);

--
-- Index pour la table `t_d_produit`
--
ALTER TABLE `t_d_produit`
  ADD PRIMARY KEY (`Id_Produit`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `Id_Fournisseur` (`Id_Fournisseur`),
  ADD KEY `Id_Categorie` (`Id_Categorie`);

--
-- Index pour la table `t_d_statut_commande`
--
ALTER TABLE `t_d_statut_commande`
  ADD PRIMARY KEY (`Id_Statut`);

--
-- Index pour la table `t_d_type_adresse`
--
ALTER TABLE `t_d_type_adresse`
  ADD PRIMARY KEY (`Id_Type`);

--
-- Index pour la table `t_d_type_client`
--
ALTER TABLE `t_d_type_client`
  ADD PRIMARY KEY (`Id_Type_Client`);

--
-- Index pour la table `t_d_type_paiement`
--
ALTER TABLE `t_d_type_paiement`
  ADD PRIMARY KEY (`Id_TypePaiement`);

--
-- Index pour la table `t_d_user`
--
ALTER TABLE `t_d_user`
  ADD PRIMARY KEY (`Id_User`),
  ADD KEY `t_d_user_ibfk_1` (`Id_UserType`);

--
-- Index pour la table `t_d_usertype`
--
ALTER TABLE `t_d_usertype`
  ADD PRIMARY KEY (`Id_UserType`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_d_adresse`
--
ALTER TABLE `t_d_adresse`
  MODIFY `Id_Adresse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `t_d_categorie`
--
ALTER TABLE `t_d_categorie`
  MODIFY `Id_Categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `t_d_client`
--
ALTER TABLE `t_d_client`
  MODIFY `Id_Client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `t_d_commande`
--
ALTER TABLE `t_d_commande`
  MODIFY `Id_Commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `t_d_commercial`
--
ALTER TABLE `t_d_commercial`
  MODIFY `Id_Commercial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `t_d_expedition`
--
ALTER TABLE `t_d_expedition`
  MODIFY `Id_Expedition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `t_d_facture`
--
ALTER TABLE `t_d_facture`
  MODIFY `Id_Facture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `t_d_fournisseur`
--
ALTER TABLE `t_d_fournisseur`
  MODIFY `Id_Fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `t_d_produit`
--
ALTER TABLE `t_d_produit`
  MODIFY `Id_Produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `t_d_statut_commande`
--
ALTER TABLE `t_d_statut_commande`
  MODIFY `Id_Statut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `t_d_type_adresse`
--
ALTER TABLE `t_d_type_adresse`
  MODIFY `Id_Type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `t_d_type_client`
--
ALTER TABLE `t_d_type_client`
  MODIFY `Id_Type_Client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `t_d_type_paiement`
--
ALTER TABLE `t_d_type_paiement`
  MODIFY `Id_TypePaiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `t_d_user`
--
ALTER TABLE `t_d_user`
  MODIFY `Id_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `t_d_usertype`
--
ALTER TABLE `t_d_usertype`
  MODIFY `Id_UserType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_d_adresse`
--
ALTER TABLE `t_d_adresse`
  ADD CONSTRAINT `t_d_adresse_ibfk_1` FOREIGN KEY (`Id_Client`) REFERENCES `t_d_client` (`Id_Client`);

--
-- Contraintes pour la table `t_d_adressecommande`
--
ALTER TABLE `t_d_adressecommande`
  ADD CONSTRAINT `t_d_adressecommande_ibfk_1` FOREIGN KEY (`Id_Commande`) REFERENCES `t_d_commande` (`Id_Commande`),
  ADD CONSTRAINT `t_d_adressecommande_ibfk_2` FOREIGN KEY (`Id_Adresse`) REFERENCES `t_d_adresse` (`Id_Adresse`),
  ADD CONSTRAINT `t_d_adressecommande_ibfk_3` FOREIGN KEY (`Id_Type`) REFERENCES `t_d_type_adresse` (`Id_Type`);

--
-- Contraintes pour la table `t_d_categorie`
--
ALTER TABLE `t_d_categorie`
  ADD CONSTRAINT `t_d_categorie_ibfk_1` FOREIGN KEY (`Id_Categorie_Parent`) REFERENCES `t_d_categorie` (`Id_Categorie`);

--
-- Contraintes pour la table `t_d_client`
--
ALTER TABLE `t_d_client`
  ADD CONSTRAINT `t_d_client_ibfk_1` FOREIGN KEY (`Id_Commercial`) REFERENCES `t_d_commercial` (`Id_Commercial`),
  ADD CONSTRAINT `t_d_client_ibfk_2` FOREIGN KEY (`Id_Type_Client`) REFERENCES `t_d_type_client` (`Id_Type_Client`),
  ADD CONSTRAINT `t_d_client_ibfk_user` FOREIGN KEY (`Id_User`) REFERENCES `t_d_user` (`Id_User`);

--
-- Contraintes pour la table `t_d_commande`
--
ALTER TABLE `t_d_commande`
  ADD CONSTRAINT `t_d_commande_ibfk_1` FOREIGN KEY (`Id_Statut`) REFERENCES `t_d_statut_commande` (`Id_Statut`),
  ADD CONSTRAINT `t_d_commande_ibfk_2` FOREIGN KEY (`Id_TypePaiement`) REFERENCES `t_d_type_paiement` (`Id_TypePaiement`),
  ADD CONSTRAINT `t_d_commande_ibfk_3` FOREIGN KEY (`Id_Client`) REFERENCES `t_d_client` (`Id_Client`);

--
-- Contraintes pour la table `t_d_facture`
--
ALTER TABLE `t_d_facture`
  ADD CONSTRAINT `t_d_facture_ibfk_1` FOREIGN KEY (`Id_Commande`) REFERENCES `t_d_commande` (`Id_Commande`);

--
-- Contraintes pour la table `t_d_lignecommande`
--
ALTER TABLE `t_d_lignecommande`
  ADD CONSTRAINT `t_d_lignecommande_ibfk_1` FOREIGN KEY (`Id_Commande`) REFERENCES `t_d_commande` (`Id_Commande`),
  ADD CONSTRAINT `t_d_lignecommande_ibfk_2` FOREIGN KEY (`Id_Produit`) REFERENCES `t_d_produit` (`Id_Produit`);

--
-- Contraintes pour la table `t_d_produit`
--
ALTER TABLE `t_d_produit`
  ADD CONSTRAINT `t_d_produit_ibfk_1` FOREIGN KEY (`Id_Fournisseur`) REFERENCES `t_d_fournisseur` (`Id_Fournisseur`),
  ADD CONSTRAINT `t_d_produit_ibfk_2` FOREIGN KEY (`Id_Categorie`) REFERENCES `t_d_categorie` (`Id_Categorie`);

--
-- Contraintes pour la table `t_d_user`
--
ALTER TABLE `t_d_user`
  ADD CONSTRAINT `t_d_user_ibfk_1` FOREIGN KEY (`Id_UserType`) REFERENCES `t_d_usertype` (`Id_UserType`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
