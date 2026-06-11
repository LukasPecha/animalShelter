-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Št 11.Jún 2026, 23:46
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `animal_shelter`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `species` varchar(50) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `animals`
--

INSERT INTO `animals` (`id`, `name`, `species`, `breed`, `description`, `created_at`, `image`) VALUES
(2, 'Dunčo', 'Pes', 'Zlatý retriever', 'Priateľský, hravý', '2026-06-09 13:36:30', '6a2b10b56bab9.png'),
(3, 'Mica', 'Mačka', 'Maianská', 'Hravá', '2026-06-09 13:43:25', '6a2b10ac8e564.png'),
(4, 'Amálka', 'Škrečok', 'Sýrsky', 'Hravý, plachý', '2026-06-10 16:03:02', '6a298ab632fd7.jpg'),
(5, 'Morko', 'Morské prasa', 'MP', 'Plache', '2026-06-11 19:45:58', '6a2b10761b3dd.jpg');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'L_Admin', '$2a$12$0/XVu1Kh1k3XxJjiDTsNMum13vYeJlhMUa6NOvjff1gsGBfDjHlfC', 'admin'),
(2, 'L_User', '$2a$12$NHJQ2IpiVNVXAcrd7z5cDuJ0ZJC0RuXmjG.3NeFw3QvhruU7pMdxG', 'user');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
