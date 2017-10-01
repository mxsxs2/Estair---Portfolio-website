-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Hoszt: 127.0.0.1
-- Létrehozás ideje: 2015. Feb 24. 23:15
-- Szerver verzió: 5.6.21
-- PHP verzió: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `estair`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `album`
--

CREATE TABLE IF NOT EXISTS `album` (
`id` tinyint(5) NOT NULL,
  `name_hun` varchar(15) NOT NULL,
  `name_en` varchar(15) NOT NULL,
  `photos` int(2) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL,
  `dir` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `album`
--

INSERT INTO `album` (`id`, `name_hun`, `name_en`, `photos`, `active`, `dir`) VALUES
(1, 'Valami', 'Something', 2, 1, 'something'),
(4, 'asd', 'asd', 1, 1, 'asd'),
(5, 'Tamas angol', 'Tamas magyar', 2, 1, 'tamasmagyar');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
`id` tinyint(5) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name_hun` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `desc_hun` varchar(255) NOT NULL,
  `desc_en` varchar(255) NOT NULL,
  `album` varchar(225) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- A tábla adatainak kiíratása `photos`
--

INSERT INTO `photos` (`id`, `filename`, `name_hun`, `name_en`, `desc_hun`, `desc_en`, `album`, `date`) VALUES
(1, '1edc51b993f6c0b98ad4ece3183dc89f', '', 'Some image', '', 'And this is a fuckin lond description about this shitty picture.......', 'something', '2013-05-28 22:16:48'),
(2, 'aa1cd4dcfe5cde0680a39e31344d3105', 'Magyar név', 'English name', 'Magyar leírás', 'Long english descripton', 'something', '2013-06-16 22:44:57'),
(3, 'c815167db2c0e28c42e1bcb79d97235e', 'Kép címe hun', 'Kép címe en', 'Kép rövid leírása hun', 'Kép rövid leírása en', 'dickkkkk', '2015-02-24 21:04:03'),
(4, '2eee712b0f1a6e72b9dc5b6e2e8dd982', 'Kép címe hun', 'Kép címe en', 'Kép rövid leírása hun', 'Kép rövid leírása en', 'tamasmagyar', '2015-02-24 21:07:55'),
(5, '081a9ba903918d8e899a2b1589e0ef2c', 'Kép címe hun2', 'Kép címe en2', 'Kép rövid leírása hun2', 'Kép rövid leírása en2', 'tamasmagyar', '2015-02-24 21:08:08');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user_sessions`
--

CREATE TABLE IF NOT EXISTS `user_sessions` (
  `session_id` varchar(100) NOT NULL,
  `ip_adress` varchar(100) DEFAULT NULL,
  `user_agent` varchar(100) DEFAULT NULL,
  `modified` varchar(100) DEFAULT NULL,
  `u_id` varchar(45) DEFAULT NULL,
  `cookie` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `user_sessions`
--

INSERT INTO `user_sessions` (`session_id`, `ip_adress`, `user_agent`, `modified`, `u_id`, `cookie`) VALUES
('c68b85203821f5ebdbe1c935c651ca8c61e9ba7a', '176.61.83.49', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.99 Safar', NULL, '1', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
 ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
