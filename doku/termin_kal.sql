-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Aug 2020 um 19:55
-- Server-Version: 10.1.38-MariaDB
-- PHP-Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `termin_kal`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kalender`
--

CREATE TABLE `kalender` (
  `id` int(11) NOT NULL,
  `anfang` datetime NOT NULL,
  `ende` datetime NOT NULL,
  `ganztag` tinyint(1) NOT NULL DEFAULT '1',
  `titel` varchar(65) COLLATE utf8_bin NOT NULL,
  `beschreibung` text COLLATE utf8_bin NOT NULL,
  `ort` varchar(65) COLLATE utf8_bin NOT NULL,
  `kategorie` varchar(30) COLLATE utf8_bin NOT NULL,
  `farbe` varchar(6) COLLATE utf8_bin NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `kalender`
--

INSERT INTO `kalender` (`id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `kategorie`, `farbe`) VALUES
(1, '2020-07-13 14:00:00', '2020-07-13 18:00:00', 0, 'Erster Testtermin', 'Das ist der Erste Testtermin, der direkt in phpmyadmin eingetragen wurde', 'da wo ich wohne', 'privat', '9DDF20'),
(8, '2020-07-15 07:00:00', '2020-07-15 09:00:00', 0, 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', 'privat', '0'),
(12, '2020-07-15 07:00:00', '2020-07-15 09:00:00', 0, 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', 'privat', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `name` varchar(65) COLLATE utf8_bin NOT NULL,
  `farbe` char(7) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `kategorie`
--

INSERT INTO `kategorie` (`id`, `name`, `farbe`) VALUES
(1, 'Privat', '#9DDF20'),
(2, 'Uni', '#5882FA'),
(3, 'Arbeit', '#FFBF00'),
(4, 'Hobby', '#FF4000'),
(5, 'Kat 5', '#FFFF00'),
(6, 'Kat 6', '#00FFFF'),
(7, 'Kat 7', '#0040FF'),
(8, 'Kat 8', '#5F04B4');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE `termine` (
  `id` int(11) NOT NULL,
  `anfang` datetime NOT NULL,
  `ende` datetime NOT NULL,
  `ganztag` tinyint(1) NOT NULL DEFAULT '0',
  `titel` varchar(65) COLLATE utf8_bin NOT NULL,
  `beschreibung` text COLLATE utf8_bin,
  `ort` varchar(65) COLLATE utf8_bin DEFAULT NULL,
  `gruppe` char(32) COLLATE utf8_bin DEFAULT NULL,
  `kategorieid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`id`, `anfang`, `ende`, `ganztag`, `titel`, `beschreibung`, `ort`, `gruppe`, `kategorieid`) VALUES
(1, '2020-08-15 07:00:00', '2020-08-15 09:00:00', 0, 'Zweiter Testtermin', 'Das ist der zweite Testtermin, der über die php-funktion eingetragen wurde', 'da wo ich wohne', NULL, 1),
(2, '2020-08-02 19:32:00', '2020-08-02 19:32:00', 0, 'testtitel', 'sdfsdf', 'sdfsdfs', NULL, 2);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `kalender`
--
ALTER TABLE `kalender`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `termine`
--
ALTER TABLE `termine`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `kalender`
--
ALTER TABLE `kalender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `termine`
--
ALTER TABLE `termine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
