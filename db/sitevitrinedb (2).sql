-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 14 juil. 2025 à 15:22
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sitevitrinedb`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

CREATE TABLE `actualites` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `date_publication` datetime NOT NULL DEFAULT current_timestamp(),
  `statut` enum('actif','inactif') DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actualites`
--

INSERT INTO `actualites` (`id`, `titre`, `description`, `image`, `date_publication`, `statut`) VALUES
(2, 'Debut des formation de la premiere vague 🌊', 'Annonce 📢', 'uploads/1751495355_IMG-20231112-WA0088.jpg', '2025-07-03 03:59:00', 'actif'),
(3, 'Debut des formation de la premiere vague', 'description test', 'uploads/1751533566_IMG-20231112-WA0073.jpg', '2025-07-03 14:36:06', 'actif'),
(4, 'Actuelité de Betuole', 'detail test', 'uploads/1751743172_IMG-20231112-WA0069.jpg', '2025-07-06 00:49:00', 'actif'),
(5, 'Remise des parchemin et des diplome de nos brillants apprenants', 'demo test', 'uploads/1751743499_IMG-20231112-WA0102.jpg', '2025-07-06 00:54:59', 'actif'),
(7, 'demo sport', 'sport a betuoe tout les lundi', 'uploads/1751743840_IMG-20231112-WA0079.jpg', '2025-07-06 01:00:40', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `annees`
--

CREATE TABLE `annees` (
  `id` int(11) NOT NULL,
  `annee` varchar(9) NOT NULL,
  `statut` enum('active','archive') NOT NULL DEFAULT 'active',
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `annees`
--

INSERT INTO `annees` (`id`, `annee`, `statut`, `date_debut`, `date_fin`, `date_creation`) VALUES
(1, '2025', 'active', '0000-00-00', '0000-00-00', '2025-07-08 15:56:27'),
(2, '2025', 'active', '0000-00-00', '0000-00-00', '2025-07-08 16:00:24'),
(3, '2024-2025', 'archive', '0000-00-00', '0000-00-00', '2025-07-08 17:24:56');

-- --------------------------------------------------------

--
-- Structure de la table `archives`
--

CREATE TABLE `archives` (
  `id` int(11) NOT NULL,
  `annee` varchar(10) NOT NULL,
  `donnees` longtext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_archive` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `archives`
--

INSERT INTO `archives` (`id`, `annee`, `donnees`, `description`, `date_archive`) VALUES
(1, '2025', '{\"utilisateurs\":[{\"id\":\"14\",\"matricule\":\"BT-user0215\",\"nom\":\"Adel\",\"prenom\":\"Cadet\",\"sexe\":\"M\",\"date_naissance\":\"2025-05-15\",\"cni\":\"CNI0258\",\"email\":\"cadet@gmail.com\",\"telephone\":\"15425658\",\"role\":\"apprenant\",\"date_inscription\":\"2025-06-29 08:07:57\",\"filiere\":\"Formation en soins de Visage pro\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"15\",\"matricule\":\"BT-fe2525\",\"nom\":\"Alexenders\",\"prenom\":\"Martinelie\",\"sexe\":\"M\",\"date_naissance\":\"2025-06-07\",\"cni\":\"KITLT1245\",\"email\":\"alex@gmail.com\",\"telephone\":\"658789858\",\"role\":\"apprenant\",\"date_inscription\":\"2025-06-29 08:10:36\",\"filiere\":\"Formation en Massage Professionnel\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"16\",\"matricule\":\"motdepasse123\",\"nom\":\"claude\",\"prenom\":\"Bernard\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"KIT177\",\"email\":\"claude@gmail.com\",\"telephone\":\"678787878\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-02 07:40:56\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"17\",\"matricule\":\"BT-2025-07-0010-04\",\"nom\":\"ARIANE\",\"prenom\":\"NGUIATAZUING\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"KIT2343\",\"email\":\"tete@gmail.com\",\"telephone\":\"0679164801\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-04 21:04:07\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"18\",\"matricule\":\"BT-2025-07-0011-04\",\"nom\":\"betuol\",\"prenom\":\"cerena\",\"sexe\":\"F\",\"date_naissance\":\"2025-07-02\",\"cni\":\"LUJI098\",\"email\":\"CERENA@gmail.com\",\"telephone\":\"679184101\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-04 21:15:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"19\",\"matricule\":\"BT-2025-07-0012-04\",\"nom\":\"DIALLO\",\"prenom\":\"Mariam\",\"sexe\":\"F\",\"date_naissance\":\"2000-04-12\",\"cni\":\"CNI001\",\"email\":\"mariam@gmail.com\",\"telephone\":\"670000001\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:30:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"20\",\"matricule\":\"BT-2025-07-0013-04\",\"nom\":\"KAMDEM\",\"prenom\":\"Sylvain\",\"sexe\":\"M\",\"date_naissance\":\"1998-12-03\",\"cni\":\"CNI002\",\"email\":\"sylvain.kamdem@gmail.com\",\"telephone\":\"670000002\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:35:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"21\",\"matricule\":\"BT-2025-07-0014-04\",\"nom\":\"NDOUMBE\",\"prenom\":\"Patricia\",\"sexe\":\"F\",\"date_naissance\":\"2001-02-25\",\"cni\":\"CNI003\",\"email\":\"patricia.ndoumbe@gmail.com\",\"telephone\":\"670000003\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:40:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"22\",\"matricule\":\"BT-2025-07-0015-04\",\"nom\":\"MBOUA\",\"prenom\":\"Jean-Pierre\",\"sexe\":\"M\",\"date_naissance\":\"1997-07-15\",\"cni\":\"CNI004\",\"email\":\"jean.mboua@gmail.com\",\"telephone\":\"670000004\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:45:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"23\",\"matricule\":\"BT-2025-07-0016-04\",\"nom\":\"FOTSO Adrian\",\"prenom\":\"Camille\",\"sexe\":\"F\",\"date_naissance\":\"1999-09-10\",\"cni\":\"CNI005\",\"email\":\"camille.fotso@gmail.com\",\"telephone\":\"670000005\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:50:00\",\"filiere\":\"Formation en Gommage pro\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"24\",\"matricule\":\"BT-2025-07-0017-04\",\"nom\":\"EKAMBI\",\"prenom\":\"Roger\",\"sexe\":\"M\",\"date_naissance\":\"1996-11-22\",\"cni\":\"CNI006\",\"email\":\"roger.ekambi@gmail.com\",\"telephone\":\"670000006\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:55:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"25\",\"matricule\":\"BT-2025-07-0018-04\",\"nom\":\"MAKON\",\"prenom\":\"Lucie\",\"sexe\":\"F\",\"date_naissance\":\"1995-05-05\",\"cni\":\"CNI007\",\"email\":\"lucie.makon@gmail.com\",\"telephone\":\"670000007\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:00:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"26\",\"matricule\":\"BT-2025-07-0019-04\",\"nom\":\"NDONGO\",\"prenom\":\"Michel\",\"sexe\":\"M\",\"date_naissance\":\"1998-08-18\",\"cni\":\"CNI008\",\"email\":\"michel.ndongo@gmail.com\",\"telephone\":\"670000008\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:05:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"27\",\"matricule\":\"BT-2025-07-0020-04\",\"nom\":\"TCHANA\",\"prenom\":\"Annie\",\"sexe\":\"F\",\"date_naissance\":\"2002-01-30\",\"cni\":\"CNI009\",\"email\":\"annie.tchana@gmail.com\",\"telephone\":\"670000009\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:10:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"28\",\"matricule\":\"BT-2025-07-0021-04\",\"nom\":\"YEMELONG\",\"prenom\":\"Bruno\",\"sexe\":\"M\",\"date_naissance\":\"1994-06-12\",\"cni\":\"CNI010\",\"email\":\"bruno.yemelong@gmail.com\",\"telephone\":\"670000010\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:15:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"30\",\"matricule\":\"BT-2025-07-0013-08\",\"nom\":\"Abdel axis\",\"prenom\":\"Camal Rodrigez\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"CNI1512\",\"email\":\"axis@gmail.com\",\"telephone\":\"690000005\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-08 07:56:26\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"32\",\"matricule\":\"BT-2025-07-0014-08\",\"nom\":\"Danh evans\",\"prenom\":\"Sidoine\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"CNI01425\",\"email\":\"evans@gmail.com\",\"telephone\":\"691210114\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-08 08:08:35\",\"filiere\":\"Formation en MAnicure\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"}],\"formations\":[{\"id\":\"7\",\"nom\":\"Formation en Gommage pro\",\"description\":\"formez vous en gommage professionnel et devenez un productrice de revenue mensuel de plus de 300 000F\",\"prix\":\"30000.00\",\"quantite\":\"30\",\"image\":\"uploads\\/1751420232_gommage.jpg\",\"date_ajout\":\"2025-07-02 07:07:12\"},{\"id\":\"8\",\"nom\":\"Formation en Esth\\u00e9tique Professionnel\",\"description\":\"Apprennez l\'esthetique en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'esthetique avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"50000.00\",\"quantite\":\"30\",\"image\":\"uploads\\/1751440670_esthetiques.jpg\",\"date_ajout\":\"2025-07-02 12:47:50\"},{\"id\":\"9\",\"nom\":\"Formation en Manicure Professionnelle\",\"description\":\"Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"45000.00\",\"quantite\":\"35\",\"image\":\"uploads\\/1751440772_manicure.jpg\",\"date_ajout\":\"2025-07-02 12:49:32\"},{\"id\":\"10\",\"nom\":\"Formation en Massage Professionnel\",\"description\":\"Apprennez le Massage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro du Massage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"40000.00\",\"quantite\":\"25\",\"image\":\"uploads\\/1751440862_MASSAGE.jpg\",\"date_ajout\":\"2025-07-02 12:51:02\"},{\"id\":\"11\",\"nom\":\"Formation en onglerie Professionnel\",\"description\":\"Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"55000.00\",\"quantite\":\"35\",\"image\":\"uploads\\/1751440938_ONGLERIE1.jpg\",\"date_ajout\":\"2025-07-02 12:52:18\"},{\"id\":\"12\",\"nom\":\"Formation en Pedicure Professionnel\",\"description\":\"Apprennez la Pedicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de La pedicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"50000.00\",\"quantite\":\"40\",\"image\":\"uploads\\/1751441196_ONGLERIE5.jpg\",\"date_ajout\":\"2025-07-02 12:56:36\"},{\"id\":\"13\",\"nom\":\"Formation en soins de Visage pro\",\"description\":\"Apprennez les soins de visage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro des soins de visage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"75000.00\",\"quantite\":\"60\",\"image\":\"uploads\\/1751441281_soins.jpg\",\"date_ajout\":\"2025-07-02 12:58:01\"},{\"id\":\"14\",\"nom\":\"Formation en Onglerie\",\"description\":\"Grace \\u00e0 nos enseignants chevronn\\u00e9 Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"60000.00\",\"quantite\":\"45\",\"image\":\"uploads\\/1751441413_salle1.jp2.jpg\",\"date_ajout\":\"2025-07-02 13:00:13\"},{\"id\":\"15\",\"nom\":\"Formation en MAnicure\",\"description\":\"Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"70000.00\",\"quantite\":\"50\",\"image\":\"uploads\\/1751495653_ONGLERIE3.jpg\",\"date_ajout\":\"2025-07-03 04:04:13\"}],\"paiements\":[{\"id\":\"13\",\"id_utilisateur\":\"2\",\"reference\":\"\",\"montant\":\"75000.00\",\"mode_paiement\":\"MTN MONEY\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-06-29 06:47:59\",\"type_paiement\":null},{\"id\":\"14\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-02 07:48:26\",\"type_paiement\":null},{\"id\":\"15\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-02 07:51:16\",\"type_paiement\":null},{\"id\":\"16\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-03 14:40:31\",\"type_paiement\":null},{\"id\":\"17\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 08:42:50\",\"type_paiement\":null},{\"id\":\"18\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 08:43:21\",\"type_paiement\":null},{\"id\":\"19\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 10:13:57\",\"type_paiement\":null},{\"id\":\"20\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"15000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 20:57:16\",\"type_paiement\":null},{\"id\":\"21\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 21:13:59\",\"type_paiement\":null},{\"id\":\"38\",\"id_utilisateur\":\"15\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:00:00\",\"type_paiement\":null},{\"id\":\"40\",\"id_utilisateur\":\"17\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:30:00\",\"type_paiement\":null},{\"id\":\"41\",\"id_utilisateur\":\"18\",\"reference\":\"\",\"montant\":\"70000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:45:00\",\"type_paiement\":null},{\"id\":\"42\",\"id_utilisateur\":\"19\",\"reference\":\"\",\"montant\":\"25000.00\",\"mode_paiement\":\"Cash\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:00:00\",\"type_paiement\":null},{\"id\":\"43\",\"id_utilisateur\":\"20\",\"reference\":\"\",\"montant\":\"80000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-05 11:15:00\",\"type_paiement\":null},{\"id\":\"44\",\"id_utilisateur\":\"21\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"VISA\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:30:00\",\"type_paiement\":null},{\"id\":\"45\",\"id_utilisateur\":\"22\",\"reference\":\"\",\"montant\":\"65000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:45:00\",\"type_paiement\":null},{\"id\":\"46\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"90000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 12:00:00\",\"type_paiement\":null},{\"id\":\"47\",\"id_utilisateur\":\"24\",\"reference\":\"\",\"montant\":\"55000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 12:15:00\",\"type_paiement\":null},{\"id\":\"48\",\"id_utilisateur\":\"25\",\"reference\":\"\",\"montant\":\"35000.00\",\"mode_paiement\":\"Cash\",\"statut\":\"rembours\\u00e9\",\"date_paiement\":\"2025-07-05 12:30:00\",\"type_paiement\":null},{\"id\":\"49\",\"id_utilisateur\":\"26\",\"reference\":\"\",\"montant\":\"40000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 12:45:00\",\"type_paiement\":null},{\"id\":\"50\",\"id_utilisateur\":\"27\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 13:00:00\",\"type_paiement\":null},{\"id\":\"51\",\"id_utilisateur\":\"28\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 13:15:00\",\"type_paiement\":null},{\"id\":\"52\",\"id_utilisateur\":\"16\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-06 06:41:37\",\"type_paiement\":null},{\"id\":\"53\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-06 20:50:47\",\"type_paiement\":null},{\"id\":\"54\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-06 22:10:29\",\"type_paiement\":null},{\"id\":\"55\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-07 11:11:08\",\"type_paiement\":null},{\"id\":\"56\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"45000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-07 11:13:16\",\"type_paiement\":null},{\"id\":\"57\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-08 05:47:21\",\"type_paiement\":null},{\"id\":\"58\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:17:53\",\"type_paiement\":null},{\"id\":\"59\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"20000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:22:27\",\"type_paiement\":null},{\"id\":\"60\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"15000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:24:57\",\"type_paiement\":null},{\"id\":\"61\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"1000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:28:50\",\"type_paiement\":null},{\"id\":\"62\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"200.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-08 06:33:27\",\"type_paiement\":null},{\"id\":\"63\",\"id_utilisateur\":\"14\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"MOMO\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:43:22\",\"type_paiement\":null},{\"id\":\"64\",\"id_utilisateur\":\"15\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 07:03:53\",\"type_paiement\":\"Frais d\'inscriptions\"},{\"id\":\"65\",\"id_utilisateur\":\"32\",\"reference\":\"BTT00057\",\"montant\":\"10000.00\",\"mode_paiement\":\"MOMO\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 08:20:48\",\"type_paiement\":\"Frais d\'inscription\"}],\"actualites\":[{\"id\":\"2\",\"titre\":\"Debut des formation de la premiere vague \\ud83c\\udf0a\",\"description\":\"Annonce \\ud83d\\udce2\",\"image\":\"uploads\\/1751495355_IMG-20231112-WA0088.jpg\",\"date_publication\":\"2025-07-03 03:59:00\",\"statut\":\"actif\"},{\"id\":\"3\",\"titre\":\"Debut des formation de la premiere vague\",\"description\":\"description test\",\"image\":\"uploads\\/1751533566_IMG-20231112-WA0073.jpg\",\"date_publication\":\"2025-07-03 14:36:06\",\"statut\":\"actif\"},{\"id\":\"4\",\"titre\":\"Actuelit\\u00e9 de Betuole\",\"description\":\"detail test\",\"image\":\"uploads\\/1751743172_IMG-20231112-WA0069.jpg\",\"date_publication\":\"2025-07-06 00:49:00\",\"statut\":\"actif\"},{\"id\":\"5\",\"titre\":\"Remise des parchemin et des diplome de nos brillants apprenants\",\"description\":\"demo test\",\"image\":\"uploads\\/1751743499_IMG-20231112-WA0102.jpg\",\"date_publication\":\"2025-07-06 00:54:59\",\"statut\":\"actif\"},{\"id\":\"7\",\"titre\":\"demo sport\",\"description\":\"sport a betuoe tout les lundi\",\"image\":\"uploads\\/1751743840_IMG-20231112-WA0079.jpg\",\"date_publication\":\"2025-07-06 01:00:40\",\"statut\":\"actif\"}],\"contact_visiteurs\":[{\"id\":\"1\",\"nom\":\"ceredine FOGANKENG KENFACK\",\"email\":\"evaricekuete2@gmail.com\",\"objet\":\"dede\",\"message\":\"test\",\"date_envoi\":\"2025-07-06 04:49:24\"},{\"id\":\"2\",\"nom\":\"ceredine FOGANKENG KENFACK\",\"email\":\"evaricekuete2@gmail.com\",\"objet\":\"test\",\"message\":\"test message\",\"date_envoi\":\"2025-07-06 20:56:07\"}]}', NULL, '2025-07-08 15:56:26'),
(2, '2025', '{\"utilisateurs\":[{\"id\":\"14\",\"matricule\":\"BT-user0215\",\"nom\":\"Adel\",\"prenom\":\"Cadet\",\"sexe\":\"M\",\"date_naissance\":\"2025-05-15\",\"cni\":\"CNI0258\",\"email\":\"cadet@gmail.com\",\"telephone\":\"15425658\",\"role\":\"apprenant\",\"date_inscription\":\"2025-06-29 08:07:57\",\"filiere\":\"Formation en soins de Visage pro\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"15\",\"matricule\":\"BT-fe2525\",\"nom\":\"Alexenders\",\"prenom\":\"Martinelie\",\"sexe\":\"M\",\"date_naissance\":\"2025-06-07\",\"cni\":\"KITLT1245\",\"email\":\"alex@gmail.com\",\"telephone\":\"658789858\",\"role\":\"apprenant\",\"date_inscription\":\"2025-06-29 08:10:36\",\"filiere\":\"Formation en Massage Professionnel\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"16\",\"matricule\":\"motdepasse123\",\"nom\":\"claude\",\"prenom\":\"Bernard\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"KIT177\",\"email\":\"claude@gmail.com\",\"telephone\":\"678787878\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-02 07:40:56\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"17\",\"matricule\":\"BT-2025-07-0010-04\",\"nom\":\"ARIANE\",\"prenom\":\"NGUIATAZUING\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"KIT2343\",\"email\":\"tete@gmail.com\",\"telephone\":\"0679164801\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-04 21:04:07\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"18\",\"matricule\":\"BT-2025-07-0011-04\",\"nom\":\"betuol\",\"prenom\":\"cerena\",\"sexe\":\"F\",\"date_naissance\":\"2025-07-02\",\"cni\":\"LUJI098\",\"email\":\"CERENA@gmail.com\",\"telephone\":\"679184101\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-04 21:15:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"19\",\"matricule\":\"BT-2025-07-0012-04\",\"nom\":\"DIALLO\",\"prenom\":\"Mariam\",\"sexe\":\"F\",\"date_naissance\":\"2000-04-12\",\"cni\":\"CNI001\",\"email\":\"mariam@gmail.com\",\"telephone\":\"670000001\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:30:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"20\",\"matricule\":\"BT-2025-07-0013-04\",\"nom\":\"KAMDEM\",\"prenom\":\"Sylvain\",\"sexe\":\"M\",\"date_naissance\":\"1998-12-03\",\"cni\":\"CNI002\",\"email\":\"sylvain.kamdem@gmail.com\",\"telephone\":\"670000002\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:35:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"21\",\"matricule\":\"BT-2025-07-0014-04\",\"nom\":\"NDOUMBE\",\"prenom\":\"Patricia\",\"sexe\":\"F\",\"date_naissance\":\"2001-02-25\",\"cni\":\"CNI003\",\"email\":\"patricia.ndoumbe@gmail.com\",\"telephone\":\"670000003\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:40:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"22\",\"matricule\":\"BT-2025-07-0015-04\",\"nom\":\"MBOUA\",\"prenom\":\"Jean-Pierre\",\"sexe\":\"M\",\"date_naissance\":\"1997-07-15\",\"cni\":\"CNI004\",\"email\":\"jean.mboua@gmail.com\",\"telephone\":\"670000004\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:45:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"23\",\"matricule\":\"BT-2025-07-0016-04\",\"nom\":\"FOTSO Adrian\",\"prenom\":\"Camille\",\"sexe\":\"F\",\"date_naissance\":\"1999-09-10\",\"cni\":\"CNI005\",\"email\":\"camille.fotso@gmail.com\",\"telephone\":\"670000005\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:50:00\",\"filiere\":\"Formation en Gommage pro\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"24\",\"matricule\":\"BT-2025-07-0017-04\",\"nom\":\"EKAMBI\",\"prenom\":\"Roger\",\"sexe\":\"M\",\"date_naissance\":\"1996-11-22\",\"cni\":\"CNI006\",\"email\":\"roger.ekambi@gmail.com\",\"telephone\":\"670000006\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:55:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"25\",\"matricule\":\"BT-2025-07-0018-04\",\"nom\":\"MAKON\",\"prenom\":\"Lucie\",\"sexe\":\"F\",\"date_naissance\":\"1995-05-05\",\"cni\":\"CNI007\",\"email\":\"lucie.makon@gmail.com\",\"telephone\":\"670000007\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:00:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"26\",\"matricule\":\"BT-2025-07-0019-04\",\"nom\":\"NDONGO\",\"prenom\":\"Michel\",\"sexe\":\"M\",\"date_naissance\":\"1998-08-18\",\"cni\":\"CNI008\",\"email\":\"michel.ndongo@gmail.com\",\"telephone\":\"670000008\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:05:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"27\",\"matricule\":\"BT-2025-07-0020-04\",\"nom\":\"TCHANA\",\"prenom\":\"Annie\",\"sexe\":\"F\",\"date_naissance\":\"2002-01-30\",\"cni\":\"CNI009\",\"email\":\"annie.tchana@gmail.com\",\"telephone\":\"670000009\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:10:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"28\",\"matricule\":\"BT-2025-07-0021-04\",\"nom\":\"YEMELONG\",\"prenom\":\"Bruno\",\"sexe\":\"M\",\"date_naissance\":\"1994-06-12\",\"cni\":\"CNI010\",\"email\":\"bruno.yemelong@gmail.com\",\"telephone\":\"670000010\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:15:00\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"30\",\"matricule\":\"BT-2025-07-0013-08\",\"nom\":\"Abdel axis\",\"prenom\":\"Camal Rodrigez\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"CNI1512\",\"email\":\"axis@gmail.com\",\"telephone\":\"690000005\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-08 07:56:26\",\"filiere\":null,\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"32\",\"matricule\":\"BT-2025-07-0014-08\",\"nom\":\"Danh evans\",\"prenom\":\"Sidoine\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"CNI01425\",\"email\":\"evans@gmail.com\",\"telephone\":\"691210114\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-08 08:08:35\",\"filiere\":\"Formation en MAnicure\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"}],\"formations\":[{\"id\":\"7\",\"nom\":\"Formation en Gommage pro\",\"description\":\"formez vous en gommage professionnel et devenez un productrice de revenue mensuel de plus de 300 000F\",\"prix\":\"30000.00\",\"quantite\":\"30\",\"image\":\"uploads\\/1751420232_gommage.jpg\",\"date_ajout\":\"2025-07-02 07:07:12\"},{\"id\":\"8\",\"nom\":\"Formation en Esth\\u00e9tique Professionnel\",\"description\":\"Apprennez l\'esthetique en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'esthetique avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"50000.00\",\"quantite\":\"30\",\"image\":\"uploads\\/1751440670_esthetiques.jpg\",\"date_ajout\":\"2025-07-02 12:47:50\"},{\"id\":\"9\",\"nom\":\"Formation en Manicure Professionnelle\",\"description\":\"Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"45000.00\",\"quantite\":\"35\",\"image\":\"uploads\\/1751440772_manicure.jpg\",\"date_ajout\":\"2025-07-02 12:49:32\"},{\"id\":\"10\",\"nom\":\"Formation en Massage Professionnel\",\"description\":\"Apprennez le Massage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro du Massage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"40000.00\",\"quantite\":\"25\",\"image\":\"uploads\\/1751440862_MASSAGE.jpg\",\"date_ajout\":\"2025-07-02 12:51:02\"},{\"id\":\"11\",\"nom\":\"Formation en onglerie Professionnel\",\"description\":\"Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"55000.00\",\"quantite\":\"35\",\"image\":\"uploads\\/1751440938_ONGLERIE1.jpg\",\"date_ajout\":\"2025-07-02 12:52:18\"},{\"id\":\"12\",\"nom\":\"Formation en Pedicure Professionnel\",\"description\":\"Apprennez la Pedicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de La pedicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"50000.00\",\"quantite\":\"40\",\"image\":\"uploads\\/1751441196_ONGLERIE5.jpg\",\"date_ajout\":\"2025-07-02 12:56:36\"},{\"id\":\"13\",\"nom\":\"Formation en soins de Visage pro\",\"description\":\"Apprennez les soins de visage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro des soins de visage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"75000.00\",\"quantite\":\"60\",\"image\":\"uploads\\/1751441281_soins.jpg\",\"date_ajout\":\"2025-07-02 12:58:01\"},{\"id\":\"14\",\"nom\":\"Formation en Onglerie\",\"description\":\"Grace \\u00e0 nos enseignants chevronn\\u00e9 Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"60000.00\",\"quantite\":\"45\",\"image\":\"uploads\\/1751441413_salle1.jp2.jpg\",\"date_ajout\":\"2025-07-02 13:00:13\"},{\"id\":\"15\",\"nom\":\"Formation en MAnicure\",\"description\":\"Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"70000.00\",\"quantite\":\"50\",\"image\":\"uploads\\/1751495653_ONGLERIE3.jpg\",\"date_ajout\":\"2025-07-03 04:04:13\"}],\"paiements\":[{\"id\":\"13\",\"id_utilisateur\":\"2\",\"reference\":\"\",\"montant\":\"75000.00\",\"mode_paiement\":\"MTN MONEY\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-06-29 06:47:59\",\"type_paiement\":null},{\"id\":\"14\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-02 07:48:26\",\"type_paiement\":null},{\"id\":\"15\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-02 07:51:16\",\"type_paiement\":null},{\"id\":\"16\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-03 14:40:31\",\"type_paiement\":null},{\"id\":\"17\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 08:42:50\",\"type_paiement\":null},{\"id\":\"18\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 08:43:21\",\"type_paiement\":null},{\"id\":\"19\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 10:13:57\",\"type_paiement\":null},{\"id\":\"20\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"15000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 20:57:16\",\"type_paiement\":null},{\"id\":\"21\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 21:13:59\",\"type_paiement\":null},{\"id\":\"38\",\"id_utilisateur\":\"15\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:00:00\",\"type_paiement\":null},{\"id\":\"40\",\"id_utilisateur\":\"17\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:30:00\",\"type_paiement\":null},{\"id\":\"41\",\"id_utilisateur\":\"18\",\"reference\":\"\",\"montant\":\"70000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:45:00\",\"type_paiement\":null},{\"id\":\"42\",\"id_utilisateur\":\"19\",\"reference\":\"\",\"montant\":\"25000.00\",\"mode_paiement\":\"Cash\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:00:00\",\"type_paiement\":null},{\"id\":\"43\",\"id_utilisateur\":\"20\",\"reference\":\"\",\"montant\":\"80000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-05 11:15:00\",\"type_paiement\":null},{\"id\":\"44\",\"id_utilisateur\":\"21\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"VISA\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:30:00\",\"type_paiement\":null},{\"id\":\"45\",\"id_utilisateur\":\"22\",\"reference\":\"\",\"montant\":\"65000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:45:00\",\"type_paiement\":null},{\"id\":\"46\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"90000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 12:00:00\",\"type_paiement\":null},{\"id\":\"47\",\"id_utilisateur\":\"24\",\"reference\":\"\",\"montant\":\"55000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 12:15:00\",\"type_paiement\":null},{\"id\":\"48\",\"id_utilisateur\":\"25\",\"reference\":\"\",\"montant\":\"35000.00\",\"mode_paiement\":\"Cash\",\"statut\":\"rembours\\u00e9\",\"date_paiement\":\"2025-07-05 12:30:00\",\"type_paiement\":null},{\"id\":\"49\",\"id_utilisateur\":\"26\",\"reference\":\"\",\"montant\":\"40000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 12:45:00\",\"type_paiement\":null},{\"id\":\"50\",\"id_utilisateur\":\"27\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 13:00:00\",\"type_paiement\":null},{\"id\":\"51\",\"id_utilisateur\":\"28\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 13:15:00\",\"type_paiement\":null},{\"id\":\"52\",\"id_utilisateur\":\"16\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-06 06:41:37\",\"type_paiement\":null},{\"id\":\"53\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-06 20:50:47\",\"type_paiement\":null},{\"id\":\"54\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-06 22:10:29\",\"type_paiement\":null},{\"id\":\"55\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-07 11:11:08\",\"type_paiement\":null},{\"id\":\"56\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"45000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-07 11:13:16\",\"type_paiement\":null},{\"id\":\"57\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-08 05:47:21\",\"type_paiement\":null},{\"id\":\"58\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:17:53\",\"type_paiement\":null},{\"id\":\"59\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"20000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:22:27\",\"type_paiement\":null},{\"id\":\"60\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"15000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:24:57\",\"type_paiement\":null},{\"id\":\"61\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"1000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:28:50\",\"type_paiement\":null},{\"id\":\"62\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"200.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-08 06:33:27\",\"type_paiement\":null},{\"id\":\"63\",\"id_utilisateur\":\"14\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"MOMO\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:43:22\",\"type_paiement\":null},{\"id\":\"64\",\"id_utilisateur\":\"15\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 07:03:53\",\"type_paiement\":\"Frais d\'inscriptions\"},{\"id\":\"65\",\"id_utilisateur\":\"32\",\"reference\":\"BTT00057\",\"montant\":\"10000.00\",\"mode_paiement\":\"MOMO\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 08:20:48\",\"type_paiement\":\"Frais d\'inscription\"}],\"actualites\":[{\"id\":\"2\",\"titre\":\"Debut des formation de la premiere vague \\ud83c\\udf0a\",\"description\":\"Annonce \\ud83d\\udce2\",\"image\":\"uploads\\/1751495355_IMG-20231112-WA0088.jpg\",\"date_publication\":\"2025-07-03 03:59:00\",\"statut\":\"actif\"},{\"id\":\"3\",\"titre\":\"Debut des formation de la premiere vague\",\"description\":\"description test\",\"image\":\"uploads\\/1751533566_IMG-20231112-WA0073.jpg\",\"date_publication\":\"2025-07-03 14:36:06\",\"statut\":\"actif\"},{\"id\":\"4\",\"titre\":\"Actuelit\\u00e9 de Betuole\",\"description\":\"detail test\",\"image\":\"uploads\\/1751743172_IMG-20231112-WA0069.jpg\",\"date_publication\":\"2025-07-06 00:49:00\",\"statut\":\"actif\"},{\"id\":\"5\",\"titre\":\"Remise des parchemin et des diplome de nos brillants apprenants\",\"description\":\"demo test\",\"image\":\"uploads\\/1751743499_IMG-20231112-WA0102.jpg\",\"date_publication\":\"2025-07-06 00:54:59\",\"statut\":\"actif\"},{\"id\":\"7\",\"titre\":\"demo sport\",\"description\":\"sport a betuoe tout les lundi\",\"image\":\"uploads\\/1751743840_IMG-20231112-WA0079.jpg\",\"date_publication\":\"2025-07-06 01:00:40\",\"statut\":\"actif\"}],\"contact_visiteurs\":[{\"id\":\"1\",\"nom\":\"ceredine FOGANKENG KENFACK\",\"email\":\"evaricekuete2@gmail.com\",\"objet\":\"dede\",\"message\":\"test\",\"date_envoi\":\"2025-07-06 04:49:24\"},{\"id\":\"2\",\"nom\":\"ceredine FOGANKENG KENFACK\",\"email\":\"evaricekuete2@gmail.com\",\"objet\":\"test\",\"message\":\"test message\",\"date_envoi\":\"2025-07-06 20:56:07\"}]}', NULL, '2025-07-08 16:00:24');
INSERT INTO `archives` (`id`, `annee`, `donnees`, `description`, `date_archive`) VALUES
(3, '2024-2025', '{\"utilisateurs\":[{\"id\":\"14\",\"matricule\":\"BT-user0215\",\"nom\":\"Adel\",\"prenom\":\"Cadet\",\"sexe\":\"M\",\"date_naissance\":\"2025-05-15\",\"cni\":\"CNI0258\",\"email\":\"cadet@gmail.com\",\"telephone\":\"15425658\",\"role\":\"apprenant\",\"date_inscription\":\"2025-06-29 08:07:57\",\"filiere\":\"Formation en soins de Visage pro\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"15\",\"matricule\":\"BT-fe2525\",\"nom\":\"Alexenders\",\"prenom\":\"Martinelie\",\"sexe\":\"M\",\"date_naissance\":\"2025-06-07\",\"cni\":\"KITLT1245\",\"email\":\"alex@gmail.com\",\"telephone\":\"658789858\",\"role\":\"apprenant\",\"date_inscription\":\"2025-06-29 08:10:36\",\"filiere\":\"Formation en Massage Professionnel\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"16\",\"matricule\":\"motdepasse123\",\"nom\":\"claude\",\"prenom\":\"Bernard\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"KIT177\",\"email\":\"claude@gmail.com\",\"telephone\":\"678787878\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-02 07:40:56\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"17\",\"matricule\":\"BT-2025-07-0010-04\",\"nom\":\"ARIANE\",\"prenom\":\"NGUIATAZUING\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"KIT2343\",\"email\":\"tete@gmail.com\",\"telephone\":\"0679164801\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-04 21:04:07\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"18\",\"matricule\":\"BT-2025-07-0011-04\",\"nom\":\"betuol\",\"prenom\":\"cerena\",\"sexe\":\"F\",\"date_naissance\":\"2025-07-02\",\"cni\":\"LUJI098\",\"email\":\"CERENA@gmail.com\",\"telephone\":\"679184101\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-04 21:15:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"19\",\"matricule\":\"BT-2025-07-0012-04\",\"nom\":\"DIALLO\",\"prenom\":\"Mariam\",\"sexe\":\"F\",\"date_naissance\":\"2000-04-12\",\"cni\":\"CNI001\",\"email\":\"mariam@gmail.com\",\"telephone\":\"670000001\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:30:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"20\",\"matricule\":\"BT-2025-07-0013-04\",\"nom\":\"KAMDEM\",\"prenom\":\"Sylvain\",\"sexe\":\"M\",\"date_naissance\":\"1998-12-03\",\"cni\":\"CNI002\",\"email\":\"sylvain.kamdem@gmail.com\",\"telephone\":\"670000002\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:35:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"21\",\"matricule\":\"BT-2025-07-0014-04\",\"nom\":\"NDOUMBE\",\"prenom\":\"Patricia\",\"sexe\":\"F\",\"date_naissance\":\"2001-02-25\",\"cni\":\"CNI003\",\"email\":\"patricia.ndoumbe@gmail.com\",\"telephone\":\"670000003\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:40:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"22\",\"matricule\":\"BT-2025-07-0015-04\",\"nom\":\"MBOUA\",\"prenom\":\"Jean-Pierre\",\"sexe\":\"M\",\"date_naissance\":\"1997-07-15\",\"cni\":\"CNI004\",\"email\":\"jean.mboua@gmail.com\",\"telephone\":\"670000004\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:45:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"23\",\"matricule\":\"BT-2025-07-0016-04\",\"nom\":\"FOTSO Adrian\",\"prenom\":\"Camille\",\"sexe\":\"F\",\"date_naissance\":\"1999-09-10\",\"cni\":\"CNI005\",\"email\":\"camille.fotso@gmail.com\",\"telephone\":\"670000005\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:50:00\",\"filiere\":\"Formation en Gommage pro\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"24\",\"matricule\":\"BT-2025-07-0017-04\",\"nom\":\"EKAMBI\",\"prenom\":\"Roger\",\"sexe\":\"M\",\"date_naissance\":\"1996-11-22\",\"cni\":\"CNI006\",\"email\":\"roger.ekambi@gmail.com\",\"telephone\":\"670000006\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 08:55:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"25\",\"matricule\":\"BT-2025-07-0018-04\",\"nom\":\"MAKON\",\"prenom\":\"Lucie\",\"sexe\":\"F\",\"date_naissance\":\"1995-05-05\",\"cni\":\"CNI007\",\"email\":\"lucie.makon@gmail.com\",\"telephone\":\"670000007\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:00:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"26\",\"matricule\":\"BT-2025-07-0019-04\",\"nom\":\"NDONGO\",\"prenom\":\"Michel\",\"sexe\":\"M\",\"date_naissance\":\"1998-08-18\",\"cni\":\"CNI008\",\"email\":\"michel.ndongo@gmail.com\",\"telephone\":\"670000008\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:05:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"27\",\"matricule\":\"BT-2025-07-0020-04\",\"nom\":\"TCHANA\",\"prenom\":\"Annie\",\"sexe\":\"F\",\"date_naissance\":\"2002-01-30\",\"cni\":\"CNI009\",\"email\":\"annie.tchana@gmail.com\",\"telephone\":\"670000009\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:10:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"28\",\"matricule\":\"BT-2025-07-0021-04\",\"nom\":\"YEMELONG\",\"prenom\":\"Bruno\",\"sexe\":\"M\",\"date_naissance\":\"1994-06-12\",\"cni\":\"CNI010\",\"email\":\"bruno.yemelong@gmail.com\",\"telephone\":\"670000010\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-05 09:15:00\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"30\",\"matricule\":\"BT-2025-07-0013-08\",\"nom\":\"Abdel axis\",\"prenom\":\"Camal Rodrigez\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"CNI1512\",\"email\":\"axis@gmail.com\",\"telephone\":\"690000005\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-08 07:56:26\",\"filiere\":\"\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"},{\"id\":\"32\",\"matricule\":\"BT-2025-07-0014-08\",\"nom\":\"Danh evans\",\"prenom\":\"Sidoine\",\"sexe\":\"M\",\"date_naissance\":\"2025-07-01\",\"cni\":\"CNI01425\",\"email\":\"evans@gmail.com\",\"telephone\":\"691210114\",\"role\":\"apprenant\",\"date_inscription\":\"2025-07-08 08:08:35\",\"filiere\":\"Formation en MAnicure\",\"balance\":\"0.00\",\"scolarite_status\":\"non sold\\u00e9\"}],\"formations\":[{\"id\":\"7\",\"nom\":\"Formation en Gommage pro\",\"description\":\"formez vous en gommage professionnel et devenez un productrice de revenue mensuel de plus de 300 000F\",\"prix\":\"30000.00\",\"quantite\":\"30\",\"image\":\"uploads\\/1751420232_gommage.jpg\",\"date_ajout\":\"2025-07-02 07:07:12\"},{\"id\":\"8\",\"nom\":\"Formation en Esth\\u00e9tique Professionnel\",\"description\":\"Apprennez l\'esthetique en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'esthetique avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"50000.00\",\"quantite\":\"30\",\"image\":\"uploads\\/1751440670_esthetiques.jpg\",\"date_ajout\":\"2025-07-02 12:47:50\"},{\"id\":\"9\",\"nom\":\"Formation en Manicure Professionnelle\",\"description\":\"Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"45000.00\",\"quantite\":\"35\",\"image\":\"uploads\\/1751440772_manicure.jpg\",\"date_ajout\":\"2025-07-02 12:49:32\"},{\"id\":\"10\",\"nom\":\"Formation en Massage Professionnel\",\"description\":\"Apprennez le Massage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro du Massage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"40000.00\",\"quantite\":\"25\",\"image\":\"uploads\\/1751440862_MASSAGE.jpg\",\"date_ajout\":\"2025-07-02 12:51:02\"},{\"id\":\"11\",\"nom\":\"Formation en onglerie Professionnel\",\"description\":\"Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"55000.00\",\"quantite\":\"35\",\"image\":\"uploads\\/1751440938_ONGLERIE1.jpg\",\"date_ajout\":\"2025-07-02 12:52:18\"},{\"id\":\"12\",\"nom\":\"Formation en Pedicure Professionnel\",\"description\":\"Apprennez la Pedicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de La pedicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"50000.00\",\"quantite\":\"40\",\"image\":\"uploads\\/1751441196_ONGLERIE5.jpg\",\"date_ajout\":\"2025-07-02 12:56:36\"},{\"id\":\"13\",\"nom\":\"Formation en soins de Visage pro\",\"description\":\"Apprennez les soins de visage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro des soins de visage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"75000.00\",\"quantite\":\"60\",\"image\":\"uploads\\/1751441281_soins.jpg\",\"date_ajout\":\"2025-07-02 12:58:01\"},{\"id\":\"14\",\"nom\":\"Formation en Onglerie\",\"description\":\"Grace \\u00e0 nos enseignants chevronn\\u00e9 Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"60000.00\",\"quantite\":\"45\",\"image\":\"uploads\\/1751441413_salle1.jp2.jpg\",\"date_ajout\":\"2025-07-02 13:00:13\"},{\"id\":\"15\",\"nom\":\"Formation en MAnicure\",\"description\":\"Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre\",\"prix\":\"70000.00\",\"quantite\":\"50\",\"image\":\"uploads\\/1751495653_ONGLERIE3.jpg\",\"date_ajout\":\"2025-07-03 04:04:13\"}],\"paiements\":[{\"id\":\"13\",\"id_utilisateur\":\"2\",\"reference\":\"\",\"montant\":\"75000.00\",\"mode_paiement\":\"MTN MONEY\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-06-29 06:47:59\",\"type_paiement\":\"\"},{\"id\":\"14\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-02 07:48:26\",\"type_paiement\":\"\"},{\"id\":\"15\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-02 07:51:16\",\"type_paiement\":\"\"},{\"id\":\"16\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-03 14:40:31\",\"type_paiement\":\"\"},{\"id\":\"17\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 08:42:50\",\"type_paiement\":\"\"},{\"id\":\"18\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 08:43:21\",\"type_paiement\":\"\"},{\"id\":\"19\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 10:13:57\",\"type_paiement\":\"\"},{\"id\":\"20\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"15000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 20:57:16\",\"type_paiement\":\"\"},{\"id\":\"21\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-04 21:13:59\",\"type_paiement\":\"\"},{\"id\":\"38\",\"id_utilisateur\":\"15\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:00:00\",\"type_paiement\":\"\"},{\"id\":\"40\",\"id_utilisateur\":\"17\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:30:00\",\"type_paiement\":\"\"},{\"id\":\"41\",\"id_utilisateur\":\"18\",\"reference\":\"\",\"montant\":\"70000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 10:45:00\",\"type_paiement\":\"\"},{\"id\":\"42\",\"id_utilisateur\":\"19\",\"reference\":\"\",\"montant\":\"25000.00\",\"mode_paiement\":\"Cash\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:00:00\",\"type_paiement\":\"\"},{\"id\":\"43\",\"id_utilisateur\":\"20\",\"reference\":\"\",\"montant\":\"80000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-05 11:15:00\",\"type_paiement\":\"\"},{\"id\":\"44\",\"id_utilisateur\":\"21\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"VISA\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:30:00\",\"type_paiement\":\"\"},{\"id\":\"45\",\"id_utilisateur\":\"22\",\"reference\":\"\",\"montant\":\"65000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 11:45:00\",\"type_paiement\":\"\"},{\"id\":\"46\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"90000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"rejet\\u00e9\",\"date_paiement\":\"2025-07-05 12:00:00\",\"type_paiement\":\"\"},{\"id\":\"47\",\"id_utilisateur\":\"24\",\"reference\":\"\",\"montant\":\"55000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 12:15:00\",\"type_paiement\":\"\"},{\"id\":\"48\",\"id_utilisateur\":\"25\",\"reference\":\"\",\"montant\":\"35000.00\",\"mode_paiement\":\"Cash\",\"statut\":\"rembours\\u00e9\",\"date_paiement\":\"2025-07-05 12:30:00\",\"type_paiement\":\"\"},{\"id\":\"49\",\"id_utilisateur\":\"26\",\"reference\":\"\",\"montant\":\"40000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 12:45:00\",\"type_paiement\":\"\"},{\"id\":\"50\",\"id_utilisateur\":\"27\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"Paypal\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 13:00:00\",\"type_paiement\":\"\"},{\"id\":\"51\",\"id_utilisateur\":\"28\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-05 13:15:00\",\"type_paiement\":\"\"},{\"id\":\"52\",\"id_utilisateur\":\"16\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-06 06:41:37\",\"type_paiement\":\"\"},{\"id\":\"53\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"30000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-06 20:50:47\",\"type_paiement\":\"\"},{\"id\":\"54\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"60000.00\",\"mode_paiement\":\"Orange\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-06 22:10:29\",\"type_paiement\":\"\"},{\"id\":\"55\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-07 11:11:08\",\"type_paiement\":\"\"},{\"id\":\"56\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"45000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-07 11:13:16\",\"type_paiement\":\"\"},{\"id\":\"57\",\"id_utilisateur\":\"1\",\"reference\":\"\",\"montant\":\"50000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-08 05:47:21\",\"type_paiement\":\"\"},{\"id\":\"58\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:17:53\",\"type_paiement\":\"\"},{\"id\":\"59\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"20000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:22:27\",\"type_paiement\":\"\"},{\"id\":\"60\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"15000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:24:57\",\"type_paiement\":\"\"},{\"id\":\"61\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"1000.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:28:50\",\"type_paiement\":\"\"},{\"id\":\"62\",\"id_utilisateur\":\"23\",\"reference\":\"\",\"montant\":\"200.00\",\"mode_paiement\":\"Esp\\u00e8ces\",\"statut\":\"en attente\",\"date_paiement\":\"2025-07-08 06:33:27\",\"type_paiement\":\"\"},{\"id\":\"63\",\"id_utilisateur\":\"14\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"MOMO\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 06:43:22\",\"type_paiement\":\"\"},{\"id\":\"64\",\"id_utilisateur\":\"15\",\"reference\":\"\",\"montant\":\"10000.00\",\"mode_paiement\":\"MTN\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 07:03:53\",\"type_paiement\":\"Frais d\'inscriptions\"},{\"id\":\"65\",\"id_utilisateur\":\"32\",\"reference\":\"BTT00057\",\"montant\":\"10000.00\",\"mode_paiement\":\"MOMO\",\"statut\":\"valid\\u00e9\",\"date_paiement\":\"2025-07-08 08:20:48\",\"type_paiement\":\"Frais d\'inscription\"}],\"actualites\":[{\"id\":\"2\",\"titre\":\"Debut des formation de la premiere vague \\ud83c\\udf0a\",\"description\":\"Annonce \\ud83d\\udce2\",\"image\":\"uploads\\/1751495355_IMG-20231112-WA0088.jpg\",\"date_publication\":\"2025-07-03 03:59:00\",\"statut\":\"actif\"},{\"id\":\"3\",\"titre\":\"Debut des formation de la premiere vague\",\"description\":\"description test\",\"image\":\"uploads\\/1751533566_IMG-20231112-WA0073.jpg\",\"date_publication\":\"2025-07-03 14:36:06\",\"statut\":\"actif\"},{\"id\":\"4\",\"titre\":\"Actuelit\\u00e9 de Betuole\",\"description\":\"detail test\",\"image\":\"uploads\\/1751743172_IMG-20231112-WA0069.jpg\",\"date_publication\":\"2025-07-06 00:49:00\",\"statut\":\"actif\"},{\"id\":\"5\",\"titre\":\"Remise des parchemin et des diplome de nos brillants apprenants\",\"description\":\"demo test\",\"image\":\"uploads\\/1751743499_IMG-20231112-WA0102.jpg\",\"date_publication\":\"2025-07-06 00:54:59\",\"statut\":\"actif\"},{\"id\":\"7\",\"titre\":\"demo sport\",\"description\":\"sport a betuoe tout les lundi\",\"image\":\"uploads\\/1751743840_IMG-20231112-WA0079.jpg\",\"date_publication\":\"2025-07-06 01:00:40\",\"statut\":\"actif\"}],\"contact_visiteurs\":[{\"id\":\"1\",\"nom\":\"ceredine FOGANKENG KENFACK\",\"email\":\"evaricekuete2@gmail.com\",\"objet\":\"dede\",\"message\":\"test\",\"date_envoi\":\"2025-07-06 04:49:24\"},{\"id\":\"2\",\"nom\":\"ceredine FOGANKENG KENFACK\",\"email\":\"evaricekuete2@gmail.com\",\"objet\":\"test\",\"message\":\"test message\",\"date_envoi\":\"2025-07-06 20:56:07\"}]}', NULL, '2025-07-08 17:24:56');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `nom_client` varchar(255) DEFAULT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `statut` enum('en attente','reçu','rejeté') DEFAULT 'en attente',
  `date_commande` timestamp NULL DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `utilisateur_id`, `nom_client`, `produit_id`, `statut`, `date_commande`, `adresse`, `telephone`) VALUES
(1, NULL, 'Evarice Kuete Tezempa', 24, 'en attente', NULL, 'DOUALA', '0679164801'),
(2, NULL, 'Evarice Kuete Tezempa', 14, 'en attente', NULL, 'Douala cameroun\r\nBonaberie babenga', '0679164801'),
(3, NULL, 'evarice kuete', 24, 'en attente', NULL, 'Douala cameroun\r\nBonaberie babenga', '0679164801');

-- --------------------------------------------------------

--
-- Structure de la table `contact_visiteurs`
--

CREATE TABLE `contact_visiteurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `objet` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact_visiteurs`
--

INSERT INTO `contact_visiteurs` (`id`, `nom`, `email`, `objet`, `message`, `date_envoi`) VALUES
(1, 'ceredine FOGANKENG KENFACK', 'evaricekuete2@gmail.com', 'dede', 'test', '2025-07-05 23:19:24');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

CREATE TABLE `formations` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `formations`
--

INSERT INTO `formations` (`id`, `nom`, `description`, `prix`, `quantite`, `image`, `date_ajout`) VALUES
(7, 'Formation en Gommage pro', 'formez vous en gommage professionnel et devenez un productrice de revenue mensuel de plus de 300 000F', 30000.00, 30, 'uploads/1751420232_gommage.jpg', '2025-07-02 01:37:12'),
(8, 'Formation en Esthétique Professionnel', 'Apprennez l\'esthetique en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'esthetique avec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 50000.00, 30, 'uploads/1751440670_esthetiques.jpg', '2025-07-02 07:17:50'),
(9, 'Formation en Manicure Professionnelle', 'Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 45000.00, 35, 'uploads/1751440772_manicure.jpg', '2025-07-02 07:19:32'),
(10, 'Formation en Massage Professionnel', 'Apprennez le Massage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro du Massage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 40000.00, 25, 'uploads/1751440862_MASSAGE.jpg', '2025-07-02 07:21:02'),
(11, 'Formation en onglerie Professionnel', 'Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 55000.00, 35, 'uploads/1751440938_ONGLERIE1.jpg', '2025-07-02 07:22:18'),
(12, 'Formation en Pedicure Professionnel', 'Apprennez la Pedicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de La pedicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 50000.00, 40, 'uploads/1751441196_ONGLERIE5.jpg', '2025-07-02 07:26:36'),
(13, 'Formation en soins de Visage pro', 'Apprennez les soins de visage en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro des soins de visage avec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 75000.00, 60, 'uploads/1751441281_soins.jpg', '2025-07-02 07:28:01'),
(14, 'Formation en Onglerie', 'Grace à nos enseignants chevronné Apprennez L\'onglerie en 2025 et debloquer votre situation financiere en 2025 pour devenir un pro de l\'onglerie avec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 60000.00, 45, 'uploads/1751441413_salle1.jp2.jpg', '2025-07-02 07:30:13'),
(15, 'Formation en MAnicure', 'Apprennez la Manicure en 2025 et debloquer votre situation financiere en 2025 pour devenir un prode Manicureavec une image de marque dans une entreprise de renomme et avec des tarif au beurre', 70000.00, 50, 'uploads/1751495653_ONGLERIE3.jpg', '2025-07-02 22:34:13');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `mode_paiement` varchar(50) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `date_paiement` datetime DEFAULT current_timestamp(),
  `type_paiement` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`id`, `id_utilisateur`, `reference`, `montant`, `mode_paiement`, `statut`, `date_paiement`, `type_paiement`) VALUES
(13, 2, '', 75000.00, 'MTN MONEY', 'validé', '2025-06-29 06:47:59', ''),
(14, 1, '', 30000.00, 'MTN', 'en attente', '2025-07-02 07:48:26', ''),
(15, 1, '', 30000.00, 'MTN', 'en attente', '2025-07-02 07:51:16', ''),
(16, 1, '', 50000.00, 'MTN', 'en attente', '2025-07-03 14:40:31', ''),
(17, 1, '', 50000.00, 'MTN', 'en attente', '2025-07-04 08:42:50', ''),
(18, 1, '', 50000.00, 'MTN', 'en attente', '2025-07-04 08:43:21', ''),
(19, 1, '', 50000.00, 'Orange', 'en attente', '2025-07-04 10:13:57', ''),
(20, 1, '', 15000.00, 'MTN', 'en attente', '2025-07-04 20:57:16', ''),
(21, 1, '', 30000.00, 'Orange', 'en attente', '2025-07-04 21:13:59', ''),
(38, 15, '', 50000.00, 'Orange', 'rejeté', '2025-07-05 10:00:00', ''),
(40, 17, '', 60000.00, 'MTN', 'rejeté', '2025-07-05 10:30:00', ''),
(41, 18, '', 70000.00, 'Paypal', 'rejeté', '2025-07-05 10:45:00', ''),
(42, 19, '', 25000.00, 'Cash', 'validé', '2025-07-05 11:00:00', ''),
(43, 20, '', 80000.00, 'Orange', 'en attente', '2025-07-05 11:15:00', ''),
(44, 21, '', 30000.00, 'VISA', 'validé', '2025-07-05 11:30:00', ''),
(45, 22, '', 65000.00, 'Paypal', 'validé', '2025-07-05 11:45:00', ''),
(46, 23, '', 90000.00, 'MTN', 'rejeté', '2025-07-05 12:00:00', ''),
(47, 24, '', 55000.00, 'MTN', 'validé', '2025-07-05 12:15:00', ''),
(48, 25, '', 35000.00, 'Cash', 'remboursé', '2025-07-05 12:30:00', ''),
(49, 26, '', 40000.00, 'Orange', 'validé', '2025-07-05 12:45:00', ''),
(50, 27, '', 60000.00, 'Paypal', 'validé', '2025-07-05 13:00:00', ''),
(51, 28, '', 50000.00, 'MTN', 'validé', '2025-07-05 13:15:00', ''),
(52, 16, '', 30000.00, 'Espèces', 'validé', '2025-07-06 06:41:37', ''),
(53, 1, '', 30000.00, 'Orange', 'en attente', '2025-07-06 20:50:47', ''),
(54, 1, '', 60000.00, 'Orange', 'en attente', '2025-07-06 22:10:29', ''),
(55, 1, '', 50000.00, 'MTN', 'en attente', '2025-07-07 11:11:08', ''),
(56, 1, '', 45000.00, 'MTN', 'en attente', '2025-07-07 11:13:16', ''),
(57, 1, '', 50000.00, 'MTN', 'en attente', '2025-07-08 05:47:21', ''),
(58, 23, '', 10000.00, 'Espèces', 'validé', '2025-07-08 06:17:53', ''),
(59, 23, '', 20000.00, 'Espèces', 'validé', '2025-07-08 06:22:27', ''),
(60, 23, '', 15000.00, 'Espèces', 'validé', '2025-07-08 06:24:57', ''),
(61, 23, '', 1000.00, 'Espèces', 'validé', '2025-07-08 06:28:50', ''),
(62, 23, '', 200.00, 'Espèces', 'en attente', '2025-07-08 06:33:27', ''),
(63, 14, '', 10000.00, 'MOMO', 'validé', '2025-07-08 06:43:22', ''),
(64, 15, '', 10000.00, 'MTN', 'validé', '2025-07-08 07:03:53', 'Frais d\'inscriptions'),
(65, 32, 'BTT00057', 10000.00, 'MOMO', 'validé', '2025-07-08 08:20:48', 'Frais d\'inscription'),
(66, 48, 'BTT01092', 10000.00, 'MOMO', 'en attente', '2025-07-08 23:19:54', 'Frais d\'inscription');

-- --------------------------------------------------------

--
-- Structure de la table `reclamations`
--

CREATE TABLE `reclamations` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) DEFAULT NULL,
  `type_demande` enum('reclamation','demande_devis','demande_approvisionnement') DEFAULT NULL,
  `nom_produit` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `adresse_livraison` varchar(255) DEFAULT NULL,
  `statut` enum('en attente','valide','rejete') DEFAULT 'en attente',
  `date_demande` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('revendeur','Client','Other','admin','Ras') NOT NULL DEFAULT 'Client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'admin', 'elit1@glzmoi.fr', '$2y$10$E6ztJqbhuS/VVp02UJzRC.VMcIEmALWlqXjXeL/Ps6FYWVfi3Tel2', 'admin', '2025-04-06 18:30:57'),
(3, 'admin1', 'lit@glzmoi.fr', '$2y$10$fsgLHRJdkPxAi.oNtGpGDeVUSqB2Rj2OoefpJrn2HdrbHVX9NFwKW', 'admin', '2025-04-07 11:14:49'),
(7, 'eva', 'eva@gmail.com', '$2y$10$sDhZ7cTFL7BPZl6mEFEzu.6M7X/clRE2YSF6smNVxgr0Ub06RWlDC', 'Client', '2025-04-25 13:37:43'),
(9, 'eva', 'eva2@gmail.com', '$2y$10$KP3lJqBNwlBI.Nz.Yat5zeWDtFkffzd1Q8J9GuwBqbLTQ1Oi1AMpS', 'Client', '2025-06-26 23:13:08'),
(10, 'evarice', 'evar@mail.com', '$2y$10$xd0FXvLNEPzTR0tkkYsqsuzkDKxuzutpwaSrMiRhrS5dCk.oRF9a6', 'admin', '2025-06-26 23:13:59');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `matricule` varchar(30) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `sexe` enum('M','F') NOT NULL,
  `date_naissance` date NOT NULL,
  `cni` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `role` enum('admin','apprenant') DEFAULT 'apprenant',
  `date_inscription` datetime DEFAULT current_timestamp(),
  `filiere` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `scolarite_status` varchar(50) DEFAULT 'non soldé'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `matricule`, `nom`, `prenom`, `sexe`, `date_naissance`, `cni`, `email`, `telephone`, `role`, `date_inscription`, `filiere`, `balance`, `scolarite_status`) VALUES
(1, 'motdepasse', 'superAdmin', 'Admin', 'M', '2025-06-07', 'KIT0144', 'admin@admin.cm', '+237690000002', 'admin', '2025-06-28 07:35:04', NULL, 0.00, 'non soldé'),
(2, 'BT-2025-06-0011-28', 'cerena', 'gomes', 'M', '2025-06-14', 'KIT0146', 'clarisse@etudiant.com', '+237695000002', 'admin', '2025-06-28 07:35:04', NULL, 0.00, 'non soldé'),
(14, 'BT-user0215', 'Adel', 'Cadet', 'M', '2025-05-15', 'CNI0258', 'cadet@gmail.com', '15425658', 'apprenant', '2025-06-29 08:07:57', 'Formation en soins de Visage pro', 0.00, 'non soldé'),
(15, 'BT-fe2525', 'Alexenders', 'Martinelie', 'M', '2025-06-07', 'KITLT1245', 'alex@gmail.com', '658789858', 'apprenant', '2025-06-29 08:10:36', 'Formation en Massage Professionnel', 0.00, 'non soldé'),
(16, 'motdepasse123', 'claude', 'Bernard', 'M', '2025-07-01', 'KIT177', 'claude@gmail.com', '678787878', 'apprenant', '2025-07-02 07:40:56', '', 0.00, 'non soldé'),
(17, 'BT-2025-07-0010-04', 'ARIANE', 'NGUIATAZUING', 'M', '2025-07-01', 'KIT2343', 'tete@gmail.com', '0679164801', 'apprenant', '2025-07-04 21:04:07', '', 0.00, 'non soldé'),
(18, 'BT-2025-07-0011-04', 'betuol', 'cerena', 'F', '2025-07-02', 'LUJI098', 'CERENA@gmail.com', '679184101', 'apprenant', '2025-07-04 21:15:00', '', 0.00, 'non soldé'),
(19, 'BT-2025-07-0012-04', 'DIALLO', 'Mariam', 'F', '2000-04-12', 'CNI001', 'mariam@gmail.com', '670000001', 'apprenant', '2025-07-05 08:30:00', '', 0.00, 'non soldé'),
(20, 'BT-2025-07-0013-04', 'KAMDEM', 'Sylvain', 'M', '1998-12-03', 'CNI002', 'sylvain.kamdem@gmail.com', '670000002', 'apprenant', '2025-07-05 08:35:00', '', 0.00, 'non soldé'),
(21, 'BT-2025-07-0014-04', 'NDOUMBE', 'Patricia', 'F', '2001-02-25', 'CNI003', 'patricia.ndoumbe@gmail.com', '670000003', 'apprenant', '2025-07-05 08:40:00', '', 0.00, 'non soldé'),
(22, 'BT-2025-07-0015-04', 'MBOUA', 'Jean-Pierre', 'M', '1997-07-15', 'CNI004', 'jean.mboua@gmail.com', '670000004', 'apprenant', '2025-07-05 08:45:00', '', 0.00, 'non soldé'),
(23, 'BT-2025-07-0016-04', 'FOTSO Adrian', 'Camille', 'F', '1999-09-10', 'CNI005', 'camille.fotso@gmail.com', '670000005', 'apprenant', '2025-07-05 08:50:00', 'Formation en Gommage pro', 0.00, 'non soldé'),
(24, 'BT-2025-07-0017-04', 'EKAMBI', 'Roger', 'M', '1996-11-22', 'CNI006', 'roger.ekambi@gmail.com', '670000006', 'apprenant', '2025-07-05 08:55:00', '', 0.00, 'non soldé'),
(25, 'BT-2025-07-0018-04', 'MAKON', 'Lucie', 'F', '1995-05-05', 'CNI007', 'lucie.makon@gmail.com', '670000007', 'apprenant', '2025-07-05 09:00:00', '', 0.00, 'non soldé'),
(26, 'BT-2025-07-0019-04', 'NDONGO', 'Michel', 'M', '1998-08-18', 'CNI008', 'michel.ndongo@gmail.com', '670000008', 'apprenant', '2025-07-05 09:05:00', '', 0.00, 'non soldé'),
(27, 'BT-2025-07-0020-04', 'TCHANA', 'Annie', 'F', '2002-01-30', 'CNI009', 'annie.tchana@gmail.com', '670000009', 'apprenant', '2025-07-05 09:10:00', '', 0.00, 'non soldé'),
(28, 'BT-2025-07-0021-04', 'YEMELONG', 'Bruno', 'M', '1994-06-12', 'CNI010', 'bruno.yemelong@gmail.com', '670000010', 'apprenant', '2025-07-05 09:15:00', '', 0.00, 'non soldé'),
(30, 'BT-2025-07-0013-08', 'Abdel axis', 'Camal Rodrigez', 'M', '2025-07-01', 'CNI1512', 'axis@gmail.com', '690000005', 'apprenant', '2025-07-08 07:56:26', '', 0.00, 'non soldé'),
(32, 'BT-2025-07-0014-08', 'Danh evans', 'Sidoine', 'M', '2025-07-01', 'CNI01425', 'evans@gmail.com', '691210114', 'apprenant', '2025-07-08 08:08:35', 'Formation en MAnicure', 0.00, 'non soldé'),
(33, 'ADM001', 'Nana', 'Albert', 'M', '1985-03-12', '110293847', 'albert.nana@betuole.com', '+237670000001', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(34, 'ADM002', 'Ngono', 'Clarisse', 'F', '1990-06-25', '210938475', 'clarisse.ngono@betuole.com', '+237670000002', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(35, 'ADM003', 'Tchami', 'Bruno', 'M', '1982-11-10', '120384756', 'bruno.tchami@betuole.com', '+237670000003', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(36, 'ADM004', 'Kamdem', 'Esther', 'F', '1988-07-08', '220394857', 'esther.kamdem@betuole.com', '+237670000004', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(37, 'ADM005', 'Ngassa', 'Michel', 'M', '1979-01-17', '130495867', 'michel.ngassa@betuole.com', '+237670000005', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(38, 'ADM006', 'Ebang', 'Juliette', 'F', '1992-02-23', '140596978', 'juliette.ebang@betuole.com', '+237670000006', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(39, 'ADM007', 'Fonkou', 'Dieudonné', 'M', '1987-05-30', '150697089', 'dieudonne.fonkou@betuole.com', '+237670000007', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(40, 'ADM008', 'Ndongo', 'Sylvie', 'F', '1991-04-15', '160798190', 'sylvie.ndongo@betuole.com', '+237670000008', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(41, 'ADM009', 'Mbarga', 'Jacques', 'M', '1983-09-18', '170899201', 'jacques.mbarga@betuole.com', '+237670000009', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(42, 'ADM010', 'Koumba', 'Aline', 'F', '1989-12-22', '180990312', 'aline.koumba@betuole.com', '+237670000010', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(43, 'ADM011', 'Bikoi', 'Franklin', 'M', '1986-08-05', '190091423', 'franklin.bikoi@betuole.com', '+237670000011', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(44, 'ADM012', 'Atangana', 'Rose', 'F', '1993-03-03', '200192534', 'rose.atangana@betuole.com', '+237670000012', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(45, 'ADM013', 'Simo', 'Pascal', 'M', '1980-10-14', '210293645', 'pascal.simo@betuole.com', '+237670000013', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(46, 'ADM014', 'Essomba', 'Christelle', 'F', '1994-01-09', '220394756', 'christelle.essomba@betuole.com', '+237670000014', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(47, 'ADM015', 'Manga', 'Guy', 'M', '1984-06-28', '230495867', 'guy.manga@betuole.com', '+237670000015', 'admin', '2025-07-08 08:32:07', NULL, 0.00, 'non soldé'),
(48, 'BT-2025-07-0015-08', 'Tesla', 'franck Lucky', 'M', '2025-07-01', 'CNI4564', 'lucky@gmail.com', '689487587', 'apprenant', '2025-07-08 23:18:21', 'Formation en soins de Visage pro', 0.00, 'non soldé');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actualites`
--
ALTER TABLE `actualites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `annees`
--
ALTER TABLE `annees`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `contact_visiteurs`
--
ALTER TABLE `contact_visiteurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `formations`
--
ALTER TABLE `formations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_utilisateur_paiement` (`id_utilisateur`);

--
-- Index pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD UNIQUE KEY `cni` (`cni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actualites`
--
ALTER TABLE `actualites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `annees`
--
ALTER TABLE `annees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `archives`
--
ALTER TABLE `archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `contact_visiteurs`
--
ALTER TABLE `contact_visiteurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `formations`
--
ALTER TABLE `formations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `reclamations`
--
ALTER TABLE `reclamations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `fk_utilisateur_paiement` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD CONSTRAINT `reclamations_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
