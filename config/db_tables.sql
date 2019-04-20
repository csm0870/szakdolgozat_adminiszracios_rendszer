-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2019. Ápr 20. 14:39
-- Kiszolgáló verziója: 10.1.19-MariaDB
-- PHP verzió: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `szakdolgozat_adminisztracios_rendszer`
--
CREATE DATABASE IF NOT EXISTS `szakdolgozat_adminisztracios_rendszer` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `szakdolgozat_adminisztracios_rendszer`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `acl_phinxlog`
--

DROP TABLE IF EXISTS `acl_phinxlog`;
CREATE TABLE `acl_phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `acl_phinxlog`
--

INSERT INTO `acl_phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20141229162641, 'CakePhpDbAcl', '2019-04-20 08:03:37', '2019-04-20 08:03:38', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `acos`
--

DROP TABLE IF EXISTS `acos`;
CREATE TABLE `acos` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 824),
(2, 1, NULL, NULL, 'ConsultationOccasions', 2, 13),
(3, 2, NULL, NULL, 'index', 3, 4),
(4, 2, NULL, NULL, 'view', 5, 6),
(5, 2, NULL, NULL, 'add', 7, 8),
(6, 2, NULL, NULL, 'edit', 9, 10),
(7, 2, NULL, NULL, 'delete', 11, 12),
(8, 1, NULL, NULL, 'Consultations', 14, 17),
(9, 8, NULL, NULL, 'exportPdf', 15, 16),
(10, 1, NULL, NULL, 'CourseLevels', 18, 29),
(11, 10, NULL, NULL, 'index', 19, 20),
(12, 10, NULL, NULL, 'view', 21, 22),
(13, 10, NULL, NULL, 'add', 23, 24),
(14, 10, NULL, NULL, 'edit', 25, 26),
(15, 10, NULL, NULL, 'delete', 27, 28),
(16, 1, NULL, NULL, 'Courses', 30, 41),
(17, 16, NULL, NULL, 'index', 31, 32),
(18, 16, NULL, NULL, 'view', 33, 34),
(19, 16, NULL, NULL, 'add', 35, 36),
(20, 16, NULL, NULL, 'edit', 37, 38),
(21, 16, NULL, NULL, 'delete', 39, 40),
(22, 1, NULL, NULL, 'CourseTypes', 42, 53),
(23, 22, NULL, NULL, 'index', 43, 44),
(24, 22, NULL, NULL, 'view', 45, 46),
(25, 22, NULL, NULL, 'add', 47, 48),
(26, 22, NULL, NULL, 'edit', 49, 50),
(27, 22, NULL, NULL, 'delete', 51, 52),
(28, 1, NULL, NULL, 'Departments', 54, 65),
(29, 28, NULL, NULL, 'index', 55, 56),
(30, 28, NULL, NULL, 'view', 57, 58),
(31, 28, NULL, NULL, 'add', 59, 60),
(32, 28, NULL, NULL, 'edit', 61, 62),
(33, 28, NULL, NULL, 'delete', 63, 64),
(34, 1, NULL, NULL, 'Documents', 66, 69),
(35, 34, NULL, NULL, 'downloadFile', 67, 68),
(36, 1, NULL, NULL, 'Error', 70, 71),
(37, 1, NULL, NULL, 'FinalExamSubjects', 72, 75),
(38, 37, NULL, NULL, 'exportDoc', 73, 74),
(39, 1, NULL, NULL, 'Groups', 76, 87),
(40, 39, NULL, NULL, 'index', 77, 78),
(41, 39, NULL, NULL, 'view', 79, 80),
(42, 39, NULL, NULL, 'add', 81, 82),
(43, 39, NULL, NULL, 'edit', 83, 84),
(44, 39, NULL, NULL, 'delete', 85, 86),
(45, 1, NULL, NULL, 'Information', 88, 103),
(46, 45, NULL, NULL, 'index', 89, 90),
(47, 45, NULL, NULL, 'view', 91, 92),
(48, 45, NULL, NULL, 'add', 93, 94),
(49, 45, NULL, NULL, 'edit', 95, 96),
(50, 45, NULL, NULL, 'delete', 97, 98),
(51, 45, NULL, NULL, 'setFillingInPeriod', 99, 100),
(52, 45, NULL, NULL, 'setEncryptionRequlation', 101, 102),
(53, 1, NULL, NULL, 'Install', 104, 107),
(54, 53, NULL, NULL, 'install', 105, 106),
(55, 1, NULL, NULL, 'InternalConsultants', 108, 119),
(56, 55, NULL, NULL, 'index', 109, 110),
(57, 55, NULL, NULL, 'view', 111, 112),
(58, 55, NULL, NULL, 'add', 113, 114),
(59, 55, NULL, NULL, 'edit', 115, 116),
(60, 55, NULL, NULL, 'delete', 117, 118),
(61, 1, NULL, NULL, 'Notifications', 120, 131),
(62, 61, NULL, NULL, 'index', 121, 122),
(63, 61, NULL, NULL, 'view', 123, 124),
(64, 61, NULL, NULL, 'add', 125, 126),
(65, 61, NULL, NULL, 'edit', 127, 128),
(66, 61, NULL, NULL, 'delete', 129, 130),
(67, 1, NULL, NULL, 'OfferedTopics', 132, 143),
(68, 67, NULL, NULL, 'index', 133, 134),
(69, 67, NULL, NULL, 'view', 135, 136),
(70, 67, NULL, NULL, 'add', 137, 138),
(71, 67, NULL, NULL, 'edit', 139, 140),
(72, 67, NULL, NULL, 'delete', 141, 142),
(73, 1, NULL, NULL, 'Pages', 144, 147),
(74, 73, NULL, NULL, 'home', 145, 146),
(75, 1, NULL, NULL, 'Questions', 148, 159),
(76, 75, NULL, NULL, 'index', 149, 150),
(77, 75, NULL, NULL, 'view', 151, 152),
(78, 75, NULL, NULL, 'add', 153, 154),
(79, 75, NULL, NULL, 'edit', 155, 156),
(80, 75, NULL, NULL, 'delete', 157, 158),
(81, 1, NULL, NULL, 'Reviewers', 160, 171),
(82, 81, NULL, NULL, 'index', 161, 162),
(83, 81, NULL, NULL, 'view', 163, 164),
(84, 81, NULL, NULL, 'add', 165, 166),
(85, 81, NULL, NULL, 'edit', 167, 168),
(86, 81, NULL, NULL, 'delete', 169, 170),
(87, 1, NULL, NULL, 'Reviews', 172, 183),
(88, 87, NULL, NULL, 'index', 173, 174),
(89, 87, NULL, NULL, 'view', 175, 176),
(90, 87, NULL, NULL, 'add', 177, 178),
(91, 87, NULL, NULL, 'edit', 179, 180),
(92, 87, NULL, NULL, 'delete', 181, 182),
(93, 1, NULL, NULL, 'Students', 184, 195),
(94, 93, NULL, NULL, 'index', 185, 186),
(95, 93, NULL, NULL, 'view', 187, 188),
(96, 93, NULL, NULL, 'add', 189, 190),
(97, 93, NULL, NULL, 'studentEdit', 191, 192),
(98, 93, NULL, NULL, 'delete', 193, 194),
(99, 1, NULL, NULL, 'Theses', 196, 207),
(100, 99, NULL, NULL, 'index', 197, 198),
(101, 99, NULL, NULL, 'view', 199, 200),
(102, 99, NULL, NULL, 'add', 201, 202),
(103, 99, NULL, NULL, 'edit', 203, 204),
(104, 99, NULL, NULL, 'delete', 205, 206),
(105, 1, NULL, NULL, 'ThesisSupplements', 208, 213),
(106, 105, NULL, NULL, 'downloadFile', 209, 210),
(108, 1, NULL, NULL, 'ThesisTopics', 214, 219),
(109, 108, NULL, NULL, 'exportPdf', 215, 216),
(110, 108, NULL, NULL, 'encyptionRegulationDoc', 217, 218),
(111, 1, NULL, NULL, 'Users', 220, 235),
(112, 111, NULL, NULL, 'index', 221, 222),
(113, 111, NULL, NULL, 'view', 223, 224),
(114, 111, NULL, NULL, 'add', 225, 226),
(115, 111, NULL, NULL, 'edit', 227, 228),
(116, 111, NULL, NULL, 'delete', 229, 230),
(117, 111, NULL, NULL, 'login', 231, 232),
(118, 111, NULL, NULL, 'logout', 233, 234),
(119, 1, NULL, NULL, 'UsersReviewers', 236, 247),
(120, 119, NULL, NULL, 'index', 237, 238),
(121, 119, NULL, NULL, 'view', 239, 240),
(122, 119, NULL, NULL, 'add', 241, 242),
(123, 119, NULL, NULL, 'edit', 243, 244),
(124, 119, NULL, NULL, 'delete', 245, 246),
(125, 1, NULL, NULL, 'Admin', 248, 447),
(126, 125, NULL, NULL, 'ConsultationOccasions', 249, 258),
(127, 126, NULL, NULL, 'index', 250, 251),
(128, 126, NULL, NULL, 'add', 252, 253),
(129, 126, NULL, NULL, 'edit', 254, 255),
(130, 126, NULL, NULL, 'delete', 256, 257),
(131, 125, NULL, NULL, 'Consultations', 259, 268),
(132, 131, NULL, NULL, 'index', 260, 261),
(133, 131, NULL, NULL, 'add', 262, 263),
(134, 131, NULL, NULL, 'delete', 264, 265),
(135, 131, NULL, NULL, 'finalize', 266, 267),
(136, 125, NULL, NULL, 'CourseLevels', 269, 278),
(137, 136, NULL, NULL, 'index', 270, 271),
(138, 136, NULL, NULL, 'add', 272, 273),
(139, 136, NULL, NULL, 'edit', 274, 275),
(140, 136, NULL, NULL, 'delete', 276, 277),
(141, 125, NULL, NULL, 'CourseTypes', 279, 288),
(142, 141, NULL, NULL, 'index', 280, 281),
(143, 141, NULL, NULL, 'add', 282, 283),
(144, 141, NULL, NULL, 'edit', 284, 285),
(145, 141, NULL, NULL, 'delete', 286, 287),
(146, 125, NULL, NULL, 'Departments', 289, 298),
(147, 146, NULL, NULL, 'index', 290, 291),
(148, 146, NULL, NULL, 'add', 292, 293),
(149, 146, NULL, NULL, 'edit', 294, 295),
(150, 146, NULL, NULL, 'delete', 296, 297),
(151, 125, NULL, NULL, 'Documents', 299, 304),
(152, 151, NULL, NULL, 'index', 300, 301),
(153, 151, NULL, NULL, 'edit', 302, 303),
(154, 125, NULL, NULL, 'FinalExamSubjects', 305, 308),
(155, 154, NULL, NULL, 'details', 306, 307),
(156, 125, NULL, NULL, 'Information', 309, 314),
(157, 156, NULL, NULL, 'setFillingInPeriod', 310, 311),
(158, 156, NULL, NULL, 'setEncryptionRequlation', 312, 313),
(159, 125, NULL, NULL, 'InternalConsultants', 315, 326),
(160, 159, NULL, NULL, 'index', 316, 317),
(161, 159, NULL, NULL, 'details', 318, 319),
(162, 159, NULL, NULL, 'add', 320, 321),
(163, 159, NULL, NULL, 'edit', 322, 323),
(164, 159, NULL, NULL, 'delete', 324, 325),
(165, 125, NULL, NULL, 'OfferedTopics', 327, 336),
(166, 165, NULL, NULL, 'index', 328, 329),
(167, 165, NULL, NULL, 'edit', 330, 331),
(168, 165, NULL, NULL, 'delete', 332, 333),
(169, 165, NULL, NULL, 'details', 334, 335),
(170, 125, NULL, NULL, 'Reviewers', 337, 352),
(171, 170, NULL, NULL, 'index', 338, 339),
(172, 170, NULL, NULL, 'add', 340, 341),
(173, 170, NULL, NULL, 'edit', 342, 343),
(174, 170, NULL, NULL, 'details', 344, 345),
(175, 170, NULL, NULL, 'delete', 346, 347),
(176, 170, NULL, NULL, 'setReviewerSuggestion', 348, 349),
(177, 170, NULL, NULL, 'setReviewerForThesisTopic', 350, 351),
(178, 125, NULL, NULL, 'Reviews', 353, 370),
(179, 178, NULL, NULL, 'sendToReview', 354, 355),
(180, 178, NULL, NULL, 'sendToReviewAgain', 356, 357),
(181, 178, NULL, NULL, 'checkReview', 358, 359),
(182, 178, NULL, NULL, 'acceptReview', 360, 361),
(183, 178, NULL, NULL, 'getReviewDoc', 362, 363),
(184, 178, NULL, NULL, 'checkConfidentialityContract', 364, 365),
(185, 178, NULL, NULL, 'getUploadedConfidentialityContract', 366, 367),
(186, 178, NULL, NULL, 'review', 368, 369),
(187, 125, NULL, NULL, 'Students', 371, 380),
(188, 187, NULL, NULL, 'index', 372, 373),
(189, 187, NULL, NULL, 'edit', 374, 375),
(190, 187, NULL, NULL, 'details', 376, 377),
(191, 187, NULL, NULL, 'setPassedFinalExam', 378, 379),
(192, 125, NULL, NULL, 'ThesisSupplements', 381, 384),
(195, 192, NULL, NULL, 'delete', 382, 383),
(196, 125, NULL, NULL, 'ThesisTopics', 385, 424),
(197, 196, NULL, NULL, 'index', 386, 387),
(198, 196, NULL, NULL, 'details', 388, 389),
(199, 196, NULL, NULL, 'edit', 390, 391),
(200, 196, NULL, NULL, 'finalizeThesisTopic', 392, 393),
(201, 196, NULL, NULL, 'acceptBooking', 394, 395),
(202, 196, NULL, NULL, 'cancelBooking', 396, 397),
(203, 196, NULL, NULL, 'acceptThesisTopic', 398, 399),
(204, 196, NULL, NULL, 'proposalForAmendment', 400, 401),
(205, 196, NULL, NULL, 'setFirstThesisSubjectCompleted', 402, 403),
(206, 196, NULL, NULL, 'decideToContinueAfterFailedFirstThesisSubject', 404, 405),
(207, 196, NULL, NULL, 'uploadThesisSupplements', 406, 407),
(208, 196, NULL, NULL, 'finalizeUploadedThesisSupplements', 408, 409),
(209, 196, NULL, NULL, 'acceptThesisSupplements', 410, 411),
(210, 196, NULL, NULL, 'setThesisGrade', 412, 413),
(211, 196, NULL, NULL, 'applyAcceptedThesisData', 414, 415),
(212, 196, NULL, NULL, 'statistics', 416, 417),
(213, 196, NULL, NULL, 'exports', 418, 419),
(214, 196, NULL, NULL, 'exportCsv', 420, 421),
(215, 196, NULL, NULL, 'delete', 422, 423),
(216, 125, NULL, NULL, 'Users', 425, 436),
(217, 216, NULL, NULL, 'index', 426, 427),
(218, 216, NULL, NULL, 'add', 428, 429),
(219, 216, NULL, NULL, 'edit', 430, 431),
(220, 216, NULL, NULL, 'details', 432, 433),
(221, 216, NULL, NULL, 'delete', 434, 435),
(222, 1, NULL, NULL, 'InternalConsultant', 448, 539),
(223, 222, NULL, NULL, 'ConsultationOccasions', 449, 458),
(224, 223, NULL, NULL, 'index', 450, 451),
(225, 223, NULL, NULL, 'add', 452, 453),
(226, 223, NULL, NULL, 'edit', 454, 455),
(227, 223, NULL, NULL, 'delete', 456, 457),
(228, 222, NULL, NULL, 'Consultations', 459, 468),
(229, 228, NULL, NULL, 'index', 460, 461),
(230, 228, NULL, NULL, 'add', 462, 463),
(231, 228, NULL, NULL, 'delete', 464, 465),
(232, 228, NULL, NULL, 'finalize', 466, 467),
(233, 222, NULL, NULL, 'FinalExamSubjects', 469, 474),
(234, 233, NULL, NULL, 'index', 470, 471),
(235, 233, NULL, NULL, 'details', 472, 473),
(236, 222, NULL, NULL, 'Notifications', 475, 482),
(237, 236, NULL, NULL, 'index', 476, 477),
(238, 236, NULL, NULL, 'getNotification', 478, 479),
(239, 236, NULL, NULL, 'delete', 480, 481),
(240, 222, NULL, NULL, 'OfferedTopics', 483, 496),
(241, 240, NULL, NULL, 'index', 484, 485),
(242, 240, NULL, NULL, 'add', 486, 487),
(243, 240, NULL, NULL, 'edit', 488, 489),
(244, 240, NULL, NULL, 'delete', 490, 491),
(245, 240, NULL, NULL, 'details', 492, 493),
(246, 240, NULL, NULL, 'acceptBooking', 494, 495),
(247, 222, NULL, NULL, 'Pages', 497, 500),
(248, 247, NULL, NULL, 'dashboard', 498, 499),
(249, 222, NULL, NULL, 'Reviewers', 501, 512),
(250, 249, NULL, NULL, 'index', 502, 503),
(251, 249, NULL, NULL, 'add', 504, 505),
(252, 249, NULL, NULL, 'edit', 506, 507),
(253, 249, NULL, NULL, 'setReviewerSuggestion', 508, 509),
(254, 249, NULL, NULL, 'delete', 510, 511),
(255, 222, NULL, NULL, 'Reviews', 513, 516),
(256, 255, NULL, NULL, 'checkReview', 514, 515),
(257, 222, NULL, NULL, 'ThesisSupplements', 517, 522),
(258, 257, NULL, NULL, 'downloadFile', 518, 519),
(260, 222, NULL, NULL, 'ThesisTopics', 523, 538),
(261, 260, NULL, NULL, 'index', 524, 525),
(262, 260, NULL, NULL, 'delete', 526, 527),
(263, 260, NULL, NULL, 'details', 528, 529),
(264, 260, NULL, NULL, 'accept', 530, 531),
(265, 260, NULL, NULL, 'setFirstThesisSubjectCompleted', 532, 533),
(266, 260, NULL, NULL, 'setThesisGrade', 534, 535),
(267, 260, NULL, NULL, 'exportPdf', 536, 537),
(268, 1, NULL, NULL, 'HeadOfDepartment', 540, 583),
(269, 268, NULL, NULL, 'Notifications', 541, 548),
(270, 269, NULL, NULL, 'index', 542, 543),
(271, 269, NULL, NULL, 'getNotification', 544, 545),
(272, 269, NULL, NULL, 'delete', 546, 547),
(273, 268, NULL, NULL, 'Pages', 549, 552),
(274, 273, NULL, NULL, 'dashboard', 550, 551),
(275, 268, NULL, NULL, 'Reviewers', 553, 556),
(276, 275, NULL, NULL, 'setReviewerForThesisTopic', 554, 555),
(277, 268, NULL, NULL, 'Reviews', 557, 570),
(278, 277, NULL, NULL, 'sendToReview', 558, 559),
(279, 277, NULL, NULL, 'checkReview', 560, 561),
(280, 277, NULL, NULL, 'acceptReview', 562, 563),
(281, 277, NULL, NULL, 'getReviewDoc', 564, 565),
(282, 277, NULL, NULL, 'checkConfidentialityContract', 566, 567),
(283, 277, NULL, NULL, 'getUploadedConfidentialityContract', 568, 569),
(284, 268, NULL, NULL, 'ThesisTopics', 571, 582),
(285, 284, NULL, NULL, 'index', 572, 573),
(286, 284, NULL, NULL, 'accept', 574, 575),
(287, 284, NULL, NULL, 'details', 576, 577),
(288, 284, NULL, NULL, 'proposalForAmendment', 578, 579),
(289, 284, NULL, NULL, 'decideToContinueAfterFailedFirstThesisSubject', 580, 581),
(290, 1, NULL, NULL, 'TopicManager', 584, 617),
(291, 290, NULL, NULL, 'Information', 585, 590),
(292, 291, NULL, NULL, 'setFillingInPeriod', 586, 587),
(293, 291, NULL, NULL, 'setEncryptionRequlation', 588, 589),
(294, 290, NULL, NULL, 'Notifications', 591, 598),
(295, 294, NULL, NULL, 'index', 592, 593),
(296, 294, NULL, NULL, 'getNotification', 594, 595),
(297, 294, NULL, NULL, 'delete', 596, 597),
(298, 290, NULL, NULL, 'Pages', 599, 602),
(299, 298, NULL, NULL, 'dashboard', 600, 601),
(300, 290, NULL, NULL, 'ThesisTopics', 603, 616),
(301, 300, NULL, NULL, 'index', 604, 605),
(302, 300, NULL, NULL, 'accept', 606, 607),
(303, 300, NULL, NULL, 'details', 608, 609),
(304, 300, NULL, NULL, 'statistics', 610, 611),
(305, 300, NULL, NULL, 'exports', 612, 613),
(306, 300, NULL, NULL, 'exportCsv', 614, 615),
(307, 1, NULL, NULL, 'ThesisManager', 618, 647),
(308, 307, NULL, NULL, 'Notifications', 619, 626),
(309, 308, NULL, NULL, 'index', 620, 621),
(310, 308, NULL, NULL, 'getNotification', 622, 623),
(311, 308, NULL, NULL, 'delete', 624, 625),
(312, 307, NULL, NULL, 'Pages', 627, 630),
(313, 312, NULL, NULL, 'dashboard', 628, 629),
(314, 307, NULL, NULL, 'Reviews', 631, 636),
(315, 314, NULL, NULL, 'checkReview', 632, 633),
(316, 314, NULL, NULL, 'getReviewDoc', 634, 635),
(317, 307, NULL, NULL, 'ThesisTopics', 637, 646),
(318, 317, NULL, NULL, 'index', 638, 639),
(319, 317, NULL, NULL, 'details', 640, 641),
(320, 317, NULL, NULL, 'acceptThesisSupplements', 642, 643),
(321, 317, NULL, NULL, 'applyAcceptedThesisData', 644, 645),
(322, 1, NULL, NULL, 'Student', 648, 711),
(323, 322, NULL, NULL, 'FinalExamSubjects', 649, 654),
(324, 323, NULL, NULL, 'index', 650, 651),
(325, 323, NULL, NULL, 'finalize', 652, 653),
(326, 322, NULL, NULL, 'Notifications', 655, 662),
(327, 326, NULL, NULL, 'index', 656, 657),
(328, 326, NULL, NULL, 'getNotification', 658, 659),
(329, 326, NULL, NULL, 'delete', 660, 661),
(330, 322, NULL, NULL, 'OfferedTopics', 663, 670),
(331, 330, NULL, NULL, 'index', 664, 665),
(332, 330, NULL, NULL, 'details', 666, 667),
(333, 330, NULL, NULL, 'book', 668, 669),
(334, 322, NULL, NULL, 'Pages', 671, 674),
(335, 334, NULL, NULL, 'dashboard', 672, 673),
(336, 322, NULL, NULL, 'Reviews', 675, 678),
(337, 336, NULL, NULL, 'checkReview', 676, 677),
(338, 322, NULL, NULL, 'Students', 679, 682),
(339, 338, NULL, NULL, 'edit', 680, 681),
(340, 322, NULL, NULL, 'ThesisSupplements', 683, 690),
(341, 340, NULL, NULL, 'downloadFile', 684, 685),
(343, 340, NULL, NULL, 'delete', 686, 687),
(344, 322, NULL, NULL, 'ThesisTopics', 691, 710),
(345, 344, NULL, NULL, 'index', 692, 693),
(346, 344, NULL, NULL, 'add', 694, 695),
(347, 344, NULL, NULL, 'edit', 696, 697),
(348, 344, NULL, NULL, 'details', 698, 699),
(349, 344, NULL, NULL, 'finalizeThesisTopic', 700, 701),
(350, 344, NULL, NULL, 'uploadThesisSupplements', 702, 703),
(351, 344, NULL, NULL, 'finalizeUploadedThesisSupplements', 704, 705),
(352, 344, NULL, NULL, 'cancelBooking', 706, 707),
(353, 344, NULL, NULL, 'exportPdf', 708, 709),
(354, 1, NULL, NULL, 'Reviewer', 712, 757),
(355, 354, NULL, NULL, 'Notifications', 713, 720),
(356, 355, NULL, NULL, 'index', 714, 715),
(357, 355, NULL, NULL, 'getNotification', 716, 717),
(358, 355, NULL, NULL, 'delete', 718, 719),
(359, 354, NULL, NULL, 'Pages', 721, 724),
(360, 359, NULL, NULL, 'dashboard', 722, 723),
(361, 354, NULL, NULL, 'Reviews', 725, 744),
(362, 361, NULL, NULL, 'review', 726, 727),
(363, 361, NULL, NULL, 'uploadReviewDoc', 728, 729),
(364, 361, NULL, NULL, 'finalizeUploadedReviewDoc', 730, 731),
(365, 361, NULL, NULL, 'getReviewDoc', 732, 733),
(366, 361, NULL, NULL, 'uploadConfidentialityContract', 734, 735),
(367, 361, NULL, NULL, 'finalizeConfidentialityContractUpload', 736, 737),
(368, 361, NULL, NULL, 'getUploadedConfidentialityContract', 738, 739),
(369, 361, NULL, NULL, 'reviewDoc', 740, 741),
(370, 361, NULL, NULL, 'confidentialityContractDoc', 742, 743),
(371, 354, NULL, NULL, 'ThesisSupplements', 745, 750),
(372, 371, NULL, NULL, 'downloadFile', 746, 747),
(374, 354, NULL, NULL, 'ThesisTopics', 751, 756),
(375, 374, NULL, NULL, 'index', 752, 753),
(376, 374, NULL, NULL, 'details', 754, 755),
(377, 1, NULL, NULL, 'FinalExamOrganizer', 758, 779),
(378, 377, NULL, NULL, 'Notifications', 759, 766),
(379, 378, NULL, NULL, 'index', 760, 761),
(380, 378, NULL, NULL, 'getNotification', 762, 763),
(381, 378, NULL, NULL, 'delete', 764, 765),
(382, 377, NULL, NULL, 'Pages', 767, 770),
(383, 382, NULL, NULL, 'dashboard', 768, 769),
(384, 377, NULL, NULL, 'Students', 771, 778),
(385, 384, NULL, NULL, 'index', 772, 773),
(386, 384, NULL, NULL, 'details', 774, 775),
(387, 384, NULL, NULL, 'setPassedFinalExam', 776, 777),
(388, 1, NULL, NULL, 'Acl', 780, 781),
(389, 1, NULL, NULL, 'Ajax', 782, 783),
(390, 1, NULL, NULL, 'Bake', 784, 785),
(391, 1, NULL, NULL, 'CakePdf', 786, 787),
(392, 1, NULL, NULL, 'CsvView', 788, 789),
(393, 1, NULL, NULL, 'DebugKit', 790, 817),
(394, 393, NULL, NULL, 'Composer', 791, 794),
(395, 394, NULL, NULL, 'checkDependencies', 792, 793),
(396, 393, NULL, NULL, 'MailPreview', 795, 802),
(397, 396, NULL, NULL, 'index', 796, 797),
(398, 396, NULL, NULL, 'sent', 798, 799),
(399, 396, NULL, NULL, 'email', 800, 801),
(400, 393, NULL, NULL, 'Panels', 803, 808),
(401, 400, NULL, NULL, 'index', 804, 805),
(402, 400, NULL, NULL, 'view', 806, 807),
(403, 393, NULL, NULL, 'Requests', 809, 812),
(404, 403, NULL, NULL, 'view', 810, 811),
(405, 393, NULL, NULL, 'Toolbar', 813, 816),
(406, 405, NULL, NULL, 'clearCache', 814, 815),
(407, 1, NULL, NULL, 'Josegonzalez\\Upload', 818, 819),
(408, 1, NULL, NULL, 'Migrations', 820, 821),
(409, 1, NULL, NULL, 'WyriHaximus\\TwigView', 822, 823),
(410, 105, NULL, NULL, 'downloadSupplementsInZip', 211, 212),
(411, 257, NULL, NULL, 'downloadSupplementsInZip', 520, 521),
(412, 340, NULL, NULL, 'downloadSupplementsInZip', 688, 689),
(413, 371, NULL, NULL, 'downloadSupplementsInZip', 748, 749),
(414, 125, NULL, NULL, 'Years', 437, 446),
(415, 414, NULL, NULL, 'index', 438, 439),
(416, 414, NULL, NULL, 'add', 440, 441),
(417, 414, NULL, NULL, 'edit', 442, 443),
(418, 414, NULL, NULL, 'delete', 444, 445);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `aros`
--

DROP TABLE IF EXISTS `aros`;
CREATE TABLE `aros` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Groups', 1, NULL, 1, 4),
(2, NULL, 'Groups', 2, NULL, 5, 6),
(3, NULL, 'Groups', 3, NULL, 7, 8),
(4, NULL, 'Groups', 4, NULL, 9, 10),
(5, NULL, 'Groups', 5, NULL, 11, 12),
(6, NULL, 'Groups', 6, NULL, 13, 14),
(7, NULL, 'Groups', 7, NULL, 15, 16),
(8, NULL, 'Groups', 8, NULL, 17, 18),
(9, 1, 'Users', 1, NULL, 2, 3);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE `aros_acos` (
  `id` int(11) NOT NULL,
  `aro_id` int(11) NOT NULL,
  `aco_id` int(11) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '-1', '-1', '-1', '-1'),
(2, 1, 125, '1', '1', '1', '1'),
(3, 1, 106, '1', '1', '1', '1'),
(4, 1, 410, '1', '1', '1', '1'),
(5, 1, 109, '1', '1', '1', '1'),
(6, 1, 110, '1', '1', '1', '1'),
(7, 1, 35, '1', '1', '1', '1'),
(8, 2, 1, '-1', '-1', '-1', '-1'),
(9, 2, 222, '1', '1', '1', '1'),
(10, 3, 1, '-1', '-1', '-1', '-1'),
(11, 3, 268, '1', '1', '1', '1'),
(12, 3, 106, '1', '1', '1', '1'),
(13, 3, 410, '1', '1', '1', '1'),
(14, 3, 109, '1', '1', '1', '1'),
(15, 4, 1, '-1', '-1', '-1', '-1'),
(16, 4, 290, '1', '1', '1', '1'),
(17, 4, 109, '1', '1', '1', '1'),
(18, 5, 1, '-1', '-1', '-1', '-1'),
(19, 5, 307, '1', '1', '1', '1'),
(20, 5, 106, '1', '1', '1', '1'),
(21, 5, 410, '1', '1', '1', '1'),
(22, 5, 109, '1', '1', '1', '1'),
(23, 1, 38, '1', '1', '1', '1'),
(24, 6, 1, '-1', '-1', '-1', '-1'),
(25, 6, 322, '1', '1', '1', '1'),
(26, 6, 110, '1', '1', '1', '1'),
(27, 6, 38, '1', '1', '1', '1'),
(28, 7, 1, '-1', '-1', '-1', '-1'),
(29, 7, 354, '1', '1', '1', '1'),
(30, 7, 35, '1', '1', '1', '1'),
(31, 8, 1, '-1', '-1', '-1', '-1'),
(32, 8, 377, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `consultations`
--

DROP TABLE IF EXISTS `consultations`;
CREATE TABLE `consultations` (
  `id` int(10) UNSIGNED NOT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  `current` tinyint(1) DEFAULT '1',
  `thesis_topic_id` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `consultation_occasions`
--

DROP TABLE IF EXISTS `consultation_occasions`;
CREATE TABLE `consultation_occasions` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `activity` text,
  `consultation_id` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `courses`
--

INSERT INTO `courses` (`id`, `name`) VALUES
(1, 'Mérnökinformatikus'),
(2, 'Gazdaságinformatikus');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `course_levels`
--

DROP TABLE IF EXISTS `course_levels`;
CREATE TABLE `course_levels` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `course_levels`
--

INSERT INTO `course_levels` (`id`, `name`) VALUES
(1, 'BSc'),
(2, 'MSc'),
(3, 'Régi egyetemi vagy főiskolai');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `course_types`
--

DROP TABLE IF EXISTS `course_types`;
CREATE TABLE `course_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `course_types`
--

INSERT INTO `course_types` (`id`, `name`) VALUES
(1, 'Nappali'),
(2, 'Levelező');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `head_of_department` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `departments`
--

INSERT INTO `departments` (`id`, `name`, `head_of_department`) VALUES
(1, 'Informatika Tanszék', 'Dr. Hatwágner F. Miklós');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `documents`
--

INSERT INTO `documents` (`id`, `name`, `file`, `created`, `modified`) VALUES
(1, 'Útmutató szakdolgozat bírálathoz', 'utmutato_szakdolgozat_biralatahoz.pdf', '2019-03-08 18:16:00', '2019-04-01 00:00:00'),
(2, 'Útmutató diplomamunka bírálathoz', 'utmutato_diplomamunka_biralatahoz-1.pdf', '2019-03-08 18:16:00', '2019-04-18 17:51:35');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `final_exam_subjects`
--

DROP TABLE IF EXISTS `final_exam_subjects`;
CREATE TABLE `final_exam_subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `teachers` text,
  `semester` tinyint(1) DEFAULT NULL,
  `year_id` int(10) UNSIGNED DEFAULT NULL,
  `student_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Belső konzulens'),
(3, 'Tanszékvezető'),
(4, 'Témakezelő'),
(5, 'Szakdolgozatkezelő'),
(6, 'Hallgató'),
(7, 'Bíráló'),
(8, 'Záróvizsga beosztást összeállító');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `information`
--

DROP TABLE IF EXISTS `information`;
CREATE TABLE `information` (
  `id` int(10) UNSIGNED NOT NULL,
  `filling_in_topic_form_begin_date` date DEFAULT NULL,
  `filling_in_topic_form_end_date` date DEFAULT NULL,
  `encryption_requlation` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `information`
--

INSERT INTO `information` (`id`, `filling_in_topic_form_begin_date`, `filling_in_topic_form_end_date`, `encryption_requlation`) VALUES
(1, '2019-01-03', '2019-04-18', '(1) A külső konzulens cége, gazdasági társasága, intézménye (továbbiakban: partner-intézmény) a téma kiírásával egy időben a saját formátumának megfelelő formában (maximum 1 oldalas), vagy a 3. melléklet használatával kérheti a diplomamunka titkossá tételét. A titkosítás maximális ideje 5 év.\r\n(2) A titkosítási kérelmet a diplomamunka elejébe be kell kötni, valamint elektronikusan is fel kell tölteni a diplomamunka mellé.\r\n(3) Titkosnak minősített diplomamunka szövegéhez csak a 4. melléklet szerinti „Titoktartási nyilatkozat” aláírása után férhet hozzá a belső konzulens, a bíráló és a védés valamennyi résztvevője. A nyilatkozatok eredeti példányait a tanszékek a feladatkiíró-lappal együtt tárolják.\r\n(4) A záróvizsga után a titkos diplomamunkákat a záróvizsgát szervező tanszékek visszaadják a hallgatóknak, az elektronikusan tárolt változatot a többi diplomamunkától elkülönítve őrzik a titoktartási időszak lejártáig. A diplomamunka ezután átkerül a nem titkosan őrzött dolgozatok közé.\r\n(5) Alaposan indokolt esetben (pl. a diplomamunka plágiumgyanú miatti hivatalos vizsgálata) a dékán eseti feloldást adhat a titkosság alól, mely csak az ügyben közvetlenül érintettekre vonatkozik, akik szintén titoktartási nyilatkozatot írnak alá. Erről a partner-intézmény hivatalos másolatot kap.');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `internal_consultants`
--

DROP TABLE IF EXISTS `internal_consultants`;
CREATE TABLE `internal_consultants` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `internal_consultant_position_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `internal_consultant_positions`
--

DROP TABLE IF EXISTS `internal_consultant_positions`;
CREATE TABLE `internal_consultant_positions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `internal_consultant_positions`
--

INSERT INTO `internal_consultant_positions` (`id`, `name`) VALUES
(1, 'Tanszéki mérnök'),
(2, 'Egyetemi tanársegéd'),
(3, 'Egyetemi docens'),
(4, 'Egyetemi adjunktus'),
(5, 'Egyetemi tanár'),
(6, 'mb. Tanszékvezető'),
(7, 'Tanszékvezető'),
(8, 'Professor emeritus');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(1, 'magyar'),
(2, 'angol'),
(3, 'német');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `unread` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `offered_topics`
--

DROP TABLE IF EXISTS `offered_topics`;
CREATE TABLE `offered_topics` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `confidential` tinyint(1) DEFAULT NULL,
  `is_thesis` tinyint(1) DEFAULT NULL,
  `has_external_consultant` tinyint(1) DEFAULT NULL,
  `external_consultant_name` varchar(50) DEFAULT NULL,
  `external_consultant_workplace` varchar(50) DEFAULT NULL,
  `external_consultant_position` varchar(50) DEFAULT NULL,
  `external_consultant_email` varchar(60) DEFAULT NULL,
  `external_consultant_phone_number` varchar(50) DEFAULT NULL,
  `external_consultant_address` varchar(80) DEFAULT NULL,
  `internal_consultant_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `question` text,
  `review_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `raw_passwords`
--

DROP TABLE IF EXISTS `raw_passwords`;
CREATE TABLE `raw_passwords` (
  `id` int(10) UNSIGNED NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reviewers`
--

DROP TABLE IF EXISTS `reviewers`;
CREATE TABLE `reviewers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `workplace` varchar(50) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `structure_and_style_point` tinyint(3) UNSIGNED DEFAULT NULL,
  `cause_of_structure_and_style_point` text,
  `processing_literature_point` tinyint(3) UNSIGNED DEFAULT NULL,
  `cause_of_processing_literature_point` text,
  `writing_up_the_topic_point` tinyint(3) UNSIGNED DEFAULT NULL,
  `cause_of_writing_up_the_topic_point` text,
  `practical_applicability_point` tinyint(3) UNSIGNED DEFAULT NULL,
  `cause_of_practical_applicability_point` text,
  `general_comments` text,
  `cause_of_rejecting_review` text,
  `review_doc` varchar(255) DEFAULT NULL,
  `confidentiality_contract` varchar(255) DEFAULT NULL,
  `confidentiality_contract_status` tinyint(4) DEFAULT NULL,
  `cause_of_rejecting_confidentiality_contract` text,
  `review_status` tinyint(4) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `thesis_topic_id` int(10) UNSIGNED DEFAULT NULL,
  `reviewer_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `address` varchar(80) DEFAULT NULL,
  `neptun` varchar(6) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `specialisation` varchar(40) DEFAULT NULL,
  `final_exam_subjects_status` tinyint(4) DEFAULT NULL,
  `passed_final_exam` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `course_id` int(10) UNSIGNED DEFAULT NULL,
  `course_level_id` int(10) UNSIGNED DEFAULT NULL,
  `course_type_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `final_exam_subjects_internal_consultant_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `thesis_supplements`
--

DROP TABLE IF EXISTS `thesis_supplements`;
CREATE TABLE `thesis_supplements` (
  `id` int(10) UNSIGNED NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `thesis_topic_id` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `thesis_topics`
--

DROP TABLE IF EXISTS `thesis_topics`;
CREATE TABLE `thesis_topics` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `is_thesis` tinyint(1) DEFAULT '0',
  `confidential` tinyint(1) DEFAULT NULL,
  `starting_year_id` int(10) UNSIGNED DEFAULT NULL,
  `starting_semester` tinyint(1) DEFAULT NULL,
  `expected_ending_year_id` int(10) UNSIGNED DEFAULT NULL,
  `expected_ending_semester` tinyint(1) DEFAULT NULL,
  `cause_of_no_external_consultant` text,
  `external_consultant_name` varchar(50) DEFAULT NULL,
  `external_consultant_workplace` varchar(50) DEFAULT NULL,
  `external_consultant_position` varchar(50) DEFAULT NULL,
  `external_consultant_email` varchar(60) DEFAULT NULL,
  `external_consultant_phone_number` varchar(60) DEFAULT NULL,
  `external_consultant_address` varchar(80) DEFAULT NULL,
  `handed_in_date` date DEFAULT NULL,
  `proposal_for_amendment` text,
  `accepted_thesis_data_applyed_to_neptun` tinyint(1) DEFAULT NULL,
  `internal_consultant_grade` tinyint(4) DEFAULT NULL,
  `first_thesis_subject_failed_suggestion` text,
  `cause_of_rejecting_thesis_supplements` text,
  `deleted` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `internal_consultant_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `student_id` int(10) UNSIGNED DEFAULT NULL,
  `thesis_topic_status_id` int(10) UNSIGNED DEFAULT NULL,
  `offered_topic_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `thesis_topic_statuses`
--

DROP TABLE IF EXISTS `thesis_topic_statuses`;
CREATE TABLE `thesis_topic_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `thesis_topic_statuses`
--

INSERT INTO `thesis_topic_statuses` (`id`, `name`) VALUES
(1, 'A téma a hallgató véglegesítésére vár.'),
(2, 'A témafoglalás belső konzulens elfogadására vár.'),
(3, 'A témafoglalást a belső konzulens elutasította.'),
(4, 'A témafoglalást a belső konzulens elfogadta, a hallgató véglegesítésére vár.'),
(5, 'A hallgató visszavonta a témafoglalást.'),
(6, 'A téma a belső konzulens döntésére vár.'),
(7, 'Téma elutasítva (belső konzulens).'),
(8, 'A téma a tanszékvezető döntésére vár.'),
(9, 'A téma elutasítva (tanszékvezető).'),
(10, 'A témához a tanszékvezető módosítási javaslatot adott.'),
(11, 'A téma a külső konzulens aláírásának ellenőrzésére vár.'),
(12, 'Téma elutasítva (külső konzulens).'),
(13, 'Téma elfogadva.'),
(14, 'Első diplomakurzus sikertelen, a folytatás tanszékvezető döntésére vár.'),
(15, 'Téma elutasítva (első diplomakurzus sikertelen).'),
(16, 'Első diplomakurzus teljesítve.'),
(17, 'A szakdolgozat/diplomamunka a formai követelményeknek megfelelt, mellékletek feltölthetőek.'),
(18, 'Szakdolgozat/Diplomamunka mellékletek feltöltve, hallgató véglegesítésére vár.'),
(19, 'Szakdolgozat/Diplomamunka mellékletek feltöltve, ellenőrzésre vár.'),
(20, 'Szakdolgozat/Diplomamunka mellékletek elutasítva.'),
(21, 'Szakdolgozat/Diplomamunka mellékletek elfogadva, bíráló kijelölésére vár.'),
(22, 'A dolgozat bírálója kijelölve, tanszékvezető ellenőrzésére vár.'),
(23, 'A dolgozat bírálója kijelölve, bírálatra küldésre vár.'),
(24, 'A dolgozat bírálat alatt.'),
(25, 'A dolgozat bírálva.'),
(26, 'A dolgozat elfogadva.');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `group_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created`, `modified`, `group_id`) VALUES
(1, 'admin@admin.hu', '$2y$10$j5J8zg2TsGkq/IxxbI3Jf.0k590L6I43kMfI0yvSBrxKHtub3A9Sa', '2019-04-20 10:15:52', '2019-04-20 10:15:52', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `years`
--

DROP TABLE IF EXISTS `years`;
CREATE TABLE `years` (
  `id` int(10) UNSIGNED NOT NULL,
  `year` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `years`
--

INSERT INTO `years` (`id`, `year`) VALUES
(1, '2017/18'),
(2, '2018/19'),
(3, '2019/20'),
(4, '2020/21'),
(5, '2021/22'),
(6, '2022/23'),
(7, '2023/24'),
(8, '2024/25'),
(9, '2025/26'),
(10, '2026/27');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `acl_phinxlog`
--
ALTER TABLE `acl_phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- A tábla indexei `acos`
--
ALTER TABLE `acos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lft` (`lft`,`rght`),
  ADD KEY `alias` (`alias`);

--
-- A tábla indexei `aros`
--
ALTER TABLE `aros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lft` (`lft`,`rght`),
  ADD KEY `alias` (`alias`);

--
-- A tábla indexei `aros_acos`
--
ALTER TABLE `aros_acos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aro_id` (`aro_id`,`aco_id`),
  ADD KEY `aco_id` (`aco_id`);

--
-- A tábla indexei `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_consultations_thesis_topics_idx` (`thesis_topic_id`);

--
-- A tábla indexei `consultation_occasions`
--
ALTER TABLE `consultation_occasions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_consultation_occasions_cosultations_idx` (`consultation_id`);

--
-- A tábla indexei `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `course_levels`
--
ALTER TABLE `course_levels`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `course_types`
--
ALTER TABLE `course_types`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `final_exam_subjects`
--
ALTER TABLE `final_exam_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_final_exam_subjects_students_idx` (`student_id`),
  ADD KEY `FK_final_exam_subjects_years_idx` (`year_id`);

--
-- A tábla indexei `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `internal_consultants`
--
ALTER TABLE `internal_consultants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_internal_consultants_departments_idx` (`department_id`),
  ADD KEY `FK_internal_consultants_users_idx` (`user_id`),
  ADD KEY `FK_internal_consultants_internal_consultant_positions_idx` (`internal_consultant_position_id`);

--
-- A tábla indexei `internal_consultant_positions`
--
ALTER TABLE `internal_consultant_positions`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_notifications_users_idx` (`user_id`);

--
-- A tábla indexei `offered_topics`
--
ALTER TABLE `offered_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_offered_topics_internal_consultants_idx` (`internal_consultant_id`),
  ADD KEY `FK_offered_topics_languages_idx` (`language_id`);

--
-- A tábla indexei `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_questions_reviews_idx` (`review_id`);

--
-- A tábla indexei `raw_passwords`
--
ALTER TABLE `raw_passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_raw_passwords_users_idx` (`user_id`);

--
-- A tábla indexei `reviewers`
--
ALTER TABLE `reviewers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_reviewers_users_idx` (`user_id`);

--
-- A tábla indexei `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_reviews_reviewers_idx` (`reviewer_id`),
  ADD KEY `FK_reviews_thesis_topics_idx` (`thesis_topic_id`);

--
-- A tábla indexei `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `neptun_UNIQUE` (`neptun`),
  ADD KEY `FK_studens_courses_idx` (`course_id`),
  ADD KEY `FK_studens_course_types_idx` (`course_type_id`),
  ADD KEY `FK_studens_course_levels_idx` (`course_level_id`),
  ADD KEY `FK_studens_users_idx` (`user_id`),
  ADD KEY `FK_students_final_exam_internal_consultants_idx` (`final_exam_subjects_internal_consultant_id`);

--
-- A tábla indexei `thesis_supplements`
--
ALTER TABLE `thesis_supplements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_thesis_supplements_thesis_topics_idx` (`thesis_topic_id`);

--
-- A tábla indexei `thesis_topics`
--
ALTER TABLE `thesis_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_degree_thesis_data_internal_constultants_idx` (`internal_consultant_id`),
  ADD KEY `FK_thesis_topics_years_idx` (`starting_year_id`),
  ADD KEY `FK_thesis_topics_students_idx` (`student_id`),
  ADD KEY `FK_thesis_topics_ending_year_idx` (`expected_ending_year_id`),
  ADD KEY `FK_thesis_topics_languages_idx` (`language_id`),
  ADD KEY `FK_thesis_topics_thesis_topic_statuses_idx` (`thesis_topic_status_id`),
  ADD KEY `FK_thesis_topics_offered_topics_idx` (`offered_topic_id`);

--
-- A tábla indexei `thesis_topic_statuses`
--
ALTER TABLE `thesis_topic_statuses`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_users_groups_idx` (`group_id`);

--
-- A tábla indexei `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `acos`
--
ALTER TABLE `acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=419;
--
-- AUTO_INCREMENT a táblához `aros`
--
ALTER TABLE `aros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT a táblához `aros_acos`
--
ALTER TABLE `aros_acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT a táblához `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `consultation_occasions`
--
ALTER TABLE `consultation_occasions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT a táblához `course_levels`
--
ALTER TABLE `course_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT a táblához `course_types`
--
ALTER TABLE `course_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT a táblához `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT a táblához `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT a táblához `final_exam_subjects`
--
ALTER TABLE `final_exam_subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT a táblához `information`
--
ALTER TABLE `information`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT a táblához `internal_consultants`
--
ALTER TABLE `internal_consultants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT a táblához `internal_consultant_positions`
--
ALTER TABLE `internal_consultant_positions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT a táblához `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT a táblához `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `offered_topics`
--
ALTER TABLE `offered_topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT a táblához `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `raw_passwords`
--
ALTER TABLE `raw_passwords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `reviewers`
--
ALTER TABLE `reviewers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT a táblához `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `thesis_supplements`
--
ALTER TABLE `thesis_supplements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `thesis_topics`
--
ALTER TABLE `thesis_topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `thesis_topic_statuses`
--
ALTER TABLE `thesis_topic_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT a táblához `years`
--
ALTER TABLE `years`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `FK_consultations_thesis_topics` FOREIGN KEY (`thesis_topic_id`) REFERENCES `thesis_topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `consultation_occasions`
--
ALTER TABLE `consultation_occasions`
  ADD CONSTRAINT `FK_consultation_occasions_cosultations` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `final_exam_subjects`
--
ALTER TABLE `final_exam_subjects`
  ADD CONSTRAINT `FK_final_exam_subjects_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_final_exam_subjects_years` FOREIGN KEY (`year_id`) REFERENCES `years` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `internal_consultants`
--
ALTER TABLE `internal_consultants`
  ADD CONSTRAINT `FK_internal_consultants_departments` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_internal_consultants_internal_consultant_positions` FOREIGN KEY (`internal_consultant_position_id`) REFERENCES `internal_consultant_positions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_internal_consultants_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `FK_notifications_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `offered_topics`
--
ALTER TABLE `offered_topics`
  ADD CONSTRAINT `FK_offered_topics_internal_consultants` FOREIGN KEY (`internal_consultant_id`) REFERENCES `internal_consultants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_offered_topics_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `FK_questions_reviews` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `raw_passwords`
--
ALTER TABLE `raw_passwords`
  ADD CONSTRAINT `FK_raw_passwords_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `reviewers`
--
ALTER TABLE `reviewers`
  ADD CONSTRAINT `FK_reviewers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_reviews_reviewers` FOREIGN KEY (`reviewer_id`) REFERENCES `reviewers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_reviews_thesis_topics` FOREIGN KEY (`thesis_topic_id`) REFERENCES `thesis_topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `FK_studens_course_levels` FOREIGN KEY (`course_level_id`) REFERENCES `course_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_studens_course_types` FOREIGN KEY (`course_type_id`) REFERENCES `course_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_studens_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_studens_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_students_final_exam_internal_consultants` FOREIGN KEY (`final_exam_subjects_internal_consultant_id`) REFERENCES `internal_consultants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `thesis_supplements`
--
ALTER TABLE `thesis_supplements`
  ADD CONSTRAINT `FK_thesis_supplements_thesis_topics` FOREIGN KEY (`thesis_topic_id`) REFERENCES `thesis_topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `thesis_topics`
--
ALTER TABLE `thesis_topics`
  ADD CONSTRAINT `FK_thesis_topics_ending_year` FOREIGN KEY (`expected_ending_year_id`) REFERENCES `years` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_thesis_topics_internal_constultants` FOREIGN KEY (`internal_consultant_id`) REFERENCES `internal_consultants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_thesis_topics_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_thesis_topics_offered_topics` FOREIGN KEY (`offered_topic_id`) REFERENCES `offered_topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_thesis_topics_starting_year` FOREIGN KEY (`starting_year_id`) REFERENCES `years` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_thesis_topics_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_thesis_topics_thesis_topic_statuses` FOREIGN KEY (`thesis_topic_status_id`) REFERENCES `thesis_topic_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Megkötések a táblához `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
