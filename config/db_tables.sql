-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2019. Ápr 05. 16:34
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
(1, 'Útmutató szakdolgozat bírálathoz', 'utmutato_szakdolgozat_biralatahoz.pdf', '2019-03-08 18:16:00', NULL),
(2, 'Útmutató diplomamunka bírálathoz', 'utmutato_diplomamunka_biralatahoz.pdf', '2019-03-08 18:16:00', NULL);

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
(1, '2019-01-03', '2019-04-17', '(1) A külső konzulens cége, gazdasági társasága, intézménye (továbbiakban: partner-intézmény) a téma kiírásával egy időben a saját formátumának megfelelő formában (maximum 1 oldalas), vagy a 3. melléklet használatával kérheti a diplomamunka titkossá tételét. A titkosítás maximális ideje 5 év.\r\n(2) A titkosítási kérelmet a diplomamunka elejébe be kell kötni, valamint elektronikusan is fel kell tölteni a diplomamunka mellé.\r\n(3) Titkosnak minősített diplomamunka szövegéhez csak a 4. melléklet szerinti „Titoktartási nyilatkozat” aláírása után férhet hozzá a belső konzulens, a bíráló és a védés valamennyi résztvevője. A nyilatkozatok eredeti példányait a tanszékek a feladatkiíró-lappal együtt tárolják.\r\n(4) A záróvizsga után a titkos diplomamunkákat a záróvizsgát szervező tanszékek visszaadják a hallgatóknak, az elektronikusan tárolt változatot a többi diplomamunkától elkülönítve őrzik a titoktartási időszak lejártáig. A diplomamunka ezután átkerül a nem titkosan őrzött dolgozatok közé.\r\n(5) Alaposan indokolt esetben (pl. a diplomamunka plágiumgyanú miatti hivatalos vizsgálata) a dékán eseti feloldást adhat a titkosság alól, mely csak az ügyben közvetlenül érintettekre vonatkozik, akik szintén titoktartási nyilatkozatot írnak alá. Erről a partner-intézmény hivatalos másolatot kap.');

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
(32, 'admin@admin.hu', '$2y$10$bUCXm8mzvqbK7qsNIe1tzO3sLq35kpKkYjCNJUyxyNOrP6IhZe6Xm', '2019-04-05 16:29:48', '2019-04-05 16:29:48', 1);

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
(1, '2018/19'),
(2, '2019/20'),
(3, '2020/21'),
(4, '2021/22'),
(5, '2022/23'),
(6, '2023/24'),
(7, '2024/25'),
(8, '2025/26'),
(9, '2026/27'),
(10, '2027/28');

--
-- Indexek a kiírt táblákhoz
--

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;
--
-- AUTO_INCREMENT a táblához `offered_topics`
--
ALTER TABLE `offered_topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT a táblához `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `raw_passwords`
--
ALTER TABLE `raw_passwords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT a táblához `reviewers`
--
ALTER TABLE `reviewers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT a táblához `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT a táblához `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT a táblához `thesis_supplements`
--
ALTER TABLE `thesis_supplements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT a táblához `thesis_topics`
--
ALTER TABLE `thesis_topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT a táblához `thesis_topic_statuses`
--
ALTER TABLE `thesis_topic_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT a táblához `years`
--
ALTER TABLE `years`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
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
