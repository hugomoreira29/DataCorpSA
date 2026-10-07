-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Nov 2022 um 11:00
-- Server-Version: 10.4.25-MariaDB
-- PHP-Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `repif_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dürfen`
--

CREATE TABLE `dürfen` (
  `IDGruppe` int(11) NOT NULL,
  `IDrule` int(11) NOT NULL,
  `Datum` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dürfen`
--

INSERT INTO `dürfen` (`IDGruppe`, `IDrule`, `Datum`) VALUES
(1, 3, '2022-11-17'),
(1, 5, '2022-11-17'),
(2, 2, '2022-11-17'),
(2, 3, '2022-11-17'),
(3, 3, '2022-11-17'),
(3, 4, '2022-11-17'),
(3, 5, '2022-11-17'),
(4, 1, '2022-11-17'),
(4, 2, '2022-11-17'),
(4, 3, '2022-11-17'),
(4, 4, '2022-11-17'),
(4, 5, '2022-11-17');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gehören`
--

CREATE TABLE `gehören` (
  `RFID_Batch` int(11) NOT NULL,
  `IDGruppe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `gehören`
--

INSERT INTO `gehören` (`RFID_Batch`, `IDGruppe`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 3),
(5, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `konferenzräume`
--

CREATE TABLE `konferenzräume` (
  `IDKonferenzraum` int(11) NOT NULL,
  `Name_Konferenzraum` varchar(250) NOT NULL,
  `Beschreibung` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `konferenzräume`
--

INSERT INTO `konferenzräume` (`IDKonferenzraum`, `Name_Konferenzraum`, `Beschreibung`) VALUES
(1, 'Meeting-Raum', 'großer Meetingraum für Meetings'),
(2, 'Informatiksall', 'Computerraum für IT-Mitarbeiter'),
(3, '1 Büro', 'ein Büro nur für Miatrbeiter, die reserviert haben');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `reservierung`
--

CREATE TABLE `reservierung` (
  `IDReservierung` int(11) NOT NULL,
  `ReservierSlots` int(11) DEFAULT NULL,
  `Datum` date NOT NULL,
  `Reservierungszweck` varchar(250) DEFAULT NULL,
  `Startzeit` time NOT NULL,
  `IDKonferenzraum` int(11) NOT NULL,
  `RFID_Batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `reservierung`
--

INSERT INTO `reservierung` (`IDReservierung`, `ReservierSlots`, `Datum`, `Reservierungszweck`, `Startzeit`, `IDKonferenzraum`, `RFID_Batch`) VALUES
(1, 1, '2022-11-17', 'ein Meeting mit Herr Joseph von der abteilung informatik', '15:40:06', 1, 3),
(2, 2, '2022-11-18', '2 Stunden für meine Arbeit zu beenden', '15:00:00', 3, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rules`
--

CREATE TABLE `rules` (
  `IDrule` int(11) NOT NULL,
  `Rulename` varchar(250) DEFAULT NULL,
  `Rule` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `rules`
--

INSERT INTO `rules` (`IDrule`, `Rulename`, `Rule`) VALUES
(1, 'Benutzer administrieren', 'Kann Benutzern administrieren, z.B Benutzer löschen oder Regeln für Benutzer festlegen'),
(2, 'Raum reservieren', 'Wie der Name der Regel es schon sagt, kann der Benutzer Räume reservieren'),
(3, 'Tür öffnen', 'Kann Tür öffnen mit dem Batch'),
(4, 'Reservierungszweck einsehen', 'Kann den zweck der Reservierung von alle Benutzer sehen'),
(5, 'Tagesübersicht einsehen', 'Kann die Reservierungen einsehen von dem Tag selber');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usergruppen`
--

CREATE TABLE `usergruppen` (
  `IDGruppe` int(11) NOT NULL,
  `GruppenName` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `usergruppen`
--

INSERT INTO `usergruppen` (`IDGruppe`, `GruppenName`) VALUES
(1, 'Reinigungsunternehmen'),
(2, 'Mitarbeiter'),
(3, 'Sicherheitspersonal'),
(4, 'Informatiker');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `RFID_Batch` int(11) NOT NULL,
  `Vorname` text NOT NULL,
  `Nachname` varchar(250) NOT NULL,
  `E_mail` varchar(250) NOT NULL,
  `Passwort` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`RFID_Batch`, `Vorname`, `Nachname`, `E_mail`, `Passwort`) VALUES
(1, 'Ermina', 'Poundford', 'epoundford0@simplemachines.org', 'GTtuabG4A'),
(2, 'Rozelle', 'Brandts', 'rbrandts1@typepad.com', 'bXXUoqe7fiw'),
(3, 'Lexy', 'Pritchard', 'lpritchard2@cnbc.com', 'BbAxEvISAwcb'),
(4, 'Lockwood', 'Rosas', 'lrosas3@histats.com', '8gPvVr'),
(5, 'Jeff', 'Müller', 'jeffmueler@info.com', '123de123');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dürfen`
--
ALTER TABLE `dürfen`
  ADD PRIMARY KEY (`IDGruppe`,`IDrule`),
  ADD KEY `IDrule` (`IDrule`);

--
-- Indizes für die Tabelle `gehören`
--
ALTER TABLE `gehören`
  ADD PRIMARY KEY (`RFID_Batch`,`IDGruppe`),
  ADD KEY `IDGruppe` (`IDGruppe`);

--
-- Indizes für die Tabelle `konferenzräume`
--
ALTER TABLE `konferenzräume`
  ADD PRIMARY KEY (`IDKonferenzraum`);

--
-- Indizes für die Tabelle `reservierung`
--
ALTER TABLE `reservierung`
  ADD PRIMARY KEY (`IDReservierung`),
  ADD KEY `IDKonferenzraum` (`IDKonferenzraum`),
  ADD KEY `RFID_Batch` (`RFID_Batch`);

--
-- Indizes für die Tabelle `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`IDrule`);

--
-- Indizes für die Tabelle `usergruppen`
--
ALTER TABLE `usergruppen`
  ADD PRIMARY KEY (`IDGruppe`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`RFID_Batch`),
  ADD UNIQUE KEY `E_mail` (`E_mail`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `konferenzräume`
--
ALTER TABLE `konferenzräume`
  MODIFY `IDKonferenzraum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `reservierung`
--
ALTER TABLE `reservierung`
  MODIFY `IDReservierung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `rules`
--
ALTER TABLE `rules`
  MODIFY `IDrule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `usergruppen`
--
ALTER TABLE `usergruppen`
  MODIFY `IDGruppe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `dürfen`
--
ALTER TABLE `dürfen`
  ADD CONSTRAINT `dürfen_ibfk_1` FOREIGN KEY (`IDGruppe`) REFERENCES `usergruppen` (`IDGruppe`),
  ADD CONSTRAINT `dürfen_ibfk_2` FOREIGN KEY (`IDrule`) REFERENCES `rules` (`IDrule`);

--
-- Constraints der Tabelle `gehören`
--
ALTER TABLE `gehören`
  ADD CONSTRAINT `gehören_ibfk_1` FOREIGN KEY (`RFID_Batch`) REFERENCES `users` (`RFID_Batch`),
  ADD CONSTRAINT `gehören_ibfk_2` FOREIGN KEY (`IDGruppe`) REFERENCES `usergruppen` (`IDGruppe`);

--
-- Constraints der Tabelle `reservierung`
--
ALTER TABLE `reservierung`
  ADD CONSTRAINT `reservierung_ibfk_1` FOREIGN KEY (`IDKonferenzraum`) REFERENCES `konferenzräume` (`IDKonferenzraum`),
  ADD CONSTRAINT `reservierung_ibfk_2` FOREIGN KEY (`RFID_Batch`) REFERENCES `users` (`RFID_Batch`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
