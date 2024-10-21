-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2024 a las 07:01:17
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
-- Estructura de tabla para la tabla `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `contraseña` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `admin_user`
--

INSERT INTO `admin_user` (`id`, `nombre_usuario`, `email`, `contraseña`) VALUES
(1, '2Ailen4', 'ailen@gmail.com', '$2y$10$G8ZqydTlUwaUN2n5U1oWoeoaGU.Gt69EL/n1tofqBeC1JMrgFb6fS'),
(2, 'webadmin', 'webadmin@gmail.com', '$2y$10$4mYQibyjU3nAXCOm8Zy.f.yQwohm8i5GzFuj1ZaRMZxRIyx6kPyY2'),
(3, 'Marian07', 'mariano@gmail.com', '$2y$10$vJpeBh/wRpjORwwQemI/e.Zxtq8KdCYPyMSODEVtFS4p30uWSSJLm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadorpokemon`
--

CREATE TABLE `entrenadorpokemon` (
  `id_entrenador` int(12) NOT NULL,
  `nombre_entrenador` varchar(15) NOT NULL,
  `ciudad_origen` varchar(20) NOT NULL,
  `nivel_entrenador` int(11) NOT NULL DEFAULT 1,
  `cant_medallas` int(11) NOT NULL DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `entrenadorpokemon`
--

INSERT INTO `entrenadorpokemon` (`id_entrenador`, `nombre_entrenador`, `ciudad_origen`, `nivel_entrenador`, `cant_medallas`, `descripcion`, `imagen`) VALUES
(1, 'Ash Ketchum', 'Pueblo Paleta', 5, 3, NULL, 'images/trainers/Ash Ketchum.jpg'),
(2, 'Brock', 'Ciudad Plateada', 7, 2, NULL, 'images/trainers/Brock.jpg'),
(3, 'Misty', 'Ciudad Celeste', 6, 1, NULL, 'images/trainers/Misty.jpg'),
(13, '2Ailu4', 'Tandilia', 1, 1, NULL, 'images/trainers/Poliwag_671487033fc46.jpg'),
(14, 'cacho', 'PALETITAtita', 2, 21, NULL, 'images/trainers/Rapidash_671486c17e034.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pokemon`
--

CREATE TABLE `pokemon` (
  `id` int(11) NOT NULL,
  `nro_pokedex` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `fecha_captura` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `peso` int(11) NOT NULL,
  `FK_id_entrenador` int(12) DEFAULT NULL,
  `imagen_pokemon` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pokemon`
--

INSERT INTO `pokemon` (`id`, `nro_pokedex`, `nombre`, `tipo`, `fecha_captura`, `peso`, `FK_id_entrenador`, `imagen_pokemon`) VALUES
(1, 1, 'Bulbasaur', 'Planta Veneno', '2024-10-19 23:53:45', 60, 1, 'images/pokemons/Bulbasaur.jpg'),
(2, 4, 'Charmander', 'Fuego', '2024-10-19 23:54:01', 8, 1, 'images/pokemons/Charmander.jpg'),
(3, 7, 'Squirtle', 'Agua', '2024-10-19 23:54:26', 9, 1, 'images/pokemons/Squirtle.jpg'),
(4, 25, 'Pikachu', 'Electrico', '2024-10-19 23:54:50', 6, 1, 'images/pokemons/Pikachu.jpg'),
(5, 54, 'Psyduck', 'Agua', '2024-10-19 23:59:20', 19, 3, 'images/pokemons/Psyduck.jpg'),
(6, 60, 'Poliwag', 'Agua', '2024-10-19 23:59:20', 12, 3, 'images/pokemons/Poliwag.jpg'),
(7, 74, 'Geodude', 'Roca Tierra', '2024-10-19 23:59:20', 20, 2, 'images/pokemons/Geodude.jpg'),
(8, 95, 'Onix', 'Roca Tierra', '2024-10-19 23:59:20', 210, 2, 'images/pokemons/Onix.jpg'),
(9, 1, 'Bulbasaur', 'Planta Veneno', '2024-10-19 23:59:20', 10, 2, 'images/pokemons/Bulbasaur.jpg'),
(10, 60, 'Poliwag', 'Agua', '2024-10-19 23:59:20', 12, 1, 'images/pokemons/Poliwag.jpg'),
(11, 74, 'Geodude', 'Roca Tierra', '2024-10-19 23:59:20', 33, 3, 'images/pokemons/Geodude.jpg'),
(12, 95, 'Onix', 'Roca Tierra', '2024-10-19 23:59:20', 130, 1, 'images/pokemons/Onix.jpg'),
(15, 25, 'Pikachu', 'Electrico', '2024-10-19 23:59:20', 15, 3, 'images/pokemons/Pikachu.jpg'),
(16, 54, 'Psyduck', 'Agua', '2024-10-19 23:59:20', 444, 2, 'images/pokemons/Psyduck.jpg'),
(22, 1, 'Bulbasaur', 'Planta Veneno', '2024-10-19 23:59:20', 21, NULL, 'images/pokemons/Bulbasaur.jpg'),
(37, 1, 'Bulbasaur', 'Planta Veneno', '2024-10-20 01:03:15', 56, NULL, 'images/pokemons/Bulbasaur.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

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
  ADD PRIMARY KEY (`id`,`nro_pokedex`),
  ADD KEY `FK_id_entrenador` (`FK_id_entrenador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `entrenadorpokemon`
--
ALTER TABLE `entrenadorpokemon`
  MODIFY `id_entrenador` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pokemon`
--
ALTER TABLE `pokemon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
