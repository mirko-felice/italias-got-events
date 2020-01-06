-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 21, 2019 alle 14:32
-- Versione del server: 10.4.10-MariaDB
-- Versione PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `progetto`
--
CREATE DATABASE IF NOT EXISTS `progetto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `progetto`;

-- --------------------------------------------------------

--
-- Struttura della tabella `biglietti`
--

CREATE TABLE `biglietti` (
  `id_biglietto` bigint(20) NOT NULL,
  `evento` bigint(20) NOT NULL,
  `proprietario` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `bozze_notifiche`
--

CREATE TABLE `bozze_notifiche` (
  `id_notifica` bigint(20) NOT NULL,
  `bozza` varchar(255) NOT NULL,
  `ordine_bozza` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `id_categoria` bigint(20) NOT NULL,
  `nome_categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `eventi`
--

CREATE TABLE `eventi` (
  `id_evento` bigint(20) NOT NULL,
  `categoria` bigint(20) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `luogo_avvenimento` varchar(255) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `data_avvenimento` date NOT NULL,
  `data_conclusione` date DEFAULT NULL,
  `orario` time NOT NULL,
  `descrizione_lunga` mediumtext NOT NULL,
  `prezzo` bigint(20) NOT NULL,
  `immagine_evento` varchar(255) DEFAULT NULL,
  `numero_visualizzazioni` bigint(20) NOT NULL,
  `organizzatore` bigint(20) NOT NULL,
  `data_inserimento` date NOT NULL,
  `evento_attivo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente_ha_categoria`
--

CREATE TABLE `utente_ha_categoria` (
  `utente` bigint(20) NOT NULL,
  `categoria` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente_riceve_notifiche`
--

CREATE TABLE `utente_riceve_notifiche` (
  `utente` bigint(20) NOT NULL,
  `id_notifica_per_utente` bigint(20) NOT NULL,
  `id_notifica` bigint(20) NOT NULL,
  `messaggio_finale` mediumtext NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` bigint(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `data_di_nascita` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `città` varchar(255) NOT NULL,
  `cellulare` varchar(15) DEFAULT NULL,
  `immagine` varchar(255) DEFAULT NULL,
  `utente_attivo` tinyint(1) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `biglietti`
--
ALTER TABLE `biglietti`
  ADD PRIMARY KEY (`id_biglietto`,`evento`),
  ADD KEY `Biglietti_fk0` (`evento`),
  ADD KEY `Biglietti_fk1` (`proprietario`);

--
-- Indici per le tabelle `bozze_notifiche`
--
ALTER TABLE `bozze_notifiche`
  ADD PRIMARY KEY (`id_notifica`,`ordine_bozza`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nome_categoria` (`nome_categoria`);

--
-- Indici per le tabelle `eventi`
--
ALTER TABLE `eventi`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `Eventi_fk0` (`categoria`),
  ADD KEY `Eventi_fk1` (`organizzatore`);

--
-- Indici per le tabelle `utente_ha_categoria`
--
ALTER TABLE `utente_ha_categoria`
  ADD PRIMARY KEY (`utente`,`categoria`),
  ADD KEY `Utente_ha_categoria_fk1` (`categoria`);

--
-- Indici per le tabelle `utente_riceve_notifiche`
--
ALTER TABLE `utente_riceve_notifiche`
  ADD PRIMARY KEY (`id_notifica_per_utente`,`utente`),
  ADD KEY `Utente_riceve_notifiche_fk0` (`utente`),
  ADD KEY `Utente_riceve_notifiche_fk1` (`id_notifica`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categoria` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `eventi`
--
ALTER TABLE `eventi`
  MODIFY `id_evento` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `biglietti`
--
ALTER TABLE `biglietti`
  ADD CONSTRAINT `Biglietti_fk0` FOREIGN KEY (`evento`) REFERENCES `eventi` (`id_evento`),
  ADD CONSTRAINT `Biglietti_fk1` FOREIGN KEY (`proprietario`) REFERENCES `utenti` (`id_utente`);

--
-- Limiti per la tabella `eventi`
--
ALTER TABLE `eventi`
  ADD CONSTRAINT `Eventi_fk0` FOREIGN KEY (`categoria`) REFERENCES `categorie` (`id_categoria`),
  ADD CONSTRAINT `Eventi_fk1` FOREIGN KEY (`organizzatore`) REFERENCES `utenti` (`id_utente`);

--
-- Limiti per la tabella `utente_ha_categoria`
--
ALTER TABLE `utente_ha_categoria`
  ADD CONSTRAINT `Utente_ha_categoria_fk0` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id_utente`),
  ADD CONSTRAINT `Utente_ha_categoria_fk1` FOREIGN KEY (`categoria`) REFERENCES `categorie` (`id_categoria`);

--
-- Limiti per la tabella `utente_riceve_notifiche`
--
ALTER TABLE `utente_riceve_notifiche`
  ADD CONSTRAINT `Utente_riceve_notifiche_fk0` FOREIGN KEY (`utente`) REFERENCES `utenti` (`id_utente`),
  ADD CONSTRAINT `Utente_riceve_notifiche_fk1` FOREIGN KEY (`id_notifica`) REFERENCES `bozze_notifiche` (`id_notifica`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
