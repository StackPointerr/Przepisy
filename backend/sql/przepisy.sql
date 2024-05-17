-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Maj 17, 2024 at 07:37 PM
-- Wersja serwera: 11.3.2-MariaDB
-- Wersja PHP: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `przepisy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ksiazka`
--

CREATE TABLE `ksiazka` (
  `id` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `id_przepisu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przepisy`
--

CREATE TABLE `przepisy` (
  `id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `opis` varchar(500) NOT NULL,
  `opis_przygotowania` varchar(500) NOT NULL,
  `zdjecie` varchar(50) NOT NULL,
  `kategoria` int(11) NOT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tokeny`
--

CREATE TABLE `tokeny` (
  `id_uzytkownika` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `nazwa_uzytkownika` varchar(25) NOT NULL,
  `haslo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `ksiazka`
--
ALTER TABLE `ksiazka`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_uzytkownika_2` (`id_uzytkownika`,`id_przepisu`),
  ADD KEY `id_przepisu` (`id_przepisu`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `przepisy`
--
ALTER TABLE `przepisy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoria` (`kategoria`),
  ADD KEY `autor` (`autor`);

--
-- Indeksy dla tabeli `tokeny`
--
ALTER TABLE `tokeny`
  ADD UNIQUE KEY `id_uzytkownika` (`id_uzytkownika`,`token`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ksiazka`
--
ALTER TABLE `ksiazka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `przepisy`
--
ALTER TABLE `przepisy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ksiazka`
--
ALTER TABLE `ksiazka`
  ADD CONSTRAINT `ksiazka_ibfk_1` FOREIGN KEY (`id_przepisu`) REFERENCES `przepisy` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ksiazka_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `przepisy`
--
ALTER TABLE `przepisy`
  ADD CONSTRAINT `przepisy_ibfk_1` FOREIGN KEY (`kategoria`) REFERENCES `kategorie` (`id`),
  ADD CONSTRAINT `przepisy_ibfk_2` FOREIGN KEY (`autor`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tokeny`
--
ALTER TABLE `tokeny`
  ADD CONSTRAINT `tokeny_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
