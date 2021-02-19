-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 18-02-2021 a las 16:56:48
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id15900605_esp8266`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ESPtable2`
--

CREATE TABLE `ESPtable2` (
  `id` int(5) NOT NULL,
  `PASSWORD` int(5) NOT NULL,
  `SENT_NUMBER_1` int(5) NOT NULL,
  `SENT_NUMBER_2` int(5) NOT NULL,
  `SENT_NUMBER_3` int(5) NOT NULL,
  `SENT_NUMBER_4` int(5) NOT NULL,
  `SENT_BOOL_1` tinyint(1) NOT NULL,
  `SENT_BOOL_2` tinyint(1) NOT NULL,
  `SENT_BOOL_3` tinyint(1) NOT NULL,
  `RECEIVED_BOOL1` int(1) NOT NULL,
  `RECEIVED_BOOL2` int(1) NOT NULL,
  `RECEIVED_BOOL3` int(1) NOT NULL,
  `RECEIVED_BOOL4` int(1) NOT NULL,
  `RECEIVED_BOOL5` int(1) NOT NULL,
  `RECEIVED_NUM1` int(5) NOT NULL,
  `RECEIVED_NUM2` int(5) NOT NULL,
  `RECEIVED_NUM3` int(5) NOT NULL,
  `RECEIVED_NUM4` int(5) NOT NULL,
  `RECEIVED_NUM5` int(5) NOT NULL,
  `TEXT_1` text CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha_desde` varchar(10) DEFAULT NULL,
  `fecha_hasta` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ESPtable2`
--

INSERT INTO `ESPtable2` (`id`, `PASSWORD`, `SENT_NUMBER_1`, `SENT_NUMBER_2`, `SENT_NUMBER_3`, `SENT_NUMBER_4`, `SENT_BOOL_1`, `SENT_BOOL_2`, `SENT_BOOL_3`, `RECEIVED_BOOL1`, `RECEIVED_BOOL2`, `RECEIVED_BOOL3`, `RECEIVED_BOOL4`, `RECEIVED_BOOL5`, `RECEIVED_NUM1`, `RECEIVED_NUM2`, `RECEIVED_NUM3`, `RECEIVED_NUM4`, `RECEIVED_NUM5`, `TEXT_1`, `fecha_desde`, `fecha_hasta`) VALUES
(99999, 12345, 327, 1212, 3233, 1313, 1, 1, 1, 0, 0, 0, 0, 1, 89, 1800, 0, 2324, 0, 'Hola soy el teo !!', '2021-01-30', '2021-02-10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ESPtable2`
--
ALTER TABLE `ESPtable2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ESPtable2`
--
ALTER TABLE `ESPtable2`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100000;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
