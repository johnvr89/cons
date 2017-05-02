-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-04-2016 a las 21:43:33
-- Versión del servidor: 5.6.26
-- Versión de PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `carousel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slider`
--

CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `url_image` varchar(255) NOT NULL,
  `texto_boton` varchar(50) NOT NULL,
  `url_boton` text NOT NULL,
  `estilo_boton` varchar(30) NOT NULL,
  `estado` int(1) NOT NULL,
  `orden` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `slider`
--

INSERT INTO `slider` (`id`, `titulo`, `descripcion`, `url_image`, `texto_boton`, `url_boton`, `estilo_boton`, `estado`, `orden`) VALUES
(1, 'Captura el momento', 'EnamÃ³rate de la versatilidad y el diseÃ±o retro de tu NX3300', 'promo2.png', 'Ver Ahora', 'producto/15/smart-camera-nx3300', 'info', 1, 4),
(2, 'El rey de los selfies', 'DiseÃ±o metÃ¡lico premium en atractivos colores', 'promo3.png', 'Comprar Ahora!', '', 'warning', 1, 0),
(3, 'La mejor experiencia!', 'Su pantalla WXGA (1.280 x 800) de 10.1 pulgadas y formato 16:10 te permite disfrutar de una imagen. ', 'promo1.png', 'Comprar Ahora!', 'producto/6/galaxy-tab-4-101-4g', 'primary', 1, 1),
(4, 'Galaxy A3 (2016)', 'El nuevo smartphone Galaxy A3 2016 tiene un diseÃ±o que combina elegancia.\r\n ', 's3.png', 'Comprar Ahora!', 'producto/10/televisor-60-j6200-led-full-hd-smart-tv', 'info', 1, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
