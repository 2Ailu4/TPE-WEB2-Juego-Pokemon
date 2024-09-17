-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-09-2024 a las 22:57:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tpe-web2-hiese-peralta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadorpokemon`
--

CREATE TABLE `entrenadorpokemon` (
  `id_entrenador` int(12) NOT NULL,
  `nombre_entrenador` varchar(15) NOT NULL,
  `ciudad_origen` varchar(20) NOT NULL,
  `nivel_entrenador` int(11) NOT NULL DEFAULT 1,
  `cant_medallas` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `entrenadorpokemon`
--

INSERT INTO `entrenadorpokemon` (`id_entrenador`, `nombre_entrenador`, `ciudad_origen`, `nivel_entrenador`, `cant_medallas`) VALUES
(1, 'Ash', 'Pueblo Paleta', 5, 3),
(2, 'Brock', 'Ciudad Plateada', 7, 2),
(3, 'Misty', 'Ciudad Celeste', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemon`
--

CREATE TABLE `pokemon` (
  `nro_pokedex` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `fecha_captura` date NOT NULL,
  `peso` int(11) NOT NULL,
  `FK_id_entrenador` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pokemon`
--

INSERT INTO `pokemon` (`nro_pokedex`, `nombre`, `tipo`, `fecha_captura`, `peso`, `FK_id_entrenador`) VALUES
(1, 'Bulbasaur', 'Planta Veneno', '2024-06-05', 6, 1),
(4, 'Charmander', 'Fuego', '2024-07-08', 8, 1),
(7, 'Squirtle', 'Agua', '2024-06-28', 9, 1),
(25, 'Pikachu', 'Electrico', '2024-03-12', 6, 1),
(54, 'Psyduck', 'Agua', '2023-08-10', 19, 3),
(60, 'Poliwag', 'Agua', '2024-01-08', 12, 3),
(74, 'Geodude', 'Roca Tierra', '2023-11-20', 20, 2),
(95, 'Onix', 'Roca Tierra', '2023-05-01', 210, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `entrenadorpokemon`
--
ALTER TABLE `entrenadorpokemon`
  ADD PRIMARY KEY (`id_entrenador`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- Indices de la tabla `pokemon`
--
ALTER TABLE `pokemon`
  ADD PRIMARY KEY (`nro_pokedex`),
  ADD KEY `FK_id_entrenador` (`FK_id_entrenador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `entrenadorpokemon`
--
ALTER TABLE `entrenadorpokemon`
  MODIFY `id_entrenador` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pokemon`
--
ALTER TABLE `pokemon`
  ADD CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`FK_id_entrenador`) REFERENCES `entrenadorpokemon` (`id_entrenador`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
