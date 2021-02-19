-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 18-02-2021 a las 16:57:36
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
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) NOT NULL,
  `activacion` int(1) NOT NULL DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_ultimo_ingreso` timestamp NULL DEFAULT NULL,
  `usuario` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `activacion`, `fecha_registro`, `fecha_ultimo_ingreso`, `usuario`, `password`, `mail`) VALUES
(2, 0, '2021-01-31 21:11:21', '2021-01-31 21:23:03', 'Julito', '7c222fb2927d828af22f592134e8932480637c0d', '37998eac77676c224e1cbd5344edc4dae942946d'),
(9, 1, '2021-02-02 18:12:20', '2021-02-02 18:13:01', 'Marcos', '7c222fb2927d828af22f592134e8932480637c0d', '4da7f1f42538bf7e3abbd30fc7914efa2fcd911c'),
(10, 1, '2021-02-07 12:21:38', '2021-02-12 21:13:43', 'Fersegura', '001c4ef9807c0f5d3f71c7e8b47da3896731f32f', 'b3cd21fe6dae5e141f4d2eeba8ceead8fa22f513'),
(11, 1, '2021-02-08 15:28:36', '2021-02-10 21:47:07', 'santi', '92429d82a41e930486c6de5ebda9602d55c39986', '88f63b9ebad5dfa181e76616be6f6ccecc0936e5');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
