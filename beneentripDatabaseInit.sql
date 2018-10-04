-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2018 at 04:03 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beneentrip`
--

-- --------------------------------------------------------

--
-- Table structure for table `activite`
--

CREATE TABLE `activite` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `image_principale_id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lieuDestination` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `nbParticipants` int(11) NOT NULL,
  `prixIndividu` double NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL,
  `nbVues` int(11) NOT NULL DEFAULT '0',
  `langueParlee` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `devise` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'EUR',
  `duree` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`, `dateCreation`, `dateModification`) VALUES
(1, 'TOURISME', '2018-06-21 12:00:00', NULL),
(2, 'VOYAGE', '2018-06-21 12:00:00', NULL),
(3, 'CULTURE', '2018-06-21 12:00:00', NULL),
(4, 'SPORT', '2018-06-21 12:00:00', NULL),
(5, 'SORTIE & NOURRITURE', '2018-06-21 12:00:00', NULL),
(6, 'PLEIN AIR', '2018-06-21 12:00:00', NULL),
(7, 'SENSATIONS FORTES', '2018-06-21 12:00:00', NULL),
(8, 'EN FAMILLE', '2018-06-21 12:00:00', NULL),
(9, 'TOUS', '2018-06-21 12:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

CREATE TABLE `discussion` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `activite_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Discussion',
  `titre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discussion_user`
--

CREATE TABLE `discussion_user` (
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `activite_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `langue`
--

CREATE TABLE `langue` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `langue`
--

INSERT INTO `langue` (`id`, `nom`, `code`, `dateCreation`, `dateModification`) VALUES
(1, 'Afar', 'aa', '2018-06-22 11:00:00', NULL),
(2, 'Abkhazian', 'ab', '2018-06-22 11:00:00', NULL),
(3, 'Avestan', 'ae', '2018-06-22 11:00:00', NULL),
(4, 'Afrikaans', 'af', '2018-06-22 11:00:00', NULL),
(5, 'Akan', 'ak', '2018-06-22 11:00:00', NULL),
(6, 'Amharic', 'am', '2018-06-22 11:00:00', NULL),
(7, 'Aragonese', 'an', '2018-06-22 11:00:00', NULL),
(8, 'Arabic', 'ar', '2018-06-22 11:00:00', NULL),
(9, 'Assamese', 'as', '2018-06-22 11:00:00', NULL),
(10, 'Avaric', 'av', '2018-06-22 11:00:00', NULL),
(11, 'Aymara', 'ay', '2018-06-22 11:00:00', NULL),
(12, 'Azerbaijani', 'az', '2018-06-22 11:00:00', NULL),
(13, 'Bashkir', 'ba', '2018-06-22 11:00:00', NULL),
(14, 'Belarusian', 'be', '2018-06-22 11:00:00', NULL),
(15, 'Bulgarian', 'bg', '2018-06-22 11:00:00', NULL),
(16, 'Bihari', 'bh', '2018-06-22 11:00:00', NULL),
(17, 'Bislama', 'bi', '2018-06-22 11:00:00', NULL),
(18, 'Bambara', 'bm', '2018-06-22 11:00:00', NULL),
(19, 'Bengali', 'bn', '2018-06-22 11:00:00', NULL),
(20, 'Tibetan', 'bo', '2018-06-22 11:00:00', NULL),
(21, 'Breton', 'br', '2018-06-22 11:00:00', NULL),
(22, 'Bosnian', 'bs', '2018-06-22 11:00:00', NULL),
(23, 'Catalan', 'ca', '2018-06-22 11:00:00', NULL),
(24, 'Chechen', 'ce', '2018-06-22 11:00:00', NULL),
(25, 'Chamorro', 'ch', '2018-06-22 11:00:00', NULL),
(26, 'Corsican', 'co', '2018-06-22 11:00:00', NULL),
(27, 'Cree', 'cr', '2018-06-22 11:00:00', NULL),
(28, 'Czech', 'cs', '2018-06-22 11:00:00', NULL),
(29, 'Old Church Slavonic', 'cu', '2018-06-22 11:00:00', NULL),
(30, 'Chuvash', 'cv', '2018-06-22 11:00:00', NULL),
(31, 'Welsh', 'cy', '2018-06-22 11:00:00', NULL),
(32, 'Danish', 'da', '2018-06-22 11:00:00', NULL),
(33, 'German', 'de', '2018-06-22 11:00:00', NULL),
(34, 'Divehi', 'dv', '2018-06-22 11:00:00', NULL),
(35, 'Dzongkha', 'dz', '2018-06-22 11:00:00', NULL),
(36, 'Ewe', 'ee', '2018-06-22 11:00:00', NULL),
(37, 'Greek', 'el', '2018-06-22 11:00:00', NULL),
(38, 'English', 'en', '2018-06-22 11:00:00', NULL),
(39, 'Esperanto', 'eo', '2018-06-22 11:00:00', NULL),
(40, 'Spanish', 'es', '2018-06-22 11:00:00', NULL),
(41, 'Estonian', 'et', '2018-06-22 11:00:00', NULL),
(42, 'Basque', 'eu', '2018-06-22 11:00:00', NULL),
(43, 'Persian', 'fa', '2018-06-22 11:00:00', NULL),
(44, 'Fulah', 'ff', '2018-06-22 11:00:00', NULL),
(45, 'Finnish', 'fi', '2018-06-22 11:00:00', NULL),
(46, 'Fijian', 'fj', '2018-06-22 11:00:00', NULL),
(47, 'Faroese', 'fo', '2018-06-22 11:00:00', NULL),
(48, 'French', 'fr', '2018-06-22 11:00:00', NULL),
(49, 'Western Frisian', 'fy', '2018-06-22 11:00:00', NULL),
(50, 'Irish', 'ga', '2018-06-22 11:00:00', NULL),
(51, 'Scottish Gaelic', 'gd', '2018-06-22 11:00:00', NULL),
(52, 'Galician', 'gl', '2018-06-22 11:00:00', NULL),
(53, 'Guarani', 'gn', '2018-06-22 11:00:00', NULL),
(54, 'Gujarati', 'gu', '2018-06-22 11:00:00', NULL),
(55, 'Manx', 'gv', '2018-06-22 11:00:00', NULL),
(56, 'Hausa', 'ha', '2018-06-22 11:00:00', NULL),
(57, 'Hebrew', 'he', '2018-06-22 11:00:00', NULL),
(58, 'Hindi', 'hi', '2018-06-22 11:00:00', NULL),
(59, 'Hiri Motu', 'ho', '2018-06-22 11:00:00', NULL),
(60, 'Croatian', 'hr', '2018-06-22 11:00:00', NULL),
(61, 'Haitian', 'ht', '2018-06-22 11:00:00', NULL),
(62, 'Hungarian', 'hu', '2018-06-22 11:00:00', NULL),
(63, 'Armenian', 'hy', '2018-06-22 11:00:00', NULL),
(64, 'Herero', 'hz', '2018-06-22 11:00:00', NULL),
(65, 'Interlingua', 'ia', '2018-06-22 11:00:00', NULL),
(66, 'Indonesian', 'id', '2018-06-22 11:00:00', NULL),
(67, 'Interlingue', 'ie', '2018-06-22 11:00:00', NULL),
(68, 'Igbo', 'ig', '2018-06-22 11:00:00', NULL),
(69, 'Sichuan Yi', 'ii', '2018-06-22 11:00:00', NULL),
(70, 'Inupiaq', 'ik', '2018-06-22 11:00:00', NULL),
(71, 'Ido', 'io', '2018-06-22 11:00:00', NULL),
(72, 'Icelandic', 'is', '2018-06-22 11:00:00', NULL),
(73, 'Italian', 'it', '2018-06-22 11:00:00', NULL),
(74, 'Inuktitut', 'iu', '2018-06-22 11:00:00', NULL),
(75, 'Japanese', 'ja', '2018-06-22 11:00:00', NULL),
(76, 'Javanese', 'jv', '2018-06-22 11:00:00', NULL),
(77, 'Georgian', 'ka', '2018-06-22 11:00:00', NULL),
(78, 'Kongo', 'kg', '2018-06-22 11:00:00', NULL),
(79, 'Kikuyu', 'ki', '2018-06-22 11:00:00', NULL),
(80, 'Kwanyama', 'kj', '2018-06-22 11:00:00', NULL),
(81, 'Kazakh', 'kk', '2018-06-22 11:00:00', NULL),
(82, 'Kalaallisut', 'kl', '2018-06-22 11:00:00', NULL),
(83, 'Khmer', 'km', '2018-06-22 11:00:00', NULL),
(84, 'Kannada', 'kn', '2018-06-22 11:00:00', NULL),
(85, 'Korean', 'ko', '2018-06-22 11:00:00', NULL),
(86, 'Kanuri', 'kr', '2018-06-22 11:00:00', NULL),
(87, 'Kashmiri', 'ks', '2018-06-22 11:00:00', NULL),
(88, 'Kurdish', 'ku', '2018-06-22 11:00:00', NULL),
(89, 'Komi', 'kv', '2018-06-22 11:00:00', NULL),
(90, 'Cornish', 'kw', '2018-06-22 11:00:00', NULL),
(91, 'Kirghiz', 'ky', '2018-06-22 11:00:00', NULL),
(92, 'Latin', 'la', '2018-06-22 11:00:00', NULL),
(93, 'Luxembourgish', 'lb', '2018-06-22 11:00:00', NULL),
(94, 'Ganda', 'lg', '2018-06-22 11:00:00', NULL),
(95, 'Limburgish', 'li', '2018-06-22 11:00:00', NULL),
(96, 'Lingala', 'ln', '2018-06-22 11:00:00', NULL),
(97, 'Lao', 'lo', '2018-06-22 11:00:00', NULL),
(98, 'Lithuanian', 'lt', '2018-06-22 11:00:00', NULL),
(99, 'Luba-Katanga', 'lu', '2018-06-22 11:00:00', NULL),
(100, 'Latvian', 'lv', '2018-06-22 11:00:00', NULL),
(101, 'Malagasy', 'mg', '2018-06-22 11:00:00', NULL),
(102, 'Marshallese', 'mh', '2018-06-22 11:00:00', NULL),
(103, 'Māori', 'mi', '2018-06-22 11:00:00', NULL),
(104, 'Macedonian', 'mk', '2018-06-22 11:00:00', NULL),
(105, 'Malayalam', 'ml', '2018-06-22 11:00:00', NULL),
(106, 'Mongolian', 'mn', '2018-06-22 11:00:00', NULL),
(107, 'Moldavian', 'mo', '2018-06-22 11:00:00', NULL),
(108, 'Marathi', 'mr', '2018-06-22 11:00:00', NULL),
(109, 'Malay', 'ms', '2018-06-22 11:00:00', NULL),
(110, 'Maltese', 'mt', '2018-06-22 11:00:00', NULL),
(111, 'Burmese', 'my', '2018-06-22 11:00:00', NULL),
(112, 'Nauru', 'na', '2018-06-22 11:00:00', NULL),
(113, 'Norwegian Bokmål', 'nb', '2018-06-22 11:00:00', NULL),
(114, 'North Ndebele', 'nd', '2018-06-22 11:00:00', NULL),
(115, 'Nepali', 'ne', '2018-06-22 11:00:00', NULL),
(116, 'Ndonga', 'ng', '2018-06-22 11:00:00', NULL),
(117, 'Dutch', 'nl', '2018-06-22 11:00:00', NULL),
(118, 'Norwegian Nynorsk', 'nn', '2018-06-22 11:00:00', NULL),
(119, 'Norwegian', 'no', '2018-06-22 11:00:00', NULL),
(120, 'South Ndebele', 'nr', '2018-06-22 11:00:00', NULL),
(121, 'Navajo', 'nv', '2018-06-22 11:00:00', NULL),
(122, 'Chichewa', 'ny', '2018-06-22 11:00:00', NULL),
(123, 'Occitan', 'oc', '2018-06-22 11:00:00', NULL),
(124, 'Ojibwa', 'oj', '2018-06-22 11:00:00', NULL),
(125, 'Oromo', 'om', '2018-06-22 11:00:00', NULL),
(126, 'Oriya', 'or', '2018-06-22 11:00:00', NULL),
(127, 'Ossetian', 'os', '2018-06-22 11:00:00', NULL),
(128, 'Panjabi', 'pa', '2018-06-22 11:00:00', NULL),
(129, 'Pāli', 'pi', '2018-06-22 11:00:00', NULL),
(130, 'Polish', 'pl', '2018-06-22 11:00:00', NULL),
(131, 'Pashto', 'ps', '2018-06-22 11:00:00', NULL),
(132, 'Portuguese', 'pt', '2018-06-22 11:00:00', NULL),
(133, 'Quechua', 'qu', '2018-06-22 11:00:00', NULL),
(134, 'Reunionese', 'rc', '2018-06-22 11:00:00', NULL),
(135, 'Romansh', 'rm', '2018-06-22 11:00:00', NULL),
(136, 'Kirundi', 'rn', '2018-06-22 11:00:00', NULL),
(137, 'Romanian', 'ro', '2018-06-22 11:00:00', NULL),
(138, 'Russian', 'ru', '2018-06-22 11:00:00', NULL),
(139, 'Kinyarwanda', 'rw', '2018-06-22 11:00:00', NULL),
(140, 'Sanskrit', 'sa', '2018-06-22 11:00:00', NULL),
(141, 'Sardinian', 'sc', '2018-06-22 11:00:00', NULL),
(142, 'Sindhi', 'sd', '2018-06-22 11:00:00', NULL),
(143, 'Northern Sami', 'se', '2018-06-22 11:00:00', NULL),
(144, 'Sango', 'sg', '2018-06-22 11:00:00', NULL),
(145, 'Serbo-Croatian', 'sh', '2018-06-22 11:00:00', NULL),
(146, 'Sinhalese', 'si', '2018-06-22 11:00:00', NULL),
(147, 'Slovak', 'sk', '2018-06-22 11:00:00', NULL),
(148, 'Slovenian', 'sl', '2018-06-22 11:00:00', NULL),
(149, 'Samoan', 'sm', '2018-06-22 11:00:00', NULL),
(150, 'Shona', 'sn', '2018-06-22 11:00:00', NULL),
(151, 'Somali', 'so', '2018-06-22 11:00:00', NULL),
(152, 'Albanian', 'sq', '2018-06-22 11:00:00', NULL),
(153, 'Serbian', 'sr', '2018-06-22 11:00:00', NULL),
(154, 'Swati', 'ss', '2018-06-22 11:00:00', NULL),
(155, 'Sotho', 'st', '2018-06-22 11:00:00', NULL),
(156, 'Sundanese', 'su', '2018-06-22 11:00:00', NULL),
(157, 'Swedish', 'sv', '2018-06-22 11:00:00', NULL),
(158, 'Swahili', 'sw', '2018-06-22 11:00:00', NULL),
(159, 'Tamil', 'ta', '2018-06-22 11:00:00', NULL),
(160, 'Telugu', 'te', '2018-06-22 11:00:00', NULL),
(161, 'Tajik', 'tg', '2018-06-22 11:00:00', NULL),
(162, 'Thai', 'th', '2018-06-22 11:00:00', NULL),
(163, 'Tigrinya', 'ti', '2018-06-22 11:00:00', NULL),
(164, 'Turkmen', 'tk', '2018-06-22 11:00:00', NULL),
(165, 'Tagalog', 'tl', '2018-06-22 11:00:00', NULL),
(166, 'Tswana', 'tn', '2018-06-22 11:00:00', NULL),
(167, 'Tonga', 'to', '2018-06-22 11:00:00', NULL),
(168, 'Turkish', 'tr', '2018-06-22 11:00:00', NULL),
(169, 'Tsonga', 'ts', '2018-06-22 11:00:00', NULL),
(170, 'Tatar', 'tt', '2018-06-22 11:00:00', NULL),
(171, 'Twi', 'tw', '2018-06-22 11:00:00', NULL),
(172, 'Tahitian', 'ty', '2018-06-22 11:00:00', NULL),
(173, 'Uighur', 'ug', '2018-06-22 11:00:00', NULL),
(174, 'Ukrainian', 'uk', '2018-06-22 11:00:00', NULL),
(175, 'Urdu', 'ur', '2018-06-22 11:00:00', NULL),
(176, 'Uzbek', 'uz', '2018-06-22 11:00:00', NULL),
(177, 'Venda', 've', '2018-06-22 11:00:00', NULL),
(178, 'Viêt Namese', 'vi', '2018-06-22 11:00:00', NULL),
(179, 'Volapük', 'vo', '2018-06-22 11:00:00', NULL),
(180, 'Walloon', 'wa', '2018-06-22 11:00:00', NULL),
(181, 'Wolof', 'wo', '2018-06-22 11:00:00', NULL),
(182, 'Xhosa', 'xh', '2018-06-22 11:00:00', NULL),
(183, 'Yiddish', 'yi', '2018-06-22 11:00:00', NULL),
(184, 'Yoruba', 'yo', '2018-06-22 11:00:00', NULL),
(185, 'Zhuang', 'za', '2018-06-22 11:00:00', NULL),
(186, 'Chinese', 'zh', '2018-06-22 11:00:00', NULL),
(187, 'Zulu', 'zu', '2018-06-22 11:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `titrePage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `titrePage`, `contenu`, `dateCreation`, `dateModification`) VALUES
(1, 'DEMO', '<h1><strong>Page DEMO BENEEN TRIP</strong></h1>', '2018-06-16 19:22:02', NULL),
(2, 'AproposDeNous', '<h1 style="text-align: center;"><u><strong><span style="font-size:36px">Qui sommes-nous ?</span></strong></u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<blockquote>\r\n<p><br />\r\n<span style="color:#000000"><kbd><strong><span style="font-size:20px"><em>Beneen Trip : un service n&eacute; de la culture du num&eacute;rique</em></span></strong></kbd></span></p>\r\n</blockquote>\r\n\r\n<p style="text-align: justify;"><br />\r\n<var><samp><code>Beneen Trip est n&eacute; &agrave; Paris. Il s&#39;agit d&#39;un site qui met en relation directe les touristes et les populations locales. L&#39;id&eacute;e derri&egrave;re cette application? Proposer aux touristes des activit&eacute;s dispens&eacute;es par des locaux &agrave; des prix abordables. Pour plus de simplicit&eacute;, la r&eacute;servation s&#39;op&egrave;re directement depuis notre plateforme.</code></samp></var></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong><span style="font-size:20px">En quelques mots :</span></strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ul>\r\n	<li style="text-align: justify;"><em><code>Nous sommes une PME cr&eacute;&eacute;e en 2018 par trois collaborateurs : Xavier Sole, Christian Ngassa et Babacar Diallo.</code></em></li>\r\n	<li style="text-align: justify;"><em><code>Nous sommes n&eacute;s en France.</code></em></li>\r\n	<li style="text-align: justify;"><em><code>Nous sommes une jeune entreprise et voulons r&eacute;volutionner l&#39;univers du tourisme.</code></em></li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong><span style="font-size:20px">Notre objectif :</span></strong></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ol>\r\n	<li style="text-align: justify;"><em><code>Associer, au sein d&rsquo;un m&ecirc;me espace des individus issus de cultures diff&eacute;rentes.</code></em></li>\r\n	<li style="text-align: justify;"><em><code>Favoriser les &eacute;changes dans un souci de transparence en incitant le dialogue entre les individus.</code></em></li>\r\n	<li style="text-align: justify;"><em><code>S&rsquo;ouvrir &agrave; l&rsquo;international en s&rsquo;int&eacute;ressant aux diff&eacute;rentes cultures et activit&eacute;s sur tous les continents, constituent l&#39;un des principes fondamentaux de Beneen Trip. &Agrave; cette fin, nous nous appuyons sur un r&eacute;seau de correspondants dans le monde entier.</code></em></li>\r\n</ol>', '2018-06-17 13:56:33', NULL),
(3, 'commentCaMarche', '<p style="text-align: center;"><u><strong><span style="font-size:36px">Comment &ccedil;a marche?</span></strong></u></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><big>Deux cas de figure s&#39;offrent &agrave; vous : Vous souhaitez d&eacute;couvrir une activit&eacute; : Vous &ecirc;tes donc un <em><strong>&quot;Trotter&quot;</strong></em>. Vous d&eacute;sirez faire partager une activit&eacute; : Vous &ecirc;tes un <strong><em>&quot;Eclaireur&quot;</em></strong></big></p>\r\n\r\n<h1 style="text-align: center;"><span style="font-family:tahoma,geneva,sans-serif"><strong><span style="font-size:26px"><var><cite><em>Vous &ecirc;tes Trotter</em></cite></var></span></strong></span></h1>\r\n\r\n<blockquote>\r\n<ul>\r\n	<li><big>Recherchez votre activit&eacute;</big></li>\r\n	<li><big>Renseignez vos lieux et la date de votre s&eacute;jour. Choisissez les activit&eacute;s correspondant &agrave; vos aspirations. Si vous voulez des pr&eacute;cisions sur une activit&eacute;, vous pouvez envoyer un message &agrave; l&#39;Eclaireur.</big></li>\r\n	<li><big>R&eacute;servez par Carte Bancaire</big></li>\r\n	<li><big>Vous r&eacute;servez une activit&eacute; avec une Carte Bancaire par paiement s&eacute;curis&eacute; et recevez un Code de R&eacute;servation. Votre guide est pr&eacute;venu imm&eacute;diatement de votre r&eacute;servation par mail et SMS. Ensuite, appelez ou non l&#39;Eclaireur pour r&eacute;gler les derniers d&eacute;tails de l&rsquo;activit&eacute; de vive voix.Toutes les transactions financi&egrave;res se font en ligne. Lorsque vous r&eacute;alisez votre activit&eacute;, Beneen Trip pr&eacute;l&egrave;ve directement le prix de la prestation sur votre carte. Vous ne devez &agrave; aucun moment payer en esp&egrave;ces, laisser un pourboire ou marchander le prix. Le service n&#39;en est que plus agr&eacute;able puisque l&#39;absence de relation d&#39;argent g&eacute;n&egrave;re une plus grande confiance entre le local et le touriste.</big></li>\r\n	<li><big>C&rsquo;est parti : amusez-vous</big></li>\r\n	<li><big>Apr&egrave;s vous &ecirc;tes rencontr&eacute;s dans un lieu de rendez-vous d&eacute;termin&eacute; avec le guide, donnez-lui le code de r&eacute;servation. Une fois l&rsquo;activit&eacute; termin&eacute;e, il pourra b&eacute;n&eacute;ficier de l&rsquo;apport convenu.</big><br />\r\n	&nbsp;</li>\r\n</ul>\r\n</blockquote>\r\n\r\n<p style="text-align: center;"><strong><span style="font-family:tahoma,geneva,sans-serif"><em><span style="font-size:26px">Vous &ecirc;tes Eclaireur</span></em></span></strong></p>\r\n\r\n<blockquote>\r\n<ul>\r\n	<li><big>Publiez vos activit&eacute;s</big></li>\r\n	<li><big>Indiquez la date, l&rsquo;horaire et le lieu de votre activit&eacute;. Egalement, proposez un r&eacute;sum&eacute; de votre activit&eacute; et le prix par individu. Choisissez entre Acceptation Manuelle et Acceptation Automatique : en Acceptation Manuelle vous confirmez chaque trotter vous-m&ecirc;me, en Acceptation Automatique, la confirmation est imm&eacute;diate et vous n&rsquo;avez rien &agrave; faire.</big></li>\r\n	<li><big>Les Trotters r&eacute;servent</big></li>\r\n	<li><big>Vos trotters r&eacute;servent et paient en ligne sur Beneentrip et vous &ecirc;tes automatiquement pr&eacute;venu(e) par e-mail et SMS &agrave; chaque nouvelle r&eacute;servation. Ensuite, &eacute;changez avec vos touristes par t&eacute;l&eacute;phone pour r&eacute;gler les derniers d&eacute;tails du voyage.</big></li>\r\n	<li><big>Let&rsquo;s go</big></li>\r\n	<li><big>Rendez-vous au lieu de d&eacute;part convenu, bien &agrave; l&rsquo;heure! Demandez le Code de R&eacute;servation de chaque trotter &agrave; la fin de chaque activit&eacute; : il vous permettra de recevoir l&rsquo;argent plus rapidement apr&egrave;s vos activit&eacute;s.</big></li>\r\n	<li><big>Recevez votre somme due</big></li>\r\n	<li><big>Entrez sur Beneentrip le(s) Code(s) de R&eacute;servationre&ccedil;u(s), demandez votre virement et voil&agrave; : vous allez rapidement recevoir votre argent par virement bancaire.</big></li>\r\n</ul>\r\n</blockquote>', '2018-06-17 14:05:05', NULL),
(4, 'conseilsPreventions', '<p style="text-align: center;"><u><strong><span style="font-size:36px">Conseils et pr&eacute;ventions</span></strong></u></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><big>Pour une activit&eacute; en toute s&eacute;curit&eacute;, nous vous invitons &agrave; suivre nos conseils pr&eacute;vention !</big></p>\r\n\r\n<p><big>Quelques mesures &agrave; prendre pour pr&eacute;parer votre activit&eacute;.</big></p>\r\n\r\n<p style="text-align: center;"><span style="font-size:20px"><strong>Les papiers :</strong></span><br />\r\n<big>(en fonction de l&#39;activit&eacute; r&eacute;alis&eacute;e, ces documents doivent toujours &ecirc;tre &agrave; votre disposition)</big></p>\r\n\r\n<ul>\r\n	<li><big>Carte d&#39;identit&eacute; ou passeport non expir&eacute;s.</big></li>\r\n	<li><big>V&eacute;rification du v&eacute;hicule, Contr&ocirc;le technique &amp; Assurance &agrave; jour.</big></li>\r\n	<li><big>Au moins 2 exemplaires de &laquo; constat amiable &raquo;. </big>&nbsp;</li>\r\n</ul>\r\n\r\n<p style="text-align: center;"><strong><span style="font-size:20px">Sant&eacute; :</span></strong></p>\r\n\r\n<ul>\r\n	<li><big>Pensez &agrave; bien pr&eacute;parer votre voyage. Renseignez-vous aupr&egrave;s des autorit&eacute;s comp&eacute;tentes pour disposer de conseils et d&#39;informations sur les vaccins &agrave; r&eacute;aliser.</big></li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style="text-align: center;"><span style="font-size:20px"><strong>Alcool et stup&eacute;fiants :</strong></span></p>\r\n\r\n<p><br />\r\n<big>La consommation d&#39;alcool n&#39;est pas recommand&eacute;e. La consommation de stup&eacute;fiants est prohib&eacute;e.</big></p>', '2018-06-17 14:15:00', NULL),
(5, 'confianceSerenite', '<p style="text-align: center;"><u><span style="font-size:36px"><strong>Confiance et s&eacute;r&eacute;nit&eacute;</strong></span></u></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><strong><span style="font-size:20px">Transparence des informations</span></strong></big></p>\r\n\r\n<p><br />\r\n<big>Beneen Trip vous garantit une transparence totale des coordonn&eacute;es de ses utilisateurs membres (e-mail, t&eacute;l&eacute;phone..) et vous donne la possibilit&eacute; de voir leurs profils.</big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><strong><span style="font-size:20px">Avis des trotters</span></strong></big></p>\r\n\r\n<p><br />\r\n<big>Gr&acirc;ce &agrave; une notation syst&eacute;matique de nos &eacute;claireurs par les pr&eacute;c&eacute;dents trotters, partez l&rsquo;esprit tranquille avec l&rsquo;assurance d&rsquo;&ecirc;tre entre de &laquo; bonne main &raquo;.</big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><strong><span style="font-size:20px">Amusez-vous avec qui vous voudrez ...</span></strong></big></p>\r\n\r\n<p><br />\r\n<big>&nbsp;Choisissez des membres chevronn&eacute;s gr&acirc;ce aux niveaux d&#39;exp&eacute;rience.</big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><span style="font-size:20px"><strong>Prenez contact avant le voyage</strong></span></big></p>\r\n\r\n<p><br />\r\n<big>Utilisez notre messagerie s&eacute;curis&eacute;e avant l&#39;activit&eacute; pour vous mettre d&#39;accord sur le lieu de rendez-vous ou r&eacute;gler d&#39;autres d&eacute;tails.</big></p>', '2018-06-17 14:18:49', NULL),
(6, 'contact', '<p style="text-align: center;"><big><strong><u><span style="font-size:36px">Contactez-nous</span></u></strong></big></p>\r\n\r\n<p><br />\r\n<big>Vous d&eacute;sirez nous contacter ? Envoyez-nous un mail &agrave; l&#39;adresse suivante : <strong>contact@beneentrip.com</strong></big></p>', '2018-06-17 14:20:46', NULL),
(7, 'sondage', '<p style="text-align: center;"><u><strong><big><span style="font-size:36px">Sondage</span></big></strong></u></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<em><strong><big>Avez-vous &eacute;t&eacute; satisfait de votre activit&eacute; ?</big></strong></em></p>\r\n\r\n<ul>\r\n	<li><span style="font-size:14px"><em>&nbsp;<big>Excellent</big></em></span></li>\r\n	<li><span style="font-size:14px"><em><big>&nbsp;Bien</big></em></span></li>\r\n	<li><span style="font-size:14px"><em><big>&nbsp;Assez bien</big></em></span></li>\r\n	<li><big><span style="font-size:14px"><em><big>&nbsp;D&eacute;testable</big></em></span></big></li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style="text-align: center;"><strong><em><big>Est-il facile de naviguer sur le site ?</big></em></strong></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><strong><em>Quels &eacute;l&eacute;ments vous semblent inutiles ?</em></strong></big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><em><strong>Quelles modifications souhaitez-vous apporter ?</strong></em></big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><em><strong>Y&rsquo;a-t-il des fonctionnalit&eacute;s manquantes ?</strong></em></big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><em><strong>Recommanderiez-vous Beneen Trip &agrave; un proche ?</strong></em></big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><em><strong>Pensez revenir sur Beneen Tri prochainement ?</strong></em></big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><em><strong>Les &eacute;claireurs vous inspirent-ils confiance ?</strong></em></big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><em><strong>Quelles notes donneriez-vous &agrave; Beneen Trip ?</strong></em></big></p>', '2018-06-17 14:27:58', NULL),
(8, 'nousRecrutons', '<p style="text-align: center;"><big><u><strong><span style="font-size:36px">Nous recrutons !</span></strong></u></big></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><big>Vous recherchez un job chez Beneen Trip ? Les candidats doivent fournir un CV en anglais (format PDF) et r&eacute;pondre &agrave; quelques questions.</big></p>\r\n\r\n<p><big>Si votre CV r&eacute;pond &agrave; nos attentes, vous serez contact&eacute;e par l&#39;&eacute;quipe de Beneen Trip.</big></p>\r\n\r\n<p><br />\r\n<big>En int&eacute;grant l&rsquo;&eacute;quipe Beneen Trip :<br />\r\nVous participerez &agrave; l&#39;&eacute;laboration de nouveaux proc&eacute;d&eacute;s sociaux et culturelles plus intelligents et plus durables,<br />\r\nvous aurez le plaisir de travailler dans un environnement en constante &eacute;volution,<br />\r\nvous rejoindrez une structure qui se d&eacute;veloppe dans le monde entier, multilingue, mobile et forte d&rsquo;une vraie culture et vision internationale.</big></p>', '2018-06-17 14:31:41', NULL),
(9, 'viePriveeCookies', '<p style="text-align: center;"><u><strong><span style="font-size:36px"><big>Vie priv&eacute;e / Cookies</big></span></strong></u></p>\r\n\r\n<p style="text-align: center;"><br />\r\n&nbsp;</p>\r\n\r\n<p style="text-align: center;"><big><em><span style="font-size:20px"><strong>Qu&rsquo;est-ce que c&rsquo;est ?</strong></span></em></big></p>\r\n\r\n<p><br />\r\n<big>Les cookies de session sont utilis&eacute;s pour stocker des informations sur les activit&eacute;s de l&#39;utilisateur sur les pages afin que ce dernier puisse facilement reprendre la ou il s&#39;&eacute;tait arr&ecirc;t&eacute;. Les cookies agissent comme un &quot;marque-page&quot; sur le site.</big></p>\r\n\r\n<p style="text-align: center;"><br />\r\n<big><span style="font-size:20px"><strong><em>Respect de la s&eacute;curit&eacute; et vie priv&eacute;e</em></strong></span></big></p>\r\n\r\n<p><br />\r\n<big>Les cookies ne sont PAS des virus. Les cookies utilisent un format de texte brut. Il ne s&#39;agit pas de morceaux de code compil&eacute;s, donc ils ne peuvent pas &ecirc;tre ex&eacute;cut&eacute;s ou s&#39;ex&eacute;cuter automatiquement. Ils sortent de la d&eacute;finition standard des virus. Les cookies pr&eacute;sents sur notre site ne sont pas l&agrave; pour vous nuire mais uniquement en tant que suggestions. Aucune donn&eacute;e personnelle n&rsquo;est stock&eacute;e sur le site et vous pouvez choisir si vous souhaitez avoir les cookies sur votre appareil.</big></p>', '2018-06-17 14:45:15', NULL),
(10, 'cgu', '<p style="text-align: center;"><span style="font-size:36px"><u><strong>Conditions g&eacute;n&eacute;rales d&rsquo;utilisation de Beneen Trip</strong></u></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Pr&eacute;ambule&nbsp;:</h1>\r\n\r\n<p>La soci&eacute;t&eacute; Beneen Trip, est une soci&eacute;t&eacute; sp&eacute;cialis&eacute;e dans la mise en relation, via une interface num&eacute;rique, entre les touristes et les populations locales. Dans ce cadre elle permet &agrave; des locaux, d&eacute;nomm&eacute;s &laquo;&nbsp;guides&nbsp;&raquo; de proposer diff&eacute;rents services de nature touristique &agrave; des visiteurs.</p>\r\n\r\n<p>Les lignes qui suivent proc&egrave;dent &agrave; la d&eacute;finition de certains termes &eacute;voqu&eacute;s dans les pr&eacute;sentes conditions g&eacute;n&eacute;rales d&rsquo;utilisation.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Utilisateur : L&#39;Utilisateur est toute personne qui utilise le Site ou l&#39;un des services propos&eacute;s sur le Site.</p>\r\n	</li>\r\n	<li>\r\n	<p>Contenu Utilisateur : Le terme &laquo; Contenu Utilisateur &raquo; d&eacute;signe les donn&eacute;es transmises par l&#39;Utilisateur dans les diff&eacute;rentes rubriques du Site.</p>\r\n	</li>\r\n	<li>\r\n	<p>Membre : Le terme &laquo; Membre &raquo; d&eacute;signe un utilisateur identifi&eacute; sur le site en raison de son inscription.</p>\r\n	</li>\r\n	<li>\r\n	<p>Identifiant : Le terme &laquo; Identifiant &raquo; recouvre les informations n&eacute;cessaires &agrave; l&#39;identification d&#39;un utilisateur sur le site pour acc&eacute;der aux zones r&eacute;serv&eacute;es aux membres.</p>\r\n	</li>\r\n	<li>\r\n	<p>Mot de passe : Le &laquo; Mot de passe &raquo; est une information confidentielle, dont l&#39;Utilisateur doit garder le secret, lui permettant, utilis&eacute; conjointement avec son Identifiant, de prouver l&#39;identit&eacute;.</p>\r\n	</li>\r\n	<li>\r\n	<p>Service du site Beneentrip&nbsp;: Le terme service d&eacute;signe l&rsquo;ensemble des op&eacute;rations, offres et facult&eacute; propos&eacute; sur le site beneentrip.com et ses variantes.</p>\r\n	</li>\r\n	<li>\r\n	<p>Internet&nbsp;: Le terme internet d&eacute;signe le r&eacute;seau t&eacute;l&eacute;matique international, qui r&eacute;sulte de l&rsquo;interconnexion des ordinateurs, Smartphones, tablettes tactiles et autres outils connect&eacute;s du monde entier utilisant un protocole commun d&rsquo;&eacute;changes de donn&eacute;es afin de dialoguer entre eux via les lignes de t&eacute;l&eacute;communication</p>\r\n	</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article .1&nbsp;: Objet&nbsp;:</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Les pr&eacute;sentes Conditions G&eacute;n&eacute;rales ont pour objet de d&eacute;finir les modalit&eacute;s de mise &agrave; disposition des services du site Beneentrip.com, et ses variantes, ci-apr&egrave;s nomm&eacute; &laquo; le Service &raquo; et les conditions d&#39;utilisation du Service par l&#39;Utilisateur.</p>\r\n\r\n<p>Tout acc&egrave;s et/ou Utilisation du site www. beneentrip.com, et ses variantes, suppose la lecture de l&#39;ensemble des termes des pr&eacute;sentes Conditions et leur acceptation inconditionnelle. Elles constituent donc un contrat entre le Service et l&#39;Utilisateur.</p>\r\n\r\n<p>Dans le cas o&ugrave; l&#39;Utilisateur ne souhaite pas accepter tout ou partie des pr&eacute;sentes conditions g&eacute;n&eacute;rales, il lui est demand&eacute; de renoncer &agrave; tout usage du Service.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 2&nbsp;: Mentions l&eacute;gales</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Le site beneentrip.com est &eacute;dit&eacute; par : (<em>partie &agrave; compl&eacute;ter)</em></p>\r\n\r\n<p>Le site www.beneentrip.com est h&eacute;berg&eacute; par : (<em>partie &agrave; compl&eacute;ter)</em></p>\r\n\r\n<h1>Article . 3&nbsp;: Acc&egrave;s au service</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Le Service est accessible gratuitement &agrave; tout Utilisateur disposant d&#39;un acc&egrave;s &agrave; internet. Tous les co&ucirc;ts aff&eacute;rents &agrave; l&#39;acc&egrave;s au Service, que ce soit les frais mat&eacute;riels, logiciels ou d&#39;acc&egrave;s &agrave; internet sont exclusivement &agrave; la charge de l&#39;utilisateur. Il est seul responsable du bon fonctionnement de son &eacute;quipement informatique ainsi que de son acc&egrave;s &agrave; internet.</p>\r\n\r\n<p>Certaines sections du site sont r&eacute;serv&eacute;es aux Membres apr&egrave;s identification &agrave; l&#39;aide de leur Identifiant et de leur Mot de passe.</p>\r\n\r\n<p>beneentrip.com se r&eacute;serve le droit de refuser l&#39;acc&egrave;s au Service, unilat&eacute;ralement et sans notification pr&eacute;alable, &agrave; tout Utilisateur ne respectant pas les pr&eacute;sentes conditions d&#39;utilisation.</p>\r\n\r\n<p>beneentrip.com met en &oelig;uvre tous les moyens raisonnables &agrave; sa disposition pour assurer un acc&egrave;s de qualit&eacute; au Service, mais n&#39;est tenu &agrave; aucune obligation d&#39;y parvenir. <strong>Elle est donc tenue &agrave; une obligation de moyen et non de r&eacute;sultat.</strong></p>\r\n\r\n<p>Beneentrip.com ne peut, en outre, &ecirc;tre tenue responsable de tout dysfonctionnement du r&eacute;seau ou des serveurs ou de tout autre &eacute;v&eacute;nement &eacute;chappant au contr&ocirc;le raisonnable, qui emp&ecirc;cherait ou d&eacute;graderait l&#39;acc&egrave;s au Service.</p>\r\n\r\n<p>beneentrip.com se r&eacute;serve la possibilit&eacute; d&#39;interrompre, de suspendre momentan&eacute;ment ou de modifier sans pr&eacute;avis l&#39;acc&egrave;s &agrave; tout ou partie du Service, afin d&#39;en assurer la maintenance, ou pour toute autre raison, sans que l&#39;interruption n&#39;ouvre droit &agrave; aucune obligation ni indemnisation.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 4: Propri&eacute;t&eacute; intellectuelle</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>beneentrip.com est une marque d&eacute;pos&eacute;e appartenant &agrave; la soci&eacute;t&eacute; Beneen Trip. Soci&eacute;t&eacute; en cours de constitution en vue d&rsquo;une inscription dans un Registre en France. Toute reproduction non autoris&eacute;e de cette marque, de ces logos et signes distinctifs constitue une contrefa&ccedil;on passible de sanctions p&eacute;nales. Le contrevenant s&#39;expose &agrave; des sanctions civiles et p&eacute;nales et notamment aux peines pr&eacute;vues aux articles L. 335.2 et L. 343.1 du code de la Propri&eacute;t&eacute; Intellectuelle.</p>\r\n\r\n<p>L&#39;Utilisateur est seul responsable du Contenu Utilisateur qu&#39;il met en ligne via le Service, ainsi que des textes et/ou opinions qu&#39;il formule. Il s&#39;engage notamment &agrave; ce que ces donn&eacute;es ne soient pas de nature &agrave; porter atteinte aux int&eacute;r&ecirc;ts l&eacute;gitimes de tiers quels qu&#39;ils soient. A ce titre, il garantit beneentrip.com contre tous recours, fond&eacute;s directement ou indirectement sur ces propos et/ou donn&eacute;es, susceptibles d&#39;&ecirc;tre intent&eacute;s par quiconque &agrave; l&#39;encontre de beneentrip.com. Il s&#39;engage en particulier &agrave; prendre en charge le paiement des sommes, quelles qu&#39;elles soient, r&eacute;sultant du recours d&#39;un tiers &agrave; l&#39;encontre de beneentrip.com, y compris les honoraires d&#39;avocat et frais de justice.</p>\r\n\r\n<p>Beneentrip.com se r&eacute;serve le droit de supprimer tout ou partie du Contenu Utilisateur, &agrave; tout moment et pour quelque raison que ce soit, sans avertissement ou justification pr&eacute;alable. L&#39;Utilisateur ne pourra faire valoir aucune r&eacute;clamation &agrave; ce titre.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 5&nbsp;: Donn&eacute;es personnelles</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Dans une logique de respect de la vie priv&eacute;e de ses Utilisateurs, beneentrip.com s&#39;engage &agrave; ce que la collecte et le traitement des donn&eacute;es personnelles, effectu&eacute;s au sein du pr&eacute;sent site, soient effectu&eacute;s conform&eacute;ment &agrave; la loi n&deg;78-17 du 6 janvier 1978 relative &agrave; l&#39;informatique, aux fichiers et aux libert&eacute;s, dite Loi &quot;Informatique et Libert&eacute;s&quot; telle que modifi&eacute;e en 2004.</p>\r\n\r\n<p>Conform&eacute;ment &agrave; l&#39;article 34 de la loi &quot;Informatique et Libert&eacute;s&quot;, beneentrip.com garantit &agrave; l&#39;Utilisateur un droit d&#39;opposition, d&#39;acc&egrave;s, de rectification et de suppression sur les donn&eacute;es personnelles le concernant.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 6&nbsp;: Limites de responsabilit&eacute;</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Le site beneentrip.com est un site de mise en relation entre personnes.</p>\r\n\r\n<p>Les annonces diffus&eacute;es sur le site beneentrip.com engagent la responsabilit&eacute; de leurs auteurs</p>\r\n\r\n<p>beneentrip.com ne peut garantir la conformit&eacute; entre l&rsquo;offre de service propos&eacute; et la prestation r&eacute;alis&eacute;e.</p>\r\n\r\n<p>En cons&eacute;quence, l&#39;Utilisation des informations et contenus disponibles sur l&#39;ensemble du site, ne sauraient en aucun cas engager la responsabilit&eacute; de beneentrip.com, &agrave; quelque titre que ce soit. L&#39;Utilisateur est seul ma&icirc;tre de la bonne utilisation, avec discernement et esprit, des informations, et services mises &agrave; sa disposition sur le Site.</p>\r\n\r\n<p>Par ailleurs, l&#39;Utilisateur s&#39;engage &agrave; indemniser beneentrip.com de toutes cons&eacute;quences dommageables li&eacute;es directement ou indirectement &agrave; l&#39;usage qu&#39;il fait du Service. L&rsquo;indemnisation se fait &agrave; hauteur du pr&eacute;judice subit.</p>\r\n\r\n<p>L&#39;acc&egrave;s &agrave; certaines sections du site beneentrip.com n&eacute;cessite l&#39;utilisation d&#39;un Identifiant et d&#39;un Mot de passe. Le Mot de passe, choisi par l&#39;utilisateur, est personnel et confidentiel. L&#39;utilisateur s&#39;engage &agrave; conserver secret son mot de passe et &agrave; ne pas le divulguer sous quelque forme que ce soit. L&#39;utilisation de son Identifiant et de son Mot de passe &agrave; travers internet se fait aux risques et p&eacute;rils de l&#39;Utilisateur. Il appartient &agrave; l&#39;Utilisateur de prendre toutes les dispositions n&eacute;cessaires permettant de prot&eacute;ger ses propres donn&eacute;es contre toute atteinte.</p>\r\n\r\n<p>L&#39;Utilisateur admet conna&icirc;tre les limitations et contraintes propres au r&eacute;seau internet et, &agrave; ce titre, reconna&icirc;t notamment l&#39;impossibilit&eacute; d&#39;une garantie totale de la s&eacute;curisation des &eacute;changes de donn&eacute;es. Beneentrip.com ne pourra pas &ecirc;tre tenue responsable des pr&eacute;judices d&eacute;coulant de la transmission de toute information, y compris de celle de son identifiant et/ou de son mot de passe, via le Service.</p>\r\n\r\n<p>Beneentrip.com ne pourra en aucun cas, dans la limite du droit applicable, &ecirc;tre tenue responsable des dommages et/ou pr&eacute;judices, directs ou indirects, mat&eacute;riels ou immat&eacute;riels, ou de quelque nature que ce soit, r&eacute;sultant d&#39;une indisponibilit&eacute; du Service ou de toute Utilisation du Service. Le terme &laquo; Utilisation &raquo; doit &ecirc;tre entendu au sens large, c&#39;est-&agrave;-dire tout usage du site quel qu&#39;il soit, licite ou non.</p>\r\n\r\n<p>L&#39;Utilisateur s&#39;engage, d&#39;une mani&egrave;re g&eacute;n&eacute;rale, &agrave; respecter l&#39;ensemble de la r&eacute;glementation en vigueur en France.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 7: Liens hypertextes</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Beneentrip.com propose des liens hypertextes vers des sites web &eacute;dit&eacute;s et/ou g&eacute;r&eacute;s par des tiers.</p>\r\n\r\n<p>Dans la mesure o&ugrave; aucun contr&ocirc;le n&#39;est exerc&eacute; sur ces ressources externes, l&#39;Utilisateur reconna&icirc;t que beneentrip.com n&#39;assume aucune responsabilit&eacute; relative &agrave; la mise &agrave; disposition de ces ressources, et ne peut &ecirc;tre tenue responsable quant &agrave; leur contenu.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article .8&nbsp;: Force majeure</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>La responsabilit&eacute; de beneentrip.com ne pourra &ecirc;tre engag&eacute;e en cas de force majeure ou de faits ind&eacute;pendants de sa volont&eacute;.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 9&nbsp;: Evolution du pr&eacute;sent contrat</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Beneentrip.com se r&eacute;serve le droit de modifier les termes, conditions et mentions du pr&eacute;sent contrat &agrave; tout moment, et sans pr&eacute;avis.</p>\r\n\r\n<p>Il est ainsi conseill&eacute; &agrave; l&#39;Utilisateur de consulter r&eacute;guli&egrave;rement la derni&egrave;re version des Conditions d&#39;Utilisation disponible sur le site <u><a href="http://www.beneentrip.com/">www.beneentrip.com</a></u></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 10: Dur&eacute;e et r&eacute;siliation</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Le pr&eacute;sent contrat est conclu pour une dur&eacute;e ind&eacute;termin&eacute;e &agrave; compter de l&#39;utilisation du service par l&#39;utilisateur.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>Article . 11: Droit applicable et juridiction comp&eacute;tente</h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Les r&egrave;gles en mati&egrave;re de droit, applicables aux contenus et aux transmissions de donn&eacute;es sur et autour du site, sont d&eacute;termin&eacute;es par la loi du lieu du si&egrave;ge social de Beneen-Trip. En cas de litige, n&#39;ayant pu faire l&#39;objet d&#39;un accord &agrave; l&#39;amiable, seuls les tribunaux fran&ccedil;ais du ressort de la Cour d&rsquo;Appel de Paris sont comp&eacute;tent.</p>\r\n\r\n<p>&nbsp;</p>', '2018-06-17 14:50:19', NULL);
INSERT INTO `page` (`id`, `titrePage`, `contenu`, `dateCreation`, `dateModification`) VALUES
(11, 'cgv', '<p style="text-align: center;"><span style="font-size:36px"><u><strong>Conditions g&eacute;n&eacute;rales de vente Beneen Trip</strong></u></span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article pr&eacute;liminaire</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Chacun des termes mentionn&eacute;s ci-dessous aura dans les pr&eacute;sentes Conditions G&eacute;n&eacute;rales de Vente du Service Beenen-Trip (ci-apr&egrave;s d&eacute;nomm&eacute;es les &laquo; CGV &raquo;) la signification suivante&nbsp;:</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Annonce&nbsp;:</strong> d&eacute;signe l&#39;ensemble des &eacute;l&eacute;ments et donn&eacute;es, d&eacute;pos&eacute; par un annonceur sous sa responsabilit&eacute; &eacute;ditoriale exclusive, en vue d&#39;offrir une prestation ou d&rsquo;en b&eacute;n&eacute;ficier d&rsquo;une et diffus&eacute;es sur le Site Internet, ou l&rsquo;application mobile.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Annonceur&nbsp;:</strong> d&eacute;signe tout professionnel ou non professionnel &eacute;tabli en France ou en dehors, titulaire d&#39;un Compte Beenen-Trip et utilisant le Service Beenen-Trip pour d&eacute;poser des annonces depuis le Site Internet, ou l&rsquo;application mobile.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Application Mobile&nbsp;: </strong>d&eacute;signe l&#39;Application mobile t&eacute;l&eacute;chargeable depuis les plateformes d&eacute;di&eacute;s et permettant aux Utilisateurs d&#39;acc&eacute;der via leur Smartphones aux Services Beenen-Trip.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Service client&nbsp;: </strong>d&eacute;signe le service aupr&egrave;s duquel les utilisateurs titulaires d&rsquo;un compte peuvent obtenir toutes informations compl&eacute;mentaires ou renseignements relatifs &agrave; Beenen-Trip.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Site Internet&nbsp;: </strong>d&eacute;signe le Site accessible depuis l&#39;URL <u><a href="http://www.beenen-trip.com/">www.beenen-trip.com</a></u> et ses variantes et permettant aux utilisateurs d&#39;acc&eacute;der via internet au Service Beenen-trip.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Utilisateur&nbsp;: </strong>d&eacute;signe une personne physique, jouissant de l&rsquo;utilisation du Service command&eacute; par le Client pour les besoins du Client.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Internet</strong>&nbsp;: D&eacute;signe le regroupement mondial de r&eacute;seaux &agrave; routeurs, priv&eacute; et public, qui sont interconnect&eacute;s au moyen de passerelles et de points d&rsquo;&eacute;changes et qui utilisent tous l&rsquo;ensemble de protocole TCP/IP.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Tarif/ Prix&nbsp;:</strong> D&eacute;signe les prix des Services exprim&eacute;s en euros hors taxes ou en toute autre monnaies pourvu que dans ce cas, la monnaie &eacute;voqu&eacute;s soit pr&eacute;cis&eacute;es.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 1. L&rsquo;objet</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Les pr&eacute;sentes CGV &eacute;tablissent les conditions contractuelles exclusivement applicables aux annonces pass&eacute;es par un annonceur depuis le Site Internet ou l&rsquo;application mobile.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 2&nbsp;: Acceptation</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Toute commande par l&#39;annonceur n&eacute;cessite l&#39;acceptation des pr&eacute;sentes CGV et sa renonciation &agrave; se pr&eacute;valoir de ses propres conditions g&eacute;n&eacute;rales d&#39;achat.</p>\r\n\r\n<p>Toute condition contraire oppos&eacute;e par l&#39;Annonceur sera donc &agrave; d&eacute;faut, d&#39;acceptation expresse, inopposable &agrave; Beenen-Trip, quel que soit le moment o&ugrave; elle aura pu &ecirc;tre port&eacute;e &agrave; sa connaissance.</p>\r\n\r\n<p>Le fait que Beenen-Trip ne se pr&eacute;vale pas &agrave; un moment donn&eacute; de l&#39;une quelconque des pr&eacute;sentes conditions g&eacute;n&eacute;rales de vente ne peut &ecirc;tre interpr&eacute;t&eacute; comme valant renonciation &agrave; se pr&eacute;valoir ult&eacute;rieurement de l&#39;une quelconque desdites conditions.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 3&nbsp;: R&egrave;gles de diffusion des Annonces</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>L&#39;Annonceur s&#39;engage &agrave; ne diffuser d&#39;Annonces qu&#39;en son nom et pour son propre compte. Ainsi, sauf accord pr&eacute;alable et expr&egrave;s de Beenen-Trip, l&#39;Annonceur ne peut utiliser le Service Beenen-trip pour diffuser des Annonces au nom et/ou pour le compte d&#39;un tiers.</p>\r\n\r\n<p>Beenen-trip se r&eacute;serve le droit de&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>supprimer&nbsp;toute Annonce diffus&eacute;e par l&#39;Annonceur au nom et/ou pour le compte d&#39;un tiers et ce sans qu&#39;aucun remboursement et/ou indemnisation ne puisse lui &ecirc;tre r&eacute;clam&eacute;(e) par l&#39;Annonceur&nbsp;;</p>\r\n	</li>\r\n	<li>\r\n	<p>supprimer sans pr&eacute;avis le&nbsp;Compte utilisateur et toutes Annonces en cours de diffusion d&#39;un Annonceur qui contreviendrait &agrave; la pr&eacute;sente disposition et ce sans qu&#39;aucun remboursement et/ou indemnisation ne puisse lui &ecirc;tre r&eacute;clam&eacute;(e) par l&#39;Annonceur&nbsp;;</p>\r\n	</li>\r\n	<li>\r\n	<p>d&#39;interdire l&#39;utilisation du Service Beenen-Trip aux fins de diffusion d&#39;Annonce &agrave; l&#39;Annonceur qui contreviendrait &agrave; la pr&eacute;sente disposition et ce sans qu&#39;aucun remboursement et/ou indemnisation ne puisse lui &ecirc;tre r&eacute;clam&eacute;(e) par l&#39;Annonceur.</p>\r\n	</li>\r\n</ul>\r\n\r\n<p>Sans que cela ne cr&eacute;e &agrave; la charge de Beenen-trip une obligation de v&eacute;rifier le contenu, l&#39;exactitude ou la coh&eacute;rence de l&#39;Annonce&nbsp;; toute Annonce d&eacute;pos&eacute;e et/ou modifi&eacute;e par l&#39;Annonceur pourra &ecirc;tre contr&ocirc;l&eacute;e par Beenen-Trip et sa diffusion sur le Service Beenen-trip pourra &ecirc;tre accept&eacute;e ou refus&eacute;e.</p>\r\n\r\n<p>Beenen-Trip se r&eacute;serve le droit de refuser toute ou partie d&#39;une Annonce qui contreviendrait aux dispositions des pr&eacute;sentes CGV, qui ne serait pas conforme aux r&egrave;gles de diffusion des Annonces et aux r&egrave;gles de diffusion du Service Beenen-Trip.</p>\r\n\r\n<p>Dans l&#39;hypoth&egrave;se o&ugrave; l&#39;Annonce contiendrait une photographie, Beenen-Trip se r&eacute;serve le droit de ne pas diffuser la photographie transmise par l&#39;Annonceur&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>si la qualit&eacute; de cette derni&egrave;re est insuffisante,</p>\r\n	</li>\r\n	<li>\r\n	<p>si elle est contraire aux r&egrave;gles de diffusion du Service Beenen-trip</p>\r\n	</li>\r\n</ul>\r\n\r\n<p>En aucun cas une annonce ne pourra servir &agrave; diffuser un message publicitaire autre qu&rsquo;une pr&eacute;sentation en rapport direct avec l&rsquo;offre de service propos&eacute;e.</p>\r\n\r\n<p>Si une Annonce est refus&eacute;e, avant sa mise en ligne, par Beenen-Trip, l&#39;Annonceur en sera inform&eacute; par email envoy&eacute; &agrave; l&#39;adresse indiqu&eacute;e lors de la cr&eacute;ation du Compte.</p>\r\n\r\n<p>Un tel refus ne fait na&icirc;tre au profit de l&#39;Annonceur aucun droit &agrave; indemnit&eacute;. Toute Annonce d&eacute;pos&eacute;e et valid&eacute;e est diffus&eacute;e sur le Service Beenen-trip pour une dur&eacute;e de deux mois, sauf retrait anticip&eacute; du fait de l&#39;Annonceur ou de Beenen-Trip notamment en raison de contenu illicite. Ce d&eacute;lai de deux mois peut faire l&rsquo;objet d&rsquo;une modification unilat&eacute;rale de la part du Service Beenen-Trip</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 4&nbsp;: Les annonces.</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2><u>4.1 R&egrave;gles g&eacute;n&eacute;rales</u></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Le b&eacute;n&eacute;fice de toute annonce est personnel &agrave; l&#39;annonceur qu&#39;il l&#39;a effectu&eacute;e et ne peut &ecirc;tre c&eacute;d&eacute;, transf&eacute;r&eacute; sans l&#39;accord de Beenen-trip. Aucun remboursement n&#39;est possible apr&egrave;s d&eacute;but d&#39;ex&eacute;cution de toute commande pass&eacute;e.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2><u>4.2 Moment de la Commande</u></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>L&rsquo;annonce est r&eacute;put&eacute;e &ecirc;tre effectu&eacute;e apr&egrave;s l&rsquo;acceptation des pr&eacute;sentes CGV et sa validation par son auteur.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2><u>4.3 Descriptif et tarifs</u></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Les prestations sont factur&eacute;es aux tarifs exprim&eacute; sur les plateformes Beenen-Trip par les annonceurs.</p>\r\n\r\n<p>Les prix peuvent &ecirc;tre r&eacute;vis&eacute;s par les parties, sous r&eacute;serve d&#39;une information pr&eacute;alable de Beenen-Trip.</p>\r\n\r\n<p>Par principe le paiement du prix entraine une obligation pour l&rsquo;offreur de prestation d&rsquo;ex&eacute;cuter la prestation.</p>\r\n\r\n<p>Les prestations sont r&eacute;put&eacute;es &ecirc;tre effectu&eacute;es &agrave; la date exprim&eacute;e sur les plateformes Beenen-Trip, ou &agrave; toute autre date fix&eacute;es par les parties d&rsquo;un commun accord.</p>\r\n\r\n<p>La v&eacute;rification de la conformit&eacute; des prestations propos&eacute;es, par rapport aux offres de prestation doit &ecirc;tre effectu&eacute;e au jour de la prestation.</p>\r\n\r\n<p>En cas de d&eacute;calage, de distorsion, ou de diff&eacute;rence majeure entre la prestation promise et la prestation effectivement propos&eacute;e, le b&eacute;n&eacute;ficiaire de prestation &eacute;mettra des r&eacute;serves claires et pr&eacute;cises qu&rsquo;il notifiera dans un d&eacute;lai de 24h&nbsp;suivant le d&eacute;roulement de la prestation sur les plateformes Beenen-Trip notamment via la rubrique consacr&eacute;e aux r&eacute;clamations et plaintes du site internet et de l&rsquo;application mobile.</p>\r\n\r\n<p>Il appartiendra alors &agrave; ce dernier de fournir toute justification quant &agrave; la r&eacute;alit&eacute; du d&eacute;calage, diff&eacute;rence ou des distorsions constat&eacute;es.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Toute annulation de prestations command&eacute;es, doit faire l&#39;objet d&#39;un accord formel entre l&rsquo;offreur de prestation et le b&eacute;n&eacute;ficiaire. Beenen-Trip devra en &ecirc;tre imm&eacute;diatement inform&eacute; et devra y apporter son accord.</p>\r\n\r\n<p>A d&eacute;faut d&rsquo;accord de la soci&eacute;t&eacute; Beneen Trip, l&rsquo;annulation de prestation ne pourra pas &ecirc;tre admise.</p>\r\n\r\n<p>L&rsquo;offreur de prestation apportera le plus grand soin &agrave; l&#39;ex&eacute;cution de sa prestation et &agrave; la qualit&eacute; de celle ci. En cas de d&eacute;fectuosit&eacute; reconnue par l&rsquo;offreur de prestation, l&#39;obligation de ce dernier sera limit&eacute;e au remplacement ou au remboursement du b&eacute;n&eacute;ficiaire, sans autre indemnit&eacute;. Sont exclus de la garantie les d&eacute;fauts et dommages r&eacute;sultant d&#39;un cas de force majeure, ou de l&rsquo;attitude du b&eacute;n&eacute;ficiaire de prestation (mauvais comportement de ce dernier).</p>\r\n\r\n<p>Chacune des parties devra adopter une attitude responsable de nature &agrave; garantir la s&eacute;curit&eacute; de chacun des participants.</p>\r\n\r\n<p>Sauf conditions particuli&egrave;res, les prestations sont payables directement sur les plateformes Beenen-Trip.</p>\r\n\r\n<p>Toutes situations particuli&egrave;res autorisant un paiement diff&eacute;r&eacute; devra respecter les conditions particuli&egrave;res fix&eacute;es par la Beenen-trip.</p>\r\n\r\n<p>Par d&eacute;finition les b&eacute;n&eacute;ficiaires de prestation b&eacute;n&eacute;ficient d&rsquo;un d&eacute;lai de r&eacute;tractation moyennant une retenue fond&eacute;e sur la grille fix&eacute; par le Service Beenen-Trip.</p>\r\n\r\n<h2><u>4.4. Frais d&#39;insertion</u></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Les frais d&#39;insertion sont en principe gratuits. Certains services pourront cependant &ecirc;tre payants.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Beneen trip se r&eacute;serve le droit de pr&eacute;lever les frais de r&eacute;servation selon le bar&egrave;me fix&eacute; en annexe</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 5. Engagement et garanties de l&rsquo;annonceur</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>5.1 L&#39;Annonceur certifie que l&#39;annonce, quelque soit sa diffusion, est conforme &agrave; l&#39;ensemble des dispositions l&eacute;gales et r&eacute;glementaires en vigueur, respecte les dispositions des pr&eacute;sentes CGV et les r&egrave;gles de diffusion du Service Beenen-Trip et ne porte pas atteinte aux droits des tiers (notamment aux droits de propri&eacute;t&eacute; intellectuelle et aux droits de la personnalit&eacute;).</p>\r\n\r\n<p>L&#39;Annonceur garantit que le contenu de ses Annonces est strictement conforme aux obligations l&eacute;gales s&#39;imposant &agrave; son activit&eacute;.</p>\r\n\r\n<p>L&#39;Annonceur garantit &ecirc;tre l&#39;auteur unique et exclusif du texte, des dessins, photographies etc. composant l&#39;Annonce. A d&eacute;faut, il d&eacute;clare disposer de tous les droits et autorisations n&eacute;cessaires &agrave; la diffusion de l&#39;Annonce.</p>\r\n\r\n<p>En cons&eacute;quence, toute Annonce d&eacute;pos&eacute;e et diffus&eacute;e sur le Service Beenen-trip para&icirc;t sous la responsabilit&eacute; exclusive de l&#39;Annonceur</p>\r\n\r\n<p>En cons&eacute;quence, l&#39;Annonceur rel&egrave;ve Beenen-trip , ses sous-traitants et fournisseurs, de toutes responsabilit&eacute;s, les garantit contre toutes condamnations, frais judiciaires et extrajudiciaires, qui r&eacute;sulteraient de tout recours en relation avec l&#39;Annonce, et les indemnise pour tout dommage r&eacute;sultant de la violation de la pr&eacute;sente disposition.</p>\r\n\r\n<p>L&#39;Annonceur reconna&icirc;t et accepte que Beenen-Trip est en droit de supprimer, sans pr&eacute;avis ni indemnit&eacute; ni droit &agrave; remboursement, toute Annonce en cours de diffusion qui ne serait pas conforme aux r&egrave;gles de diffusions du Service et/ou qui serait susceptible de porter atteinte aux droits d&#39;un tiers ou contiendrait un contenu illicite.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>5.2 L&#39;Annonceur s&#39;engage &agrave; ne proposer dans ses Annonces que des offres de prestations qu&rsquo;il peut effectivement fournir. L&#39;Annonceur s&#39;engage, en cas d&#39;indisponibilit&eacute;, &agrave; proc&eacute;der au retrait de l&#39;Annonce du Service Beenen-Trip d&egrave;s sa prise de connaissance.&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>5.3 A ce titre, l&#39;Annonceur reconna&icirc;t et accepte que pour des raisons d&#39;ordre technique, la mise en ligne d&#39;une Annonce sur le Site Internet, et l&#39;Application mobile peut ne pas &ecirc;tre instantan&eacute;e avec sa validation.</p>\r\n\r\n<p>5.4 L&#39;Annonceur d&eacute;clare conna&icirc;tre l&#39;&eacute;tendue de diffusion du Site Internet, avoir pris toutes pr&eacute;cautions pour respecter la l&eacute;gislation en vigueur des lieux de r&eacute;ception et d&eacute;charger Beenen-Trip de toutes responsabilit&eacute;s &agrave; cet &eacute;gard.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>5.5 L&#39;Annonceur accepte que les donn&eacute;es collect&eacute;es ou recueillies sur le Site Internet soient conserv&eacute;es par les fournisseurs d&#39;acc&egrave;s et utilis&eacute;es &agrave; des fins statistiques, publicitaires ou pour r&eacute;pondre &agrave; des demandes d&eacute;termin&eacute;es ou &eacute;manant des pouvoirs publics.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>5.6 Pour &ecirc;tre recevable, toute r&eacute;clamation devra indiquer pr&eacute;cis&eacute;ment le(s) d&eacute;faut(s) all&eacute;gu&eacute;(s) de l&#39;Annonce et &ecirc;tre transmise par &eacute;crit &agrave; Beenen-Trip dans un d&eacute;lai de sept jours ouvrables &agrave; compter de la date de d&eacute;p&ocirc;t. La r&eacute;clamation devra &ecirc;tre effectu&eacute; &agrave; l&rsquo;adresse du si&egrave;ge sociale de Beneen Trip.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 6. La r&eacute;siliation</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>La cr&eacute;ation de compte Beenen-trip est gratuite. La r&eacute;siliation se fait via le site internet, via l&rsquo;onglet d&eacute;di&eacute;.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 7&nbsp;: Limitation de la responsabilit&eacute;</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Beenen-trip s&#39;engage &agrave; mettre en &oelig;uvre tous les moyens n&eacute;cessaires afin d&#39;assurer au mieux la fourniture du Service Beenen-Trip qu&#39;elle propose &agrave; l&#39;Annonceur.</p>\r\n\r\n<p>Sauf engagement &eacute;crit contraire, la prestation commercialis&eacute;e par Beenen-Trip se limite &agrave; la diffusion d&#39;Annonce, avec souscription d&#39;options, sur le Service Beenen-trip, &agrave; l&#39;exclusion de toute autre prestation.</p>\r\n\r\n<p>Beenen-Trip ne garantit aucunement les &eacute;ventuels r&eacute;sultats escompt&eacute;s par l&#39;Annonceur suite &agrave; la diffusion des Annonces.</p>\r\n\r\n<p>Beenen-Trip ne pourra &ecirc;tre tenue responsable de la capture des donn&eacute;es qui serait faite &agrave; son insu, ni de la tra&ccedil;abilit&eacute; qui en r&eacute;sulterait.</p>\r\n\r\n<p>Beenen-Trip ne pourra &ecirc;tre tenue responsable des interruptions et modifications du Service internet et/ou du Site Internet, de l&rsquo;application Mobile et de la perte de donn&eacute;es ou d&#39;informations stock&eacute;es par Beenen-trip&nbsp;;</p>\r\n\r\n<p>Il incombe &agrave; l&#39;Annonceur de prendre toutes pr&eacute;cautions utiles pour conserver les Annonces qu&#39;ils publient sur le Site Internet.</p>\r\n\r\n<p>Beenen-Trip ne pourra &ecirc;tre tenue responsable, notamment ni du fait de pr&eacute;judices ou dommages directs ou indirects, de quelque nature que ce soit, r&eacute;sultant de la gestion, l&#39;utilisation, l&#39;exploitation, l&#39;interruption ou le dysfonctionnement du Site Internet, et de l&#39;Application mobile.</p>\r\n\r\n<p>Beenen-Trip et ses sous-traitants ou fournisseurs, ne pourront &ecirc;tre tenus pour responsables des retards ou impossibilit&eacute;s de remplir leurs obligations contractuelles, en cas&nbsp;:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>de force majeure,</p>\r\n	</li>\r\n	<li>\r\n	<p>d&#39;interruption de la connexion au Site Internet en raison d&#39;op&eacute;rations de maintenance ou d&#39;actualisation des informations publi&eacute;es,</p>\r\n	</li>\r\n	<li>\r\n	<p>d&#39;impossibilit&eacute; momentan&eacute;e d&#39;acc&egrave;s au Site Internet en raison de probl&egrave;mes techniques, quelle qu&#39;en soit l&#39;origine,</p>\r\n	</li>\r\n	<li>\r\n	<p>d&#39;attaque ou piratage informatique, privation, suppression ou interdiction, temporaire ou d&eacute;finitive, et pour quelque cause que ce soit, de l&#39;acc&egrave;s au r&eacute;seau Internet.</p>\r\n	</li>\r\n</ul>\r\n\r\n<p>L&#39;Annonceur reconna&icirc;t en outre qu&#39;en l&#39;&eacute;tat actuel de la technique et en l&#39;absence de garantie des op&eacute;rateurs de t&eacute;l&eacute;communications, la disponibilit&eacute; permanente du Service Beenen-Trip et notamment du Site Internet ne peut &ecirc;tre garantie.</p>\r\n\r\n<p>Sauf tromperie ou faute lourde, Beenen-trip, ses sous-traitants et fournisseurs ne seront tenus en aucun cas &agrave; r&eacute;paration, p&eacute;cuniaires ou en nature, du fait d&#39;erreurs ou d&#39;omissions dans la composition d&#39;une Annonce. En particulier, de tels &eacute;v&eacute;nements ne pourront en aucun cas justifier un refus de paiement, m&ecirc;me partiel, ni ouvrir droit &agrave; une Annonce aux frais de Beenen-trip, ou &agrave; une indemnisation.</p>\r\n\r\n<p>Beenen-trip se r&eacute;serve le droit, sans aucun pr&eacute;avis, de modifier, d&#39;interrompre ou d&#39;arr&ecirc;ter l&#39;accessibilit&eacute; &agrave; tout ou partie du Service du Site Internet, de l&rsquo;application Mobile, sans &ecirc;tre tenue de verser &agrave; l&#39;Annonceur une indemnit&eacute; de quelque nature que ce soit</p>\r\n\r\n<p>Ni l&#39;Annonceur, d&#39;une part, ni Beenen-Trip, ses sous-traitants ou fournisseurs, d&#39;autre part, ne pourront &ecirc;tre tenu pour responsable de tout retard, inex&eacute;cution ou autre manquement &agrave; leurs obligations r&eacute;sultant d&#39;un cas de force majeure.</p>\r\n\r\n<p>Seront consid&eacute;r&eacute;s comme des cas de force majeur ceux habituellement retenus par la jurisprudence des Cours et Tribunaux fran&ccedil;ais ainsi que les gr&egrave;ves totales ou partielles, internes ou externes &agrave; l&#39;une des parties, &agrave; un fournisseur ou sous-traitant, lock-out, blocages des moyens de transport ou d&#39;approvisionnement pour quelque raison que ce soit, incendies, temp&ecirc;tes, inondations, d&eacute;g&acirc;ts des eaux, restrictions gouvernementales ou l&eacute;gales, modifications l&eacute;gales ou r&eacute;glementaires des formes de commercialisation, blocage des moyens de t&eacute;l&eacute;communications, y compris les r&eacute;seaux, et tout autre cas ind&eacute;pendant de la volont&eacute; de l&#39;Annonceur, de Beenen-Trip, ses sous-traitants ou fournisseurs emp&ecirc;chant l&#39;ex&eacute;cution normale des prestations.</p>\r\n\r\n<p>En pr&eacute;sence d&#39;un cas de force majeur, si l&#39;emp&ecirc;chement d&#39;ex&eacute;cuter normalement l&#39;obligation contractuelle devait perdurer plus d&rsquo;un moi, les parties seraient lib&eacute;r&eacute;es de leurs obligations r&eacute;ciproques sans qu&#39;aucune indemnit&eacute; ne puisse &ecirc;tre r&eacute;clam&eacute;e &agrave; la partie d&eacute;faillante.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 8&nbsp;: Propri&eacute;t&eacute; intellectuelle</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Tous les droits de propri&eacute;t&eacute; intellectuelle (tels que notamment droits d&#39;auteur, droits voisins, droits des marques, droits des producteurs de bases de donn&eacute;es) portant tant sur la structure que sur les contenus du Site Internet, du Site Mobile et de l&#39;Application mobile et notamment les images, sons, vid&eacute;os, photographies, logos, marques, &eacute;l&eacute;ments graphiques, textuels, visuels, outils, logiciels, documents, donn&eacute;es, etc. (ci-apr&egrave;s d&eacute;sign&eacute;s dans leur ensemble &laquo;&nbsp;El&eacute;ments&nbsp;&raquo;) sont r&eacute;serv&eacute;s. Ces El&eacute;ments sont la propri&eacute;t&eacute; de Beenen-Trip. Ces El&eacute;ments sont mis &agrave; disposition des Annonceurs, &agrave; titre gracieux, pour la seule utilisation du Service Beenen-Trip et dans le cadre d&#39;une utilisation normale de ses fonctionnalit&eacute;s. Les Annonceurs s&#39;engagent &agrave; ne modifier en aucune mani&egrave;re les El&eacute;ments.</p>\r\n\r\n<p>Toute utilisation non express&eacute;ment autoris&eacute;e des El&eacute;ments du Site Internet, et de l&#39;Application mobile entra&icirc;ne une violation des droits d&#39;auteur et constitue une contrefa&ccedil;on. Elle peut aussi entra&icirc;ner une violation des droits &agrave; l&#39;image, droits des personnes ou de tous autres droits et r&eacute;glementations en vigueur. Elle peut donc engager la responsabilit&eacute; civile et/ou p&eacute;nale de son auteur.</p>\r\n\r\n<p>Les marques et logos Beenen-Trip ainsi les marques et logos des partenaires de Beenen-trip sont des marques d&eacute;pos&eacute;es. Toute reproduction totale ou partielle de ces marques et/ou logos sans l&#39;autorisation pr&eacute;alable et &eacute;crite de Beenen-Trip est interdite.</p>\r\n\r\n<p>Beenen-Trip est producteur des bases de donn&eacute;es du Service Beenen-trip.com. En cons&eacute;quence, toute extraction et/ou r&eacute;utilisation de la ou des bases de donn&eacute;es au sens des articles L 342-1 et L 342-2 du code de la propri&eacute;t&eacute; intellectuelle est interdite.</p>\r\n\r\n<p>Beenen-trip se r&eacute;serve la possibilit&eacute; de saisir toutes voies de droit &agrave; l&#39;encontre des personnes qui n&#39;auraient pas respect&eacute; les interdictions contenues dans le pr&eacute;sent article.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article 9&nbsp;: Conditions g&eacute;n&eacute;rales de vente et modification</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Si une partie des CGV devait s&#39;av&eacute;rer ill&eacute;gale, invalide ou inapplicable, pour quelque raison que ce soit, les dispositions en question seraient r&eacute;put&eacute;es non &eacute;crites, sans remettre en cause la validit&eacute; des autres dispositions qui continueront de s&#39;appliquer entre les Annonceurs et Beenen-trip.</p>\r\n\r\n<p>Il est &agrave; noter que le pr&eacute;sent contrat et les pr&eacute;sentes CGV sont soumises aux dispositions de la loi du N&deg;2004-575 du 21 Juin 2004 art 25-II et l&#39;Ordonnance N&deg;2005-674 du 16 Juin 2005 et aux articles 1369-1 &agrave; 1369-2 du Code Civil.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Beenen-Trip se r&eacute;serve la possibilit&eacute;, &agrave; tout moment, de modifier en tout ou partie les CGV.</p>\r\n\r\n<p>Les Annonceurs sont invit&eacute;s &agrave; prendre r&eacute;guli&egrave;rement connaissance des CGV afin de prendre connaissance de changements apport&eacute;s.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h1>&nbsp;<u>Article&nbsp;10: Juridiction comp&eacute;tente</u></h1>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Tout litige relatif aux pr&eacute;sentes sera de la comp&eacute;tence exclusive de la juridiction du lieu du si&egrave;ge social de BeneenTrip. Les pr&eacute;sentes conditions g&eacute;n&eacute;rales d&#39;utilisation sont soumises &agrave; la loi fran&ccedil;aise.</p>', '2018-06-17 14:54:12', NULL),
(12, 'charteConduite', '<p style="text-align: center;"><strong><span style="font-size:26px">La charte de bonne conduite</span></strong></p>\r\n\r\n<p style="text-align: center;">&nbsp;</p>\r\n\r\n<h3 style="text-align: center;"><strong>Article 1. Responsabilit&eacute; personnelle</strong></h3>\r\n\r\n<p><br />\r\nTous les membres doivent accepter la responsabilit&eacute; personnelle qui leur incombe de respecter la charte de bonne conduite. Ils doivent tout particuli&egrave;rement s&rsquo;acquitter de leurs t&acirc;ches avec honn&ecirc;tet&eacute;, soin, diligence, professionnalisme, impartialit&eacute; et &eacute;thique.</p>\r\n\r\n<p style="text-align: center;">&nbsp;</p>\r\n\r\n<h3 style="text-align: center;"><strong>Article 2. Respect de la loi.</strong></h3>\r\n\r\n<p><br />\r\nLes utilisateurs de Beneen Trip se doivent de respecter les lois propres &agrave; chaque pays.</p>\r\n\r\n<h3 style="text-align: center;"><br />\r\n<strong>Article 3. Publication et contenu</strong></h3>\r\n\r\n<p><br />\r\nNe publier que des posts en lien avec l&#39;activit&eacute; du site, Beneen Trip se r&eacute;serve le droit de supprimer toute publication non conforme. Aucune remarque inappropri&eacute; n&rsquo;est tol&eacute;r&eacute;.</p>\r\n\r\n<h3 style="text-align: center;"><strong>Article 4. Paiement </strong></h3>\r\n\r\n<p>Toutes transactions financi&egrave;res&nbsp; entre guides et touristes devront uniquement se faire via la plateforme BT.&nbsp; Les guides ne doivent sous aucun pr&eacute;texte r&eacute;clamer un compl&eacute;ment de revenu lors de l&#39;activit&eacute;.</p>\r\n\r\n<h3 style="text-align: center;"><strong>Article 5. Conduite &agrave; adopter</strong></h3>\r\n\r\n<p>Le respect d&#39;autrui et la politesse sont une n&eacute;cessit&eacute; imp&eacute;rieuse de la vie en communaut&eacute;. Tous les membres se doivent d&#39;adopter un comportement correct. Le respect de la dignit&eacute; d&#39;autrui, proscrit rigoureusement toutes les manifestations qui conduisent &agrave; des actes d&#39;incivilit&eacute; qui d&eacute;t&eacute;riorent les relations de vie commune et quelconque endroit. Aucune violence verbale ou physique ne seront tol&eacute;r&eacute;s. Ne prenez aucun risque.</p>\r\n\r\n<h3 style="text-align: center;"><strong>Article 6. Horaires-assiduit&eacute;-ponctualit&eacute;</strong></h3>\r\n\r\n<p>Babacar =&gt; Etre ponctuel ; respecter les horaires de d&eacute;but et de fin d&rsquo;activit&eacute; ; pr&eacute;venir en cas d&rsquo;arriv&eacute;e tardive ou en cas de d&eacute;part anticip&eacute; en fin d&rsquo;activit&eacute;.</p>', '2018-08-24 22:48:06', NULL),
(13, 'partenaire', '<p style="text-align: center;"><span style="font-size:24px"><strong>Le saviez-vous ?</strong></span></p>\r\n\r\n<p>&nbsp;Beneen Trip va proposer une plateforme en ligne pour mettre en relation des offreurs et des demandeurs d&rsquo;activit&eacute;s touristiques. &nbsp;<br />\r\nVous souhaitez devenir partenaire de Beneen Trip ? Si vous souhaitez sensibiliser votre communaut&eacute; concernant&nbsp; diff&eacute;rentes acitivit&eacute;s culturelles / sociales et si vous avez une quelconque id&eacute;e de collaboration, contactez-nous.</p>', '2018-08-24 23:09:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL,
  `paye` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation_user`
--

CREATE TABLE `reservation_user` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_langue`
--

CREATE TABLE `user_langue` (
  `user_id` int(11) NOT NULL,
  `langue_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_langue`
--

INSERT INTO `user_langue` (`user_id`, `langue_id`) VALUES
(1, 38),
(1, 48),
(2, 33),
(2, 38),
(2, 48),
(3, 48);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `genre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typeutilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `privilege` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `urlPhoto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateNaissance` date NOT NULL,
  `telephone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `monnaie` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'EUR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `genre`, `nom`, `photo`, `typeutilisateur`, `privilege`, `urlPhoto`, `dateCreation`, `dateModification`, `prenom`, `dateNaissance`, `telephone`, `avatar_id`) VALUES
(1, 'micdejc', 'micdejc', 'midcejc@gmail.com', 'midcejc@gmail.com', 1, NULL, '$2y$13$REvaLEbGgFehBA2P3fIBy.rD/SWw1KQhnSGJyJwBOneM.tya.2enW', '2018-08-07 11:27:29', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'Homme', 'michael yamsi', NULL, 'Guide', 'ROLE_ADMIN', NULL, '2018-06-14 15:39:37', '2018-08-07 11:27:29', '', '1992-01-05', NULL, NULL, 'EUR'),
(2, 'nsanith', 'nsanith', 'nsanith@gmail.com', 'nsanith@gmail.com', 1, NULL, '$2y$13$tx9WlnPkfk0CXpGWk1tHFuQz7LmBeUmPJLrnViWGkGR/8kXdeeDBK', '2018-06-17 14:40:33', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'Homme', 'nsani thierry', NULL, 'Guide', 'ROLE_ADMIN', NULL, '2018-06-15 15:30:21', '2018-06-22 13:31:22', '', '1986-01-01', NULL, NULL, 'EUR'),
(3, 'paullecodeur', 'paullecodeur', 'paulericyemdji@gmail.com', 'paulericyemdji@gmail.com', 1, NULL, '$2y$13$ZZ4RLSLDCpFOIiSUK10hAujkcbyA9pVqNC6RRK6jp8d58D.CstXeu', '2018-06-18 04:54:39', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'Homme', 'paul eric', NULL, 'Guide', 'ROLE_ADMIN', NULL, '2018-06-15 15:32:13', '2018-06-23 20:50:00', '', '1988-01-01', NULL, NULL, 'EUR');


-- --------------------------------------------------------

--
-- Table structure for table `reservation_activite`
--

CREATE TABLE `reservation_activite` (
  `reservation_id` int(11) NOT NULL,
  `activite_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `itemId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transactionId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `currencyCode` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UNCOMPLETED',
  `transactionToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateCreation` datetime NOT NULL,
  `dateModification` datetime DEFAULT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `invoice` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transactionPayer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B875551591F8D062` (`image_principale_id`),
  ADD KEY `IDX_B8755515BCF5E72D` (`categorie_id`),
  ADD KEY `IDX_B875551560BB6FE6` (`auteur_id`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussion`
--
ALTER TABLE `discussion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C0B9F90F60BB6FE6` (`auteur_id`),
  ADD KEY `IDX_C0B9F90F9B0F88B1` (`activite_id`);

--
-- Indexes for table `discussion_user`
--
ALTER TABLE `discussion_user`
  ADD PRIMARY KEY (`discussion_id`,`user_id`),
  ADD KEY `IDX_A8FD7A7F1ADED311` (`discussion_id`),
  ADD KEY `IDX_A8FD7A7FA76ED395` (`user_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045F9B0F88B1` (`activite_id`);

--
-- Indexes for table `langue`
--
ALTER TABLE `langue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation_user`
--
ALTER TABLE `reservation_user`
  ADD PRIMARY KEY (`reservation_id`,`user_id`),
  ADD KEY `IDX_9BAA1B21B83297E7` (`reservation_id`),
  ADD KEY `IDX_9BAA1B21A76ED395` (`user_id`);

--
-- Indexes for table `user_langue`
--
ALTER TABLE `user_langue`
  ADD PRIMARY KEY (`user_id`,`langue_id`),
  ADD KEY `IDX_F6056EB3A76ED395` (`user_id`),
  ADD KEY `IDX_F6056EB32AADBACD` (`langue_id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_497B315E92FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_497B315EA0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_497B315EC05FB297` (`confirmation_token`),
  ADD UNIQUE KEY `UNIQ_497B315E86383B10` (`avatar_id`);


--
-- Indexes for table `reservation_activite`
--
ALTER TABLE `reservation_activite`
  ADD PRIMARY KEY (`reservation_id`,`activite_id`),
  ADD KEY `IDX_25C0B701B83297E7` (`reservation_id`),
  ADD KEY `IDX_25C0B7019B0F88B1` (`activite_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6D28840DFB88E14F` (`utilisateur_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activite`
--
ALTER TABLE `activite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `discussion`
--
ALTER TABLE `discussion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `langue`
--
ALTER TABLE `langue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;
--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `FK_B875551560BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `FK_B875551591F8D062` FOREIGN KEY (`image_principale_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_B8755515BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `FK_C0B9F90F60BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `FK_C0B9F90F9B0F88B1` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`id`);

--
-- Constraints for table `discussion_user`
--
ALTER TABLE `discussion_user`
  ADD CONSTRAINT `FK_A8FD7A7F1ADED311` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A8FD7A7FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F9B0F88B1` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`id`);



--
-- Constraints for table `reservation_user`
--
ALTER TABLE `reservation_user`
  ADD CONSTRAINT `FK_9BAA1B21A76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9BAA1B21B83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_langue`
--
ALTER TABLE `user_langue`
  ADD CONSTRAINT `FK_F6056EB32AADBACD` FOREIGN KEY (`langue_id`) REFERENCES `langue` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F6056EB3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;


--
-- Constraints for table `reservation_activite`
--
ALTER TABLE `reservation_activite`
  ADD CONSTRAINT `FK_25C0B7019B0F88B1` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_25C0B701B83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_6D28840DFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);


--
-- Constraints for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `FK_497B315E86383B10` FOREIGN KEY (`avatar_id`) REFERENCES `image` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
