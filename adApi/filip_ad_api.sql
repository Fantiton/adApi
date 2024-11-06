-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Lis 06, 2024 at 10:00 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `filip_ad_api`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `accounts`
--

CREATE TABLE `accounts` (
  `accountNo` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `name` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountNo`, `amount`, `name`, `user_id`) VALUES
(123456, 2137, 'żółty', 1),
(12345678, 10000, 'rizzler', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` varchar(70) NOT NULL COMMENT 'sha-256',
  `ip` varchar(16) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `token`
--

INSERT INTO `token` (`id`, `token`, `ip`, `user_id`) VALUES
(1, 'ac97ba6ef54b372315d96961242d361ec8ff4f60ba15dc607af261b60263e1a0', '::1', 1),
(2, '038844ed15fb4acfedeafe1eb1403d8d89afbd9603bfdcdb06d651128cf0f8f5', '::1', 1),
(3, '82b447c94299c21162309ef5fe494c5a8cc81f766d9c7aabeb106049a777216b', '::1', 2),
(4, '7b5b79d6b2c20c45b2f03425be293aeb408cc1b180973febb25a177d5b9db102', '::1', 2),
(5, 'aa81d3e5f91470fb222d1348d577e55b1ea8bea920a0e2d1dc83b2982faad076', '::1', 2),
(6, '169caa40782e2d9640874698eae7dc6b459228c1051c4e7eefb306a3f7426f6b', '::1', 2),
(7, '0ae2c795706426d9b31eec3a76802c7bf44876b7e8c99e70da692c347b40d993', '::1', 2),
(8, '69292d0a7fb79e67e3f092fa9ab55d1a1bf1c596ccc47c111708096ac6184299', '::1', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passwordHash` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `passwordHash`) VALUES
(1, 'rizzler@teb', '$argon2i$v=19$m=16,t=2,p=1$TkFtOTBVQVNtTURCSzVDOQ$CsENyvPUMqnttmvSGW7FYg'),
(2, 'kowejko', '$argon2i$v=19$m=16,t=2,p=1$MTIzNDU2Nzg$sd3sNXdtHHX24YjTXY9Dnw');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountNo`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
