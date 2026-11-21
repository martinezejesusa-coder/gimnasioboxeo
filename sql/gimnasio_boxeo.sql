-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 16-11-2026 a las 01:28:58
-- Versión del servidor: 8.0.46-0ubuntu0.24.04.5
-- Versión de PHP: 8.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gimnasio_boxeo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atletas`
--

CREATE TABLE `atletas` (
  `id_atleta` int NOT NULL,
  `pnombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `snombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `papellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sapellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `id_club` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `atletas`
--

INSERT INTO `atletas` (`id_atleta`, `pnombre`, `snombre`, `papellido`, `sapellido`, `cedula`, `fecha_nacimiento`, `id_club`, `id_categoria`, `foto`, `activo`) VALUES
(1, 'Juan', NULL, 'Perez', NULL, '11111111', '1998-05-12', 1, 4, NULL, 1),
(2, 'Pedro', NULL, 'Gomez', NULL, '22222222', '2000-08-20', 1, 3, NULL, 1),
(3, 'Luis', NULL, 'Martinez', NULL, '33333333', '1995-03-15', 2, 5, NULL, 1),
(4, 'Andres', NULL, 'Lopez', NULL, '44444444', '2001-11-30', 2, 4, NULL, 1),
(5, 'ilia', '', 'topuria', '', '232323232323', '1997-01-21', 2, 4, NULL, 1),
(6, 'PRUEBA', '', 'TEST', '', 'TEST123', '1990-01-01', NULL, NULL, NULL, 1),
(7, 'ACTUALIZADO_1781479319', '', 'TEST', '', 'TEST_1781479319', '1990-01-01', NULL, NULL, NULL, 0),
(8, 'ACTUALIZADO_1781479431', '', 'TEST', '', 'TEST_1781479431', '1990-01-01', NULL, NULL, NULL, 0),
(9, '', '', '', '', '', NULL, NULL, NULL, NULL, 1),
(10, 'Jesus', '', 'Martinez', '', '26945388', '1999-11-21', 3, 6, 'public/uploads/atletas/atleta_6a2f3c318b4aa.png', 1),
(11, 'Jesúss', '', 'Martinez', '', '26945389', '1999-11-21', 2, 6, 'public/uploads/atletas/atleta_6a2f3c73b1411.png', 1),
(12, 'Pedro', '', 'perez', '', '943234', '1980-10-11', 3, 6, 'public/uploads/atletas/atleta_6a2f53a48cda5.jpeg', 1),
(13, 'Khamzat', '', 'Chimaev', '', '1232323', '1994-05-01', 1, 6, 'public/uploads/atletas/atleta_6a2f69e42b974.webp', 1),
(14, 'tony', '', 'torrealba', '', '66666667', '2000-04-10', 4, 6, 'public/uploads/atletas/atleta_6a352ee596b6e.jpeg', 0),
(15, 'José', 'Guillermo', 'Martinez', NULL, 'J-25854009', '2005-07-14', 4, 6, 'public/uploads/atletas/atleta_6aa2e9ce5bfe2.jpeg', 1),
(16, 'Jesússw', 'dasdasd', 'asdasd', 'Lopezwa', 'V-25854004', '1999-11-21', 1, 6, 'public/uploads/atletas/atleta_6af4c6d81da32.jpeg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atleta_documentos`
--

CREATE TABLE `atleta_documentos` (
  `id_documento` int NOT NULL,
  `id_atleta` int DEFAULT NULL,
  `nombre_documento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruta_archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_subida` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `atleta_documentos`
--

INSERT INTO `atleta_documentos` (`id_documento`, `id_atleta`, `nombre_documento`, `ruta_archivo`, `fecha_subida`) VALUES
(1, 10, 'ficha tecnica ilia topuria.odt', 'public/uploads/documentos/doc_6a2f3c31d06cc.odt', '2026-06-14 23:41:37'),
(2, 11, 'ficha tecnica ilia topuria.odt', 'public/uploads/documentos/doc_6a2f3c73d3b60.odt', '2026-06-14 23:42:43'),
(3, 11, '4350812.png', 'public/uploads/documentos/doc_6a2f474f1b564.png', '2026-06-15 00:29:03'),
(4, 12, 'romero.jpeg', 'public/uploads/documentos/doc_6a2f53a4bf0b9_1781486500.jpeg', '2026-06-15 01:21:40'),
(5, 13, 'ficha tecnica ilia topuria.docx', 'public/uploads/documentos/doc_6a2f6a4194b55_1781492289.docx', '2026-06-15 02:58:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nombre_categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `peso_min` decimal(5,2) DEFAULT NULL,
  `peso_max` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `peso_min`, `peso_max`) VALUES
(1, 'Semillita', 48.00, 51.00),
(2, 'Infantil', 51.00, 54.00),
(3, 'Junior', 54.00, 57.00),
(4, 'Cadete', 57.00, 60.00),
(5, 'Juvenil', 60.00, 69.00),
(6, 'Adulto', 69.00, 75.00),
(7, 'Pesado', 75.00, 91.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clubes`
--

CREATE TABLE `clubes` (
  `id_club` int NOT NULL,
  `nombre_club` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_parroquia` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clubes`
--

INSERT INTO `clubes` (`id_club`, `nombre_club`, `ubicacion`, `telefono`, `correo`, `logo`, `id_parroquia`) VALUES
(1, 'Club Boxeo Central', 'Av. Principal, Caracas', '02125551234', 'central@boxeo.com', NULL, NULL),
(2, 'Gimnasio Knockout 2', 'Calle 5, Valencia', '02415551234', 'knockout@boxeo.com', 'public/uploads/clubes/club_6a30b7490de95.jpeg', NULL),
(3, 'El Matador MMA Academy', 'Avenida Principal de Las Mercedes, Edificio Ring Central, Piso 2, Caracas.', '+58412-5551234', 'contacto@elmatadormma.com', NULL, NULL),
(4, 'Dojo JIUJITSU - Muay Thai - MMA', 'entre calles 25 y 26 Av. Venezuela, Barquisimeto 3001, Lara', '04221232323', 'dojo@gmail.com', 'public/uploads/clubes/club_6a30b7fc62421.jpg', NULL),
(5, 'Gimganm', 'obelisco', '+507 42342343', 'miguel@boxeo.com', 'public/uploads/clubes/club_6aa306b073406.jpeg', NULL),
(6, 'kinkong', 'esquina calle 12', '0424-1234325', 'kinkong@gmail.com', 'public/uploads/clubes/club_6af4b69541932.jpeg', 535),
(7, 'Gimganmwqwqwd12', 'esquina calle 12 asdasdasdasd', '0424-1234325', 'king@gmail.com', NULL, 173);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combates`
--

CREATE TABLE `combates` (
  `id_combate` int NOT NULL,
  `id_torneo` int DEFAULT NULL,
  `id_atleta_local` int DEFAULT NULL,
  `id_atleta_visitante` int DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `ronda` int DEFAULT NULL,
  `rounds_pautados` int DEFAULT '3',
  `round_finalizacion` int DEFAULT NULL,
  `decision_victoria` enum('KO','TKO','UD','SD','MD','RTD','DQ','NC') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resultado` enum('Pendiente','Local','Visitante','Empate','No Contest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pendiente',
  `posicion_llave` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `combates`
--

INSERT INTO `combates` (`id_combate`, `id_torneo`, `id_atleta_local`, `id_atleta_visitante`, `fecha`, `ronda`, `rounds_pautados`, `round_finalizacion`, `decision_victoria`, `resultado`, `posicion_llave`) VALUES
(1, 1, 1, 2, '2025-03-05', 3, 3, 1, 'KO', 'Visitante', 1),
(2, 1, 3, 4, '2025-03-07', 3, 3, 3, 'UD', 'Local', 1),
(3, 2, 1, 3, '2025-05-12', 4, 3, NULL, 'MD', 'Empate', NULL),
(4, NULL, 3, 1, '2026-06-12', 3, 3, NULL, NULL, 'Local', NULL),
(5, 1, 2, 4, '2026-06-13', 1, 3, NULL, NULL, 'Visitante', 0),
(6, 1, 5, 4, '2026-06-13', 1, 3, NULL, NULL, 'Local', 0),
(7, 1, 10, 11, '2026-06-16', 3, 3, NULL, NULL, 'Pendiente', 0),
(8, 1, 13, 12, '2026-06-16', 1, 3, 2, 'KO', 'Local', 1),
(9, 2, 15, 13, '2026-09-10', 4, 8, NULL, NULL, 'Pendiente', 1),
(10, 2, 16, 15, '2026-11-11', 3, 8, NULL, 'UD', 'Local', 1),
(11, NULL, 16, 15, '2026-11-15', 1, 3, NULL, 'UD', 'Local', NULL),
(12, NULL, 16, 13, '2026-11-15', 1, 3, NULL, NULL, 'Pendiente', NULL),
(13, NULL, 16, 15, '2026-11-15', 1, 3, NULL, 'UD', 'Local', NULL),
(14, NULL, 16, 15, '2026-11-15', 1, 3, NULL, 'UD', 'Local', NULL),
(16, NULL, 16, 12, '2026-11-15', 1, 3, NULL, NULL, 'Pendiente', NULL),
(17, NULL, 12, 13, '2026-11-15', 1, 3, NULL, NULL, 'Pendiente', NULL),
(18, NULL, 12, 13, '2026-11-15', 1, 3, NULL, NULL, 'Pendiente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadores`
--

CREATE TABLE `entrenadores` (
  `id_entrenador` int NOT NULL,
  `pnombre_entrenador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `snombre_entrenador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_entrenador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sapellido_entrenador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula_entrenador` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrenadores`
--

INSERT INTO `entrenadores` (`id_entrenador`, `pnombre_entrenador`, `snombre_entrenador`, `apellido_entrenador`, `sapellido_entrenador`, `cedula_entrenador`, `telefono`, `correo`, `activo`) VALUES
(1, 'Carlos', '', 'Ramirez', '', '12345678', '04141234567', 'carlos@boxeo.com', 1),
(2, 'Miguel', '', 'Torres', '', '23456789', '04147654321', 'miguel@boxeo.com', 1),
(3, 'ilia', 'matador', 'topuria', '', '123456789', '087657567756', 'elmatador@gmail.com', 1),
(4, 'Nombre', NULL, 'Dodonas', NULL, 'V-12343345', '04241234326', 'arman@boxeo.com', 1),
(6, 'king', '', 'king', '', '11111111', '04121111112', 'king@gmail.com', 1),
(7, 'ilia', 'Jose', 'White', NULL, 'V-11352677', '04141234567', 'knockouczt@boxeo.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenador_club`
--

CREATE TABLE `entrenador_club` (
  `id_entrenador` int NOT NULL,
  `id_club` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrenador_club`
--

INSERT INTO `entrenador_club` (`id_entrenador`, `id_club`) VALUES
(2, 1),
(7, 1),
(1, 2),
(3, 2),
(6, 3),
(3, 5),
(3, 6),
(7, 6),
(6, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int NOT NULL,
  `nombre_estado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nombre_estado`) VALUES
(1, 'Amazonas'),
(2, 'Anzoátegui'),
(3, 'Apure'),
(4, 'Aragua'),
(5, 'Barinas'),
(6, 'Bolívar'),
(7, 'Carabobo'),
(8, 'Cojedes'),
(9, 'Delta Amacuro'),
(10, 'Distrito Capital'),
(11, 'Falcón'),
(12, 'Guárico'),
(13, 'Lara'),
(16, 'Mérida'),
(14, 'Miranda'),
(15, 'Monagas'),
(17, 'Nueva Esparta'),
(18, 'Portuguesa'),
(19, 'Sucre'),
(21, 'Táchira'),
(20, 'Trujillo'),
(22, 'Vargas'),
(23, 'Yaracuy'),
(24, 'Zulia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jueces`
--

CREATE TABLE `jueces` (
  `id_juez` int NOT NULL,
  `pnombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `papellido` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jueces`
--

INSERT INTO `jueces` (`id_juez`, `pnombre`, `papellido`, `cedula`, `activo`) VALUES
(1, 'Juez', 'Uno', 'J10000001', 1),
(2, 'Juez', 'Dos', 'J10000002', 1),
(3, 'Juez', 'Tres', 'J10000003', 1),
(4, 'jesus', 'Martinez', 'V-26945389', 1),
(5, 'Jesús', 'Martinez', 'Z-25854009', 1),
(6, 'Jesús', 'topuria', 'E-25854009', 1),
(7, 'Khamzat', 'Chimaev', 'V-26945333', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int NOT NULL,
  `id_estado` int NOT NULL,
  `nombre_municipio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `id_estado`, `nombre_municipio`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Atabapo'),
(3, 1, 'Atures'),
(4, 1, 'Autana'),
(5, 1, 'Manapiare'),
(6, 1, 'Maroa'),
(7, 1, 'Río Negro'),
(8, 2, 'Anaco'),
(9, 2, 'Aragua'),
(10, 2, 'Bolívar'),
(11, 2, 'Bruzual'),
(12, 2, 'Cajigal'),
(13, 2, 'Carvajal'),
(14, 2, 'Diego Bautista Urbaneja'),
(15, 2, 'Freites'),
(16, 2, 'Guanipa'),
(17, 2, 'Guanta'),
(18, 2, 'Independencia'),
(19, 2, 'Libertad'),
(20, 2, 'McGregor'),
(21, 2, 'Miranda'),
(22, 2, 'Monagas'),
(23, 2, 'Peñalver'),
(24, 2, 'Píritu'),
(25, 2, 'San Juan de Capistrano'),
(26, 2, 'Santa Ana'),
(27, 2, 'Simón Rodríguez'),
(28, 2, 'Sotillo'),
(29, 3, 'Achaguas'),
(30, 3, 'Biruaca'),
(31, 3, 'Muñoz'),
(32, 3, 'Pedro Camejo'),
(33, 3, 'Páez'),
(34, 3, 'Rómulo Gallegos'),
(35, 3, 'San Fernando'),
(36, 4, 'Bolívar'),
(37, 4, 'Camatagua'),
(38, 4, 'Francisco Linares Alcántara'),
(39, 4, 'Girardot'),
(40, 4, 'José Félix Ribas'),
(41, 4, 'José Rafael Revenga'),
(42, 4, 'José Ángel Lamas'),
(43, 4, 'Libertador'),
(44, 4, 'Mario Briceño Iragorry'),
(45, 4, 'Ocumare de la Costa de Oro'),
(46, 4, 'San Casimiro'),
(47, 4, 'San Sebastián'),
(48, 4, 'Santiago Mariño'),
(49, 4, 'Santos Michelena'),
(50, 4, 'Sucre'),
(51, 4, 'Tovar'),
(52, 4, 'Urdaneta'),
(53, 4, 'Zamora'),
(54, 5, 'Alberto Arvelo Torrealba'),
(55, 5, 'Andrés Eloy Blanco'),
(56, 5, 'Antonio José de Sucre'),
(57, 5, 'Arismendi'),
(58, 5, 'Barinas'),
(59, 5, 'Bolívar'),
(60, 5, 'Cruz Paredes'),
(61, 5, 'Ezequiel Zamora'),
(62, 5, 'Obispos'),
(63, 5, 'Pedraza'),
(64, 5, 'Rojas'),
(65, 5, 'Sosa'),
(66, 6, 'Angostura'),
(67, 6, 'Caroní'),
(68, 6, 'Cedeño'),
(69, 6, 'El Callao'),
(70, 6, 'Gran Sabana'),
(71, 6, 'Heres'),
(72, 6, 'Padre Pedro Chien'),
(73, 6, 'Píar'),
(74, 6, 'Roscio'),
(75, 6, 'Sifontes'),
(76, 6, 'Sucre'),
(77, 7, 'Bejuma'),
(78, 7, 'Carlos Arvelo'),
(79, 7, 'Diego Ibarra'),
(80, 7, 'Guacara'),
(81, 7, 'Libertador'),
(82, 7, 'Los Guayos'),
(83, 7, 'Miranda'),
(84, 7, 'Montalbán'),
(85, 7, 'Mora'),
(86, 7, 'Naguanagua'),
(87, 7, 'Puerto Cabello'),
(88, 7, 'San Diego'),
(89, 7, 'San Joaquín'),
(90, 7, 'Valencia'),
(91, 8, 'Anzoátegui'),
(92, 8, 'Girardot'),
(93, 8, 'Lima Blanco'),
(94, 8, 'Pao de San Juan Bautista'),
(95, 8, 'Ricaurte'),
(96, 8, 'Rómulo Gallegos'),
(97, 8, 'San Carlos'),
(98, 8, 'Tinaco'),
(99, 8, 'Tinaquillo'),
(100, 9, 'Antonio Díaz'),
(101, 9, 'Casacoima'),
(102, 9, 'Pedernales'),
(103, 9, 'Tucupita'),
(104, 10, 'Libertador'),
(105, 11, 'Acosta'),
(106, 11, 'Bolívar'),
(107, 11, 'Buchivacoa'),
(108, 11, 'Carirubana'),
(109, 11, 'Colina'),
(110, 11, 'Dabajuro'),
(111, 11, 'Democracia'),
(112, 11, 'Falcón'),
(113, 11, 'Federación'),
(114, 11, 'Jacura'),
(115, 11, 'Los Taques'),
(116, 11, 'Manaure'),
(117, 11, 'Mauroa'),
(118, 11, 'Miranda'),
(119, 11, 'Monseñor Iturriza'),
(120, 11, 'Palmasola'),
(121, 11, 'Petit'),
(122, 11, 'Píritu'),
(123, 11, 'San Francisco'),
(124, 11, 'Silva'),
(125, 11, 'Sucre'),
(126, 11, 'Tocópero'),
(127, 11, 'Unión'),
(128, 11, 'Urumaco'),
(129, 11, 'Zamora'),
(130, 12, 'Camaguán'),
(131, 12, 'Chaguaramas'),
(132, 12, 'El Socorro'),
(133, 12, 'Infante'),
(134, 12, 'Las Mercedes'),
(135, 12, 'Mellado'),
(136, 12, 'Miranda'),
(137, 12, 'Monagas'),
(138, 12, 'Ortiz'),
(139, 12, 'Ribas'),
(140, 12, 'Roscio'),
(141, 12, 'San Gerónimo de Guayabal'),
(142, 12, 'San José de Guaribe'),
(143, 12, 'Santa María de Ipire'),
(144, 12, 'Zaraza'),
(145, 13, 'Andrés Eloy Blanco'),
(146, 13, 'Crespo'),
(147, 13, 'Iribarren'),
(148, 13, 'Jiménez'),
(149, 13, 'Morán'),
(150, 13, 'Palavecino'),
(151, 13, 'Simón Planas'),
(152, 13, 'Torres'),
(153, 13, 'Urdaneta'),
(154, 14, 'Acevedo'),
(155, 14, 'Andrés Bello'),
(156, 14, 'Baruta'),
(157, 14, 'Brión'),
(158, 14, 'Buroz'),
(159, 14, 'Carrizal'),
(160, 14, 'Chacao'),
(161, 14, 'Cristóbal Rojas'),
(162, 14, 'El Hatillo'),
(163, 14, 'Guaicaipuro'),
(164, 14, 'Independencia'),
(165, 14, 'Lander'),
(166, 14, 'Los Salias'),
(167, 14, 'Paz Castillo'),
(168, 14, 'Pedro Gual'),
(169, 14, 'Plaza'),
(170, 14, 'Páez'),
(171, 14, 'Simón Bolívar'),
(172, 14, 'Sucre'),
(173, 14, 'Urdaneta'),
(174, 14, 'Zamora'),
(175, 15, 'Acosta'),
(176, 15, 'Aguasay'),
(177, 15, 'Bolívar'),
(178, 15, 'Caripe'),
(179, 15, 'Cedeño'),
(180, 15, 'Ezequiel Zamora'),
(181, 15, 'Libertador'),
(182, 15, 'Maturín'),
(183, 15, 'Piar'),
(184, 15, 'Punceres'),
(185, 15, 'Santa Bárbara'),
(186, 15, 'Sotillo'),
(187, 15, 'Uracoa'),
(188, 16, 'Alberto Adriani'),
(189, 16, 'Andrés Bello'),
(190, 16, 'Antonio Pinto Salinas'),
(191, 16, 'Aricagua'),
(192, 16, 'Arzobispo Chacón'),
(193, 16, 'Campo Elías'),
(194, 16, 'Caracciolo Parra Olmedo'),
(195, 16, 'Cardenal Quintero'),
(196, 16, 'Guaraque'),
(197, 16, 'Julio César Salas'),
(198, 16, 'Justo Briceño'),
(199, 16, 'Libertador'),
(200, 16, 'Miranda'),
(201, 16, 'Obispo Ramos de Lora'),
(202, 16, 'Padre Noguera'),
(203, 16, 'Pueblo Llano'),
(204, 16, 'Rangel'),
(205, 16, 'Rivas Dávila'),
(206, 16, 'Santos Marquina'),
(207, 16, 'Sucre'),
(208, 16, 'Tovar'),
(209, 16, 'Tulio Febres Cordero'),
(210, 16, 'Zea'),
(211, 17, 'Antolín del Campo'),
(212, 17, 'Arismendi'),
(213, 17, 'Díaz'),
(214, 17, 'García'),
(215, 17, 'Gómez'),
(216, 17, 'Maneiro'),
(217, 17, 'Marcano'),
(218, 17, 'Mariño'),
(219, 17, 'Península de Macanao'),
(220, 17, 'Tubores'),
(221, 17, 'Villalba'),
(222, 18, 'Agua Blanca'),
(223, 18, 'Araure'),
(224, 18, 'Esteller'),
(225, 18, 'Guanare'),
(226, 18, 'Guanarito'),
(227, 18, 'Monseñor José Vicente de Unda'),
(228, 18, 'Ospino'),
(229, 18, 'Papelón'),
(230, 18, 'Páez'),
(231, 18, 'San Genaro de Boconoíto'),
(232, 18, 'San Rafael de Onoto'),
(233, 18, 'Santa Rosalía'),
(234, 18, 'Sucre'),
(235, 18, 'Turén'),
(236, 19, 'Andrés Eloy Blanco'),
(237, 19, 'Andrés Mata'),
(238, 19, 'Arismendi'),
(239, 19, 'Benítez'),
(240, 19, 'Bermúdez'),
(241, 19, 'Bolívar'),
(242, 19, 'Cajigal'),
(243, 19, 'Cruz Salmerón Acosta'),
(244, 19, 'Libertador'),
(245, 19, 'Mariño'),
(246, 19, 'Mejía'),
(247, 19, 'Montes'),
(248, 19, 'Ribero'),
(249, 19, 'Sucre'),
(250, 19, 'Valdez'),
(251, 20, 'Andrés Bello'),
(252, 20, 'Boconó'),
(253, 20, 'Bolívar'),
(254, 20, 'Candelaria'),
(255, 20, 'Carache'),
(256, 20, 'Escuque'),
(257, 20, 'José Felipe Márquez Cañizalez'),
(258, 20, 'Juan Vicente Campos Elías'),
(259, 20, 'La Ceiba'),
(260, 20, 'Miranda'),
(261, 20, 'Monte Carmelo'),
(262, 20, 'Motatán'),
(263, 20, 'Pampanito'),
(264, 20, 'Pampán'),
(265, 20, 'Rafael Rangel'),
(266, 20, 'San Rafael de Carvajal'),
(267, 20, 'Sucre'),
(268, 20, 'Trujillo'),
(269, 20, 'Urdaneta'),
(270, 20, 'Valera'),
(271, 21, 'Andrés Bello'),
(272, 21, 'Antonio Rómulo Costa'),
(273, 21, 'Ayacucho'),
(274, 21, 'Bolívar'),
(275, 21, 'Cárdenas'),
(276, 21, 'Córdoba'),
(277, 21, 'Fernández Feo'),
(278, 21, 'Francisco de Miranda'),
(279, 21, 'García de Hevia'),
(280, 21, 'Guásimos'),
(281, 21, 'Independencia'),
(282, 21, 'José María Vargas'),
(283, 21, 'Junín'),
(284, 21, 'Jáuregui'),
(285, 21, 'Libertad'),
(286, 21, 'Libertador'),
(287, 21, 'Lobatera'),
(288, 21, 'Michelena'),
(289, 21, 'Panamericano'),
(290, 21, 'Pedro María Ureña'),
(291, 21, 'Rafael Urdaneta'),
(292, 21, 'Samuel Darío Maldonado'),
(293, 21, 'San Cristóbal'),
(294, 21, 'San Judas Tadeo'),
(295, 21, 'Seboruco'),
(296, 21, 'Simón Rodríguez'),
(297, 21, 'Sucre'),
(298, 21, 'Torbes'),
(299, 21, 'Uribante'),
(300, 22, 'Vargas'),
(301, 23, 'Arístides Bastidas'),
(302, 23, 'Bolívar'),
(303, 23, 'Bruzual'),
(304, 23, 'Cocorote'),
(305, 23, 'Independencia'),
(306, 23, 'José Antonio Páez'),
(307, 23, 'La Trinidad'),
(308, 23, 'Manuel Monge'),
(309, 23, 'Nirgua'),
(310, 23, 'Peña'),
(311, 23, 'San Felipe'),
(312, 23, 'Sucre'),
(313, 23, 'Urachiche'),
(314, 23, 'Veroes'),
(315, 24, 'Almirante Padilla'),
(316, 24, 'Baralt'),
(317, 24, 'Cabimas'),
(318, 24, 'Catatumbo'),
(319, 24, 'Colón'),
(320, 24, 'Francisco Javier Pulgar'),
(321, 24, 'Jesús Enrique Lossada'),
(322, 24, 'Jesús María Semprún'),
(323, 24, 'La Cañada de Urdaneta'),
(324, 24, 'Lagunillas'),
(325, 24, 'Machiques de Perijá'),
(326, 24, 'Mara'),
(327, 24, 'Maracaibo'),
(328, 24, 'Miranda'),
(329, 24, 'Páez'),
(330, 24, 'Rosario de Perijá'),
(331, 24, 'San Francisco'),
(332, 24, 'Santa Rita'),
(333, 24, 'Simón Bolívar'),
(334, 24, 'Sucre'),
(335, 24, 'Valmore Rodríguez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquias`
--

CREATE TABLE `parroquias` (
  `id_parroquia` int NOT NULL,
  `id_municipio` int NOT NULL,
  `nombre_parroquia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `parroquias`
--

INSERT INTO `parroquias` (`id_parroquia`, `id_municipio`, `nombre_parroquia`) VALUES
(1, 1, 'Alto Orinoco La Esmeralda'),
(2, 1, 'Huachamacare Acanaña'),
(3, 1, 'Marawaka Toky Shamanaña'),
(4, 1, 'Mavaka Mavaka'),
(5, 1, 'Sierra Parima Parimabé'),
(6, 2, 'Caname Guarinuma'),
(7, 2, 'Ucata Laja Lisa'),
(8, 2, 'Yapacana Macuruco'),
(9, 3, 'Fernando Girón Tovar'),
(10, 3, 'Luis Alberto Gómez'),
(11, 3, 'Pahueña Limón de Parhueña'),
(12, 3, 'Platanillal Platanillal'),
(13, 4, 'Guayapo San Pedro del Orinoco'),
(14, 4, 'Munduapo Munduapo'),
(15, 4, 'Samariapo Samariapo'),
(16, 4, 'Sipapo Pendare'),
(17, 5, 'Alto Ventuari Cacurí'),
(18, 5, 'Bajo Ventuari Marueta'),
(19, 5, 'Medio Ventuari Manami'),
(20, 6, 'Comunidad'),
(21, 6, 'Victorino'),
(22, 7, 'Casiquiare Curimacare'),
(23, 7, 'Cocuy'),
(24, 7, 'San Carlos de Río Negro'),
(25, 7, 'Solano Solano'),
(26, 8, 'Anaco'),
(27, 8, 'San Joaquín'),
(28, 9, 'Aragua de Barcelona'),
(29, 9, 'Cachipo'),
(30, 10, 'Bergatín'),
(31, 10, 'Caigua'),
(32, 10, 'El Carmen.'),
(33, 10, 'El Pilar'),
(34, 10, 'Naricual.'),
(35, 10, 'San Cristóbal'),
(36, 11, 'Clarines'),
(37, 11, 'Guanape'),
(38, 11, 'Sabana de Uchire'),
(39, 12, 'Onoto'),
(40, 12, 'San Pablo'),
(41, 13, 'Santa Bárbara'),
(42, 13, 'Valle de Guanape'),
(43, 14, 'El Morro'),
(44, 14, 'Lechería'),
(45, 15, 'Cantaura'),
(46, 15, 'Libertador'),
(47, 15, 'Santa Rosa'),
(48, 15, 'Urica'),
(49, 16, 'San José de Guanipa'),
(50, 17, 'Chorrerón'),
(51, 17, 'Guanta'),
(52, 18, 'Mamo'),
(53, 18, 'Soledad'),
(54, 19, 'El Carito'),
(55, 19, 'La Romereña'),
(56, 19, 'San Mateo'),
(57, 19, 'Santa Inés'),
(58, 20, 'Calatrava'),
(59, 20, 'El Chaparro'),
(60, 20, 'Tomás Alfaro'),
(61, 21, 'Atapirire'),
(62, 21, 'Boca del Pao'),
(63, 21, 'El Pao'),
(64, 21, 'Pariaguán'),
(65, 22, 'Mapire'),
(66, 22, 'Piar'),
(67, 22, 'San Diego de Cabrutica'),
(68, 22, 'Santa Clara'),
(69, 22, 'Uverito'),
(70, 22, 'Zuata'),
(71, 23, 'Puerto Píritu'),
(72, 23, 'San Miguel'),
(73, 23, 'Sucre'),
(74, 24, 'Píritu'),
(75, 24, 'San Francisco'),
(76, 25, 'Boca de Chávez'),
(77, 25, 'Boca de Uchire'),
(78, 26, 'Pueblo Nuevo'),
(79, 26, 'Santa Ana'),
(80, 27, 'Edmundo Barrios'),
(81, 27, 'Miguel Otero Silva'),
(82, 28, 'Pozuelos'),
(83, 28, 'Puerto La Cruz'),
(84, 29, 'Achaguas'),
(85, 29, 'Apurito'),
(86, 29, 'El Yagual'),
(87, 29, 'Guachara'),
(88, 29, 'Mucuritas'),
(89, 29, 'Queseras del medio'),
(90, 30, 'Biruaca'),
(91, 31, 'Bruzual'),
(92, 31, 'Mantecal'),
(93, 31, 'Quintero'),
(94, 31, 'Rincón Hondo'),
(95, 31, 'San Vicente'),
(96, 32, 'Codazzi'),
(97, 32, 'Cunaviche'),
(98, 32, 'San Juan de Payara'),
(99, 33, 'Aramendi'),
(100, 33, 'El Amparo'),
(101, 33, 'Guasdualito'),
(102, 33, 'San Camilo'),
(103, 33, 'Urdaneta'),
(104, 34, 'Elorza'),
(105, 34, 'La Trinidad'),
(106, 35, 'El Recreo'),
(107, 35, 'Peñalver'),
(108, 35, 'San Fernando'),
(109, 35, 'San Rafael de Atamaica'),
(110, 36, 'Bolívar'),
(111, 37, 'Camatagua'),
(112, 37, 'Carmen de Cura'),
(113, 38, 'Francisco de Miranda'),
(114, 38, 'Moseñor Feliciano González'),
(115, 38, 'Santa Rita'),
(116, 39, 'Andrés Eloy Blanco'),
(117, 39, 'Choroní'),
(118, 39, 'Joaquín Crespo'),
(119, 39, 'José Casanova Godoy'),
(120, 39, 'Las Delicias'),
(121, 39, 'Los Tacarigua'),
(122, 39, 'Madre María de San José'),
(123, 39, 'Pedro José Ovalles'),
(124, 40, 'Castor Nieves Ríos'),
(125, 40, 'José Félix Ribas'),
(126, 40, 'Las Guacamayas'),
(127, 40, 'Pao de Zárate'),
(128, 40, 'Zuata'),
(129, 41, 'José Rafael Revenga'),
(130, 42, 'Santa Cruz'),
(131, 43, 'Palo Negro'),
(132, 43, 'San Martín de Porres'),
(133, 44, 'Caña de Azúcar'),
(134, 44, 'El Limón'),
(135, 45, 'Ocumare de la Costa'),
(136, 46, 'Güiripa'),
(137, 46, 'Ollas de Caramacate'),
(138, 46, 'San Casimiro'),
(139, 46, 'Valle Morín'),
(140, 47, 'San Sebastián'),
(141, 48, 'Alfredo Pacheco Miranda'),
(142, 48, 'Arevalo Aponte'),
(143, 48, 'Chuao'),
(144, 48, 'Samán de Güere'),
(145, 48, 'Turmero'),
(146, 49, 'Santos Michelena'),
(147, 49, 'Tiara'),
(148, 50, 'Bella Vista'),
(149, 50, 'Cagua'),
(150, 51, 'Tovar'),
(151, 52, 'Las Peñitas'),
(152, 52, 'San Francisco de Cara'),
(153, 52, 'Taguay'),
(154, 52, 'Urdaneta'),
(155, 53, 'Augusto Mijares'),
(156, 53, 'Magdaleno'),
(157, 53, 'San Francisco de Asís'),
(158, 53, 'Valles de Tucutunemo'),
(159, 53, 'Zamora'),
(160, 54, 'Juan Antonio Rodríguez Domínguez'),
(161, 54, 'Sabaneta'),
(162, 55, 'El Cantón'),
(163, 55, 'Puerto Vivas'),
(164, 55, 'Santa Cruz de Guacas'),
(165, 56, 'Andrés Bello'),
(166, 56, 'Nicolás Pulido'),
(167, 56, 'Ticoporo'),
(168, 57, 'Arismendi'),
(169, 57, 'Guadarrama'),
(170, 57, 'La Unión'),
(171, 57, 'San Antonio'),
(172, 58, 'Alberto Arvelo Larriva'),
(173, 58, 'Alto Barinas'),
(174, 58, 'Barinas'),
(175, 58, 'Corazón de Jesús'),
(176, 58, 'Dominga Ortiz de Páez'),
(177, 58, 'El Carmen'),
(178, 58, 'Juan Antonio Rodríguez Domínguez'),
(179, 58, 'Manuel Palacio Fajardo'),
(180, 58, 'Ramón Ignacio Méndez'),
(181, 58, 'Rómulo Betancourt'),
(182, 58, 'San Silvestre'),
(183, 58, 'Santa Inés'),
(184, 58, 'Santa Lucía'),
(185, 58, 'Torunos'),
(186, 59, 'Altamira de Cáceres'),
(187, 59, 'Barinitas'),
(188, 59, 'Calderas'),
(189, 60, 'Barrancas'),
(190, 60, 'El Socorro'),
(191, 60, 'Mazparrito'),
(192, 61, 'José Ignacio del Pumar'),
(193, 61, 'Pedro Briceño Méndez'),
(194, 61, 'Ramón Ignacio Méndez'),
(195, 61, 'Santa Bárbara'),
(196, 62, 'El Real'),
(197, 62, 'La Luz'),
(198, 62, 'Los Guasimitos'),
(199, 62, 'Obispos'),
(200, 63, 'Ciudad Bolívia'),
(201, 63, 'José Félix Ribas'),
(202, 63, 'José Ignacio Briceño'),
(203, 63, 'Páez'),
(204, 64, 'Dolores'),
(205, 64, 'Libertad'),
(206, 64, 'Palacio Fajardo'),
(207, 64, 'Santa Rosa'),
(208, 64, 'Simón Rodríguez'),
(209, 65, 'Ciudad de Nutrias'),
(210, 65, 'El Regalo'),
(211, 65, 'Puerto Nutrias'),
(212, 65, 'Santa Catalina'),
(213, 65, 'Simón Bolívar'),
(214, 66, 'Barceloneta'),
(215, 66, 'Raúl Leoni'),
(216, 66, 'San Francisco'),
(217, 66, 'Santa Bárbara'),
(218, 67, '5 de Julio'),
(219, 67, 'Cachamay'),
(220, 67, 'Chirica'),
(221, 67, 'Dalla Costa'),
(222, 67, 'Once de Abril'),
(223, 67, 'Pozo Verde'),
(224, 67, 'Simón Bolívar'),
(225, 67, 'Unare'),
(226, 67, 'Universidad'),
(227, 67, 'Vista al Sol'),
(228, 67, 'Yocoima'),
(229, 68, 'Altagracia'),
(230, 68, 'Ascensión Farreras'),
(231, 68, 'Cedeño'),
(232, 68, 'Guaniamo'),
(233, 68, 'La Urbana'),
(234, 68, 'Pijiguaos'),
(235, 69, 'El Callao'),
(236, 70, 'Gran Sabana'),
(237, 70, 'Ikabarú'),
(238, 71, 'Agua Salada'),
(239, 71, 'Catedral'),
(240, 71, 'José Antonio Páez'),
(241, 71, 'La Sabanita'),
(242, 71, 'Marhuanta'),
(243, 71, 'Orinoco'),
(244, 71, 'Panapana'),
(245, 71, 'Vista Hermosa'),
(246, 71, 'Zea'),
(247, 72, 'Padre Pedro Chien'),
(248, 72, 'Río Grande'),
(249, 73, 'Andrés Eloy Blanco'),
(250, 73, 'Pedro Cova'),
(251, 74, 'Roscio'),
(252, 74, 'Salóm'),
(253, 75, 'Dalla Costa'),
(254, 75, 'San Isidro'),
(255, 75, 'Sifontes'),
(256, 76, 'Aripao'),
(257, 76, 'Guarataro'),
(258, 76, 'Las Majadas'),
(259, 76, 'Moitaco'),
(260, 76, 'Sucre'),
(261, 77, 'Bejuma'),
(262, 77, 'Canoabo'),
(263, 77, 'Simón Bolívar'),
(264, 78, 'Carabobo'),
(265, 78, 'Güigüe'),
(266, 78, 'Tacarigua'),
(267, 79, 'Aguas Calientes'),
(268, 79, 'Mariara'),
(269, 80, 'Ciudad Alianza'),
(270, 80, 'Guacara'),
(271, 80, 'Yagua'),
(272, 81, 'Campo Carabobo'),
(273, 81, 'Valencia'),
(274, 82, 'Los Guayos'),
(275, 83, 'Miranda'),
(276, 84, 'Montalbán'),
(277, 85, 'Morón'),
(278, 85, 'Urama'),
(279, 86, 'Naguanagua'),
(280, 87, 'Bartolomé Salóm'),
(281, 87, 'Borburata'),
(282, 87, 'Democracia'),
(283, 87, 'Fraternidad'),
(284, 87, 'Goaigoaza'),
(285, 87, 'Juan José Flores'),
(286, 87, 'Patanemo'),
(287, 87, 'Unión'),
(288, 88, 'San Diego'),
(289, 89, 'San Joaquín'),
(290, 90, 'No Urbana Negro Primero'),
(291, 90, 'Urbana Candelaria'),
(292, 90, 'Urbana Catedral'),
(293, 90, 'Urbana El Socorro'),
(294, 90, 'Urbana Miguel Peña'),
(295, 90, 'Urbana Rafael Urdaneta'),
(296, 90, 'Urbana San Blas'),
(297, 90, 'Urbana San José'),
(298, 90, 'Urbana Santa Rosa'),
(299, 91, 'Cojedes'),
(300, 91, 'Juan de Mata Suárez'),
(301, 92, 'El Baúl'),
(302, 92, 'Sucre'),
(303, 93, 'La Aguadita'),
(304, 93, 'Macapo'),
(305, 94, 'El Pao'),
(306, 95, 'El Amparo'),
(307, 95, 'Libertad de Cojedes'),
(308, 96, 'Rómulo Gallegos'),
(309, 97, 'Juan Ángel Bravo'),
(310, 97, 'Manuel Manrique'),
(311, 97, 'San Carlos de Austria'),
(312, 98, 'General en Jefe José Laurencio Silva'),
(313, 99, 'Tinaquillo'),
(314, 100, 'Almirante Luis Brión'),
(315, 100, 'Curiapo'),
(316, 100, 'Francisco Aniceto Lugo Boca de Cuyubini'),
(317, 100, 'Manuel Renaud'),
(318, 100, 'Padre Barral'),
(319, 100, 'Santos de Abelgas'),
(320, 101, 'Cinco de Julio Piacoa'),
(321, 101, 'Imataca Moruca'),
(322, 101, 'Juan Bautista Arismendi'),
(323, 101, 'Manuel Piar Santa Catalina'),
(324, 101, 'Rómulo Gallegos'),
(325, 102, 'Luis Beltrán Prieto Figueroa'),
(326, 102, 'Pedernales'),
(327, 103, 'José Vidal Marcano Caparal de Guara'),
(328, 103, 'Juan Millán'),
(329, 103, 'Leonardo Ruíz Pineda Paloma'),
(330, 103, 'Mariscal Antonio José de Sucre'),
(331, 103, 'Monseñor Argimiro García San Rafael'),
(332, 103, 'San José'),
(333, 103, 'San Rafael'),
(334, 103, 'Virgen del Valle'),
(335, 104, '23 de enero'),
(336, 104, 'Altagracia'),
(337, 104, 'Antímano'),
(338, 104, 'Candelaria'),
(339, 104, 'Caricuao'),
(340, 104, 'Catedral'),
(341, 104, 'Coche'),
(342, 104, 'El Junquito'),
(343, 104, 'El Paraíso'),
(344, 104, 'El Recreo'),
(345, 104, 'El Valle'),
(346, 104, 'La Pastora'),
(347, 104, 'La Vega'),
(348, 104, 'Macarao'),
(349, 104, 'San Agustín'),
(350, 104, 'San Bernardino'),
(351, 104, 'San José'),
(352, 104, 'San Juan'),
(353, 104, 'San Pedro'),
(354, 104, 'Santa Rosalía'),
(355, 104, 'Santa Teresa'),
(356, 104, 'Sucre (Catia)'),
(357, 105, 'Capadare'),
(358, 105, 'La Pastora'),
(359, 105, 'Libertador'),
(360, 105, 'San Juan de los Cayos'),
(361, 106, 'Aracua'),
(362, 106, 'La Peña'),
(363, 106, 'San Luis'),
(364, 107, 'Bariro'),
(365, 107, 'Borojó'),
(366, 107, 'Capatárida'),
(367, 107, 'Guajiro'),
(368, 107, 'Seque'),
(369, 107, 'Valle de Eroa'),
(370, 107, 'Zazárida'),
(371, 108, 'Carirubana'),
(372, 108, 'Norte'),
(373, 108, 'Santa Ana'),
(374, 108, 'Urbana Punta Cardón'),
(375, 109, 'Acurigua'),
(376, 109, 'Guaibacoa'),
(377, 109, 'Las Calderas'),
(378, 109, 'La Vela de Coro'),
(379, 109, 'Macoruca'),
(380, 110, 'Dabajuro'),
(381, 111, 'Agua Clara'),
(382, 111, 'Avaria'),
(383, 111, 'Pedregal'),
(384, 111, 'Piedra Grande'),
(385, 111, 'Purureche'),
(386, 112, 'Adaure'),
(387, 112, 'Adícora'),
(388, 112, 'Baraived'),
(389, 112, 'Buena Vista'),
(390, 112, 'El Hato'),
(391, 112, 'El Vínculo'),
(392, 112, 'Jadacaquiva'),
(393, 112, 'Moruy'),
(394, 112, 'Pueblo Nuevo'),
(395, 113, 'Agua Larga'),
(396, 113, 'Churuguara'),
(397, 113, 'El Paují'),
(398, 113, 'Independencia'),
(399, 113, 'Mapararí'),
(400, 114, 'Agua Linda'),
(401, 114, 'Araurima'),
(402, 114, 'Jacura'),
(403, 115, 'Judibana'),
(404, 115, 'Los Taques'),
(405, 116, 'Cacique Manaure'),
(406, 117, 'Casigua'),
(407, 117, 'Mene de Mauroa'),
(408, 117, 'San Félix'),
(409, 118, 'Guzmán Guillermo'),
(410, 118, 'Mitare'),
(411, 118, 'Río Seco'),
(412, 118, 'Sabaneta'),
(413, 118, 'San Antonio'),
(414, 118, 'San Gabriel'),
(415, 118, 'Santa Ana'),
(416, 119, 'Boca del Tocuyo'),
(417, 119, 'Chichiriviche'),
(418, 119, 'Tocuyo de la Costa'),
(419, 120, 'Palmasola'),
(420, 121, 'Cabure'),
(421, 121, 'Colina'),
(422, 121, 'Curimagua'),
(423, 122, 'Píritu'),
(424, 122, 'San José de la Costa'),
(425, 123, 'Capital San Francisco'),
(426, 124, 'Boca de Aroa'),
(427, 124, 'Tucacas'),
(428, 125, 'Pecaya'),
(429, 125, 'Sucre'),
(430, 126, 'Tocópero'),
(431, 127, 'El Charal'),
(432, 127, 'Las Vegas del Tuy'),
(433, 127, 'Santa Cruz de Bucaral'),
(434, 128, 'Bruzual'),
(435, 128, 'Urumaco'),
(436, 129, 'La Ciénaga'),
(437, 129, 'La Soledad'),
(438, 129, 'Pueblo Cumarebo'),
(439, 129, 'Puerto Cumarebo'),
(440, 129, 'Zazárida'),
(441, 130, 'Camaguán'),
(442, 130, 'Puerto Miranda'),
(443, 130, 'Uverito'),
(444, 131, 'Chaguaramas'),
(445, 132, 'El Socorro'),
(446, 133, 'Espino'),
(447, 133, 'Valle de la Pascua'),
(448, 134, 'Cabruta'),
(449, 134, 'Las Mercedes'),
(450, 134, 'Santa Rita de Manapire'),
(451, 135, 'El Sombrero'),
(452, 135, 'Sosa'),
(453, 136, 'Capital Urbana Calabozo'),
(454, 136, 'El Calvario'),
(455, 136, 'El Rastro'),
(456, 136, 'Guardatinajas'),
(457, 137, 'Altagracia de Orituco'),
(458, 137, 'Carlos Soublette'),
(459, 137, 'Libertad de Orituco'),
(460, 137, 'Paso Real de Macaira'),
(461, 137, 'San Francisco de Macaira'),
(462, 137, 'San Francisco Javier de Lezama'),
(463, 137, 'San Rafael de Orituco'),
(464, 138, 'Ortiz'),
(465, 138, 'San Francisco de Tiznados'),
(466, 138, 'San José de Tiznados'),
(467, 138, 'San Lorenzo de Tiznados'),
(468, 139, 'San Rafael de Laya'),
(469, 139, 'Tucupido'),
(470, 140, 'Cantagallo'),
(471, 140, 'Parapara'),
(472, 140, 'San Juan de los Morros'),
(473, 141, 'Cazorla'),
(474, 141, 'Guayabal'),
(475, 142, 'San José de Guaribe'),
(476, 142, 'Uveral'),
(477, 143, 'Altamira'),
(478, 143, 'Santa María de Ipire'),
(479, 144, 'Unare'),
(480, 144, 'Zaraza'),
(481, 145, 'Pío Tamayo'),
(482, 145, 'Quebrada Honda de Guache'),
(483, 145, 'Yacambú'),
(484, 146, 'Freitez'),
(485, 146, 'José María Blanco'),
(486, 147, 'Aguedo Felipe Alvarado'),
(487, 147, 'Buena Vista'),
(488, 147, 'Catedral'),
(489, 147, 'Concepción'),
(490, 147, 'El Cují'),
(491, 147, 'Juan de Villegas'),
(492, 147, 'Juárez'),
(493, 147, 'Santa Rosa'),
(494, 147, 'Tamaca'),
(495, 147, 'Unión'),
(496, 148, 'Coronel Mariano Peraza'),
(497, 148, 'Cuara'),
(498, 148, 'Diego de Lozada'),
(499, 148, 'José Bernardo Dorante'),
(500, 148, 'Juan Bautista Rodríguez'),
(501, 148, 'Paraíso de San José'),
(502, 148, 'San Miguel'),
(503, 148, 'Tintorero'),
(504, 149, 'Anzoátegui'),
(505, 149, 'Bolívar'),
(506, 149, 'Guárico'),
(507, 149, 'Hilario Luna y Luna'),
(508, 149, 'Humocaro Alto'),
(509, 149, 'Humocaro Bajo'),
(510, 149, 'La Candelaria'),
(511, 149, 'Morán'),
(512, 150, 'Agua Viva'),
(513, 150, 'Cabudare'),
(514, 150, 'José Gregorio Bastidas'),
(515, 151, 'Buría'),
(516, 151, 'Gustavo Vega'),
(517, 151, 'Sarare'),
(518, 152, 'Altagracia'),
(519, 152, 'Antonio Díaz'),
(520, 152, 'Camacaro'),
(521, 152, 'Castañeda'),
(522, 152, 'Cecilio Zubillaga'),
(523, 152, 'Chiquinquira'),
(524, 152, 'El Blanco'),
(525, 152, 'Espinoza de los Monteros'),
(526, 152, 'Heriberto Arrollo'),
(527, 152, 'Lara'),
(528, 152, 'Las Mercedes'),
(529, 152, 'Manuel Morillo'),
(530, 152, 'Montaña Verde'),
(531, 152, 'Montes de Oca'),
(532, 152, 'Reyes de Vargas'),
(533, 152, 'Torres'),
(534, 152, 'Trinidad Samuel'),
(535, 153, 'Moroturo'),
(536, 153, 'San Miguel'),
(537, 153, 'Siquisique'),
(538, 153, 'Xaguas'),
(539, 154, 'Aragüita'),
(540, 154, 'Arévalo González'),
(541, 154, 'Capaya'),
(542, 154, 'Caucagua'),
(543, 154, 'El Café'),
(544, 154, 'Marizapa'),
(545, 154, 'Panaquire'),
(546, 154, 'Ribas'),
(547, 155, 'Cumbo'),
(548, 155, 'San José de Barlovento'),
(549, 156, 'El Cafetal'),
(550, 156, 'Las Minas'),
(551, 156, 'Nuestra Señora del Rosario'),
(552, 157, 'Curiepe'),
(553, 157, 'Higuerote'),
(554, 157, 'Tacarigua de Brión'),
(555, 158, 'Mamporal'),
(556, 159, 'Carrizal'),
(557, 160, 'Chacao'),
(558, 161, 'Charallave'),
(559, 161, 'Las Brisas'),
(560, 162, 'El Hatillo'),
(561, 163, 'Altagracia de la Montaña'),
(562, 163, 'Cecilio Acosta'),
(563, 163, 'El Jarillo'),
(564, 163, 'Los Teques'),
(565, 163, 'Paracotos'),
(566, 163, 'San Pedro.'),
(567, 163, 'Tácata'),
(568, 164, 'Cartanal'),
(569, 164, 'Santa Teresa del Tuy'),
(570, 165, 'La Democracia'),
(571, 165, 'Ocumare del Tuy'),
(572, 165, 'Santa Bárbara'),
(573, 166, 'San Antonio de los Altos'),
(574, 167, 'Santa Lucía del Tuy'),
(575, 168, 'Cúpira'),
(576, 168, 'Machurucuto'),
(577, 169, 'Guarenas'),
(578, 170, 'El Guapo'),
(579, 170, 'Paparo'),
(580, 170, 'Río Chico'),
(581, 170, 'San Fernando del Guapo'),
(582, 170, 'Tacarigua de la Laguna'),
(583, 171, 'San Antonio de Yare'),
(584, 171, 'San Francisco de Yare'),
(585, 172, 'Caucagüita'),
(586, 172, 'Filas de Mariche'),
(587, 172, 'La Dolorita'),
(588, 172, 'Leoncio Martínez'),
(589, 172, 'Petare'),
(590, 173, 'Cúa'),
(591, 173, 'Nueva Cúa'),
(592, 174, 'Bolívar'),
(593, 174, 'Guatire'),
(594, 175, 'San Antonio de Maturín'),
(595, 175, 'San Francisco de Maturín'),
(596, 176, 'Aguasay'),
(597, 177, 'Caripito'),
(598, 178, 'Caripe'),
(599, 178, 'El Guácharo'),
(600, 178, 'La Guanota'),
(601, 178, 'Sabana de Piedra'),
(602, 178, 'San Agustín'),
(603, 178, 'Teresen'),
(604, 179, 'Areo'),
(605, 179, 'Capital Cedeño'),
(606, 179, 'San Félix de Cantalicio'),
(607, 179, 'Viento Fresco'),
(608, 180, 'El Tejero'),
(609, 180, 'Punta de Mata'),
(610, 181, 'Chaguaramas'),
(611, 181, 'Las Alhuacas'),
(612, 181, 'Tabasca'),
(613, 181, 'Temblador'),
(614, 182, 'Alto de los Godos'),
(615, 182, 'Boquerón'),
(616, 182, 'El Corozo'),
(617, 182, 'El Furrial'),
(618, 182, 'Jusepín'),
(619, 182, 'La Cruz'),
(620, 182, 'La Pica'),
(621, 182, 'Las Cocuizas'),
(622, 182, 'San Simón'),
(623, 182, 'San Vicente'),
(624, 183, 'Aparicio'),
(625, 183, 'Aragua de Maturín'),
(626, 183, 'Chaguamal'),
(627, 183, 'El Pinto'),
(628, 183, 'Guanaguana'),
(629, 183, 'La Toscana'),
(630, 183, 'Taguaya'),
(631, 184, 'Cachipo'),
(632, 184, 'Quiriquire'),
(633, 185, 'Santa Bárbara'),
(634, 186, 'Barrancas'),
(635, 186, 'Los Barrancos de Fajardo'),
(636, 187, 'Uracoa'),
(637, 188, 'Gabriel Picón González'),
(638, 188, 'Héctor Amable Mora'),
(639, 188, 'José Nucete Sardi'),
(640, 188, 'Presidente Betancourt'),
(641, 188, 'Presidente Páez'),
(642, 188, 'Presidente Rómulo Gallegos'),
(643, 188, 'Pulido Méndez'),
(644, 189, 'La Azulita'),
(645, 190, 'Mesa Bolívar'),
(646, 190, 'Mesa de Las Palmas'),
(647, 190, 'Santa Cruz de Mora'),
(648, 191, 'Aricagua'),
(649, 191, 'San Antonio'),
(650, 192, 'Canagua'),
(651, 192, 'Capurí'),
(652, 192, 'Chacantá'),
(653, 192, 'El Molino'),
(654, 192, 'Guaimaral'),
(655, 192, 'Mucuchachí'),
(656, 192, 'Mucutuy'),
(657, 193, 'Acequias'),
(658, 193, 'Fernández Peña'),
(659, 193, 'Jají'),
(660, 193, 'La Mesa'),
(661, 193, 'Matriz'),
(662, 193, 'Montalbán'),
(663, 193, 'San José del Sur'),
(664, 194, 'Florencio Ramírez'),
(665, 194, 'Tucaní'),
(666, 195, 'Las Piedras'),
(667, 195, 'Santo Domingo'),
(668, 196, 'Guaraque'),
(669, 196, 'Mesa de Quintero'),
(670, 196, 'Río Negro'),
(671, 197, 'Arapuey'),
(672, 197, 'Palmira'),
(673, 198, 'San Cristóbal de Torondoy'),
(674, 198, 'Torondoy'),
(675, 199, 'Antonio Spinetti Dini'),
(676, 199, 'Arias'),
(677, 199, 'Caracciolo Parra Pérez'),
(678, 199, 'Domingo Peña'),
(679, 199, 'El Llano'),
(680, 199, 'El Morro'),
(681, 199, 'Gonzalo Picón Febres'),
(682, 199, 'Jacinto Plaza'),
(683, 199, 'Juan Rodríguez Suárez'),
(684, 199, 'Lasso de la Vega'),
(685, 199, 'Los Nevados'),
(686, 199, 'Mariano Picón Salas'),
(687, 199, 'Milla'),
(688, 199, 'Osuna Rodríguez'),
(689, 199, 'Sagrario'),
(690, 200, 'Andrés Eloy Blanco'),
(691, 200, 'La Venta'),
(692, 200, 'Piñango'),
(693, 200, 'Timotes'),
(694, 201, 'Eloy Paredes'),
(695, 201, 'San Rafael de Alcázar'),
(696, 201, 'Santa Elena de Arenales'),
(697, 202, 'Santa María de Caparo'),
(698, 203, 'Pueblo Llano'),
(699, 204, 'Cacute'),
(700, 204, 'La Toma'),
(701, 204, 'Mucuchíes'),
(702, 204, 'Mucurubá'),
(703, 204, 'San Rafael'),
(704, 205, 'Bailadores'),
(705, 205, 'Gerónimo Maldonado'),
(706, 206, 'Tabay'),
(707, 207, 'Chiguará'),
(708, 207, 'Estánquez'),
(709, 207, 'Lagunillas'),
(710, 207, 'La Trampa'),
(711, 207, 'Pueblo Nuevo del Sur'),
(712, 207, 'San Juan'),
(713, 208, 'El Amparo'),
(714, 208, 'El Llano'),
(715, 208, 'San Francisco'),
(716, 208, 'Tovar'),
(717, 209, 'Independencia'),
(718, 209, 'María de la Concepción Palacios Blanco'),
(719, 209, 'Nueva Bolivia'),
(720, 209, 'Santa Apolonia'),
(721, 210, 'Caño El Tigre'),
(722, 210, 'Zea'),
(723, 211, 'Antolín del Campo'),
(724, 212, 'Arismendi'),
(725, 213, 'San Juan Bautista'),
(726, 213, 'Zabala'),
(727, 214, 'Francisco Fajardo'),
(728, 214, 'García'),
(729, 215, 'Bolívar'),
(730, 215, 'Cerro de Matasiete'),
(731, 215, 'Guevara'),
(732, 215, 'Santa Ana'),
(733, 215, 'Sucre'),
(734, 216, 'Aguirre'),
(735, 216, 'Maneiro'),
(736, 217, 'Adrián'),
(737, 217, 'Juan Griego'),
(738, 217, 'Yaguaraparo'),
(739, 218, 'Porlamar'),
(740, 219, 'Boca de Río'),
(741, 219, 'San Francisco de Macanao'),
(742, 220, 'Los Barales'),
(743, 220, 'Tubores'),
(744, 221, 'Vicente Fuentes'),
(745, 221, 'Villalba'),
(746, 222, 'Agua Blanca'),
(747, 223, 'Araure'),
(748, 223, 'Río Acarigua'),
(749, 224, 'Píritu'),
(750, 224, 'Uveral'),
(751, 225, 'Cordova'),
(752, 225, 'Guanare'),
(753, 225, 'San José de la Montaña'),
(754, 225, 'San Juan de Guanaguanare'),
(755, 225, 'Virgen de Coromoto'),
(756, 226, 'Divina Pastora'),
(757, 226, 'Guanarito'),
(758, 226, 'Trinidad de la Capilla'),
(759, 227, 'Peña Blanca'),
(760, 228, 'Aparición'),
(761, 228, 'La Estación'),
(762, 228, 'Ospino'),
(763, 229, 'Caño Delgadito'),
(764, 229, 'Papelón'),
(765, 230, 'Acarigua'),
(766, 230, 'Payara'),
(767, 230, 'Pimpinela'),
(768, 230, 'Ramón Peraza'),
(769, 231, 'Antolín Tovar Anquino'),
(770, 231, 'Boconoíto'),
(771, 232, 'San Rafael de Onoto'),
(772, 232, 'Santa Fé'),
(773, 232, 'Thermo Morales'),
(774, 233, 'El Playón'),
(775, 233, 'Florida'),
(776, 234, 'Biscucuy'),
(777, 234, 'Concepción'),
(778, 234, 'San José de Saguaz'),
(779, 234, 'San Rafael de Palo Alzado'),
(780, 234, 'Uvencio Antonio Velásquez'),
(781, 234, 'Villa Rosa'),
(782, 235, 'Canelones'),
(783, 235, 'San Isidro Labrador'),
(784, 235, 'Santa Cruz'),
(785, 236, 'Mariño'),
(786, 236, 'Rómulo Gallegos'),
(787, 237, 'San José de Aerocuar'),
(788, 237, 'Tavera Acosta'),
(789, 238, 'Antonio José de Sucre'),
(790, 238, 'El Morro de Puerto Santo'),
(791, 238, 'Puerto Santo'),
(792, 238, 'Río Caribe'),
(793, 238, 'San Juan de las Galdonas'),
(794, 239, 'El Pilar'),
(795, 239, 'El Rincón'),
(796, 239, 'General Francisco Antonio Váquez'),
(797, 239, 'Guaraúnos'),
(798, 239, 'Tunapuicito'),
(799, 239, 'Unión'),
(800, 240, 'Bolívar'),
(801, 240, 'Maracapana'),
(802, 240, 'Santa Catalina'),
(803, 240, 'Santa Rosa'),
(804, 240, 'Santa Teresa'),
(805, 242, 'El Paujil'),
(806, 242, 'Libertad'),
(807, 242, 'Yaguaraparo'),
(808, 243, 'Chacopata'),
(809, 243, 'Cruz Salmerón Acosta'),
(810, 243, 'Manicuare'),
(811, 244, 'Campo Elías'),
(812, 244, 'Tunapuy'),
(813, 245, 'Campo Claro'),
(814, 245, 'Irapa'),
(815, 245, 'Maraval'),
(816, 245, 'San Antonio de Irapa'),
(817, 245, 'Soro'),
(818, 246, 'Mejía'),
(819, 247, 'Arenas'),
(820, 247, 'Aricagua'),
(821, 247, 'Cocollar'),
(822, 247, 'Cumanacoa'),
(823, 247, 'San Fernando'),
(824, 247, 'San Lorenzo'),
(825, 248, 'Catuaro'),
(826, 248, 'Rendón'),
(827, 248, 'San Cruz'),
(828, 248, 'Santa María'),
(829, 248, 'Villa Frontado (Muelle de Cariaco)'),
(830, 249, 'Altagracia Cumaná'),
(831, 249, 'Ayacucho Cumaná'),
(832, 249, 'Gran Mariscal'),
(833, 249, 'Raúl Leoni'),
(834, 249, 'San Juan'),
(835, 249, 'Santa Inés Cumaná'),
(836, 249, 'Valentín Valiente Cumaná'),
(837, 250, 'Bideau'),
(838, 250, 'Cristóbal Colón'),
(839, 250, 'Güiria'),
(840, 250, 'Punta de Piedras'),
(841, 251, 'Araguaney'),
(842, 251, 'El Jaguito'),
(843, 251, 'La Esperanza'),
(844, 251, 'Santa Isabel'),
(845, 252, 'Ayacucho'),
(846, 252, 'Boconó'),
(847, 252, 'Burbusay'),
(848, 252, 'El Carmen'),
(849, 252, 'General Ribas'),
(850, 252, 'Guaramacal'),
(851, 252, 'Monseñor Jáuregui'),
(852, 252, 'Mosquey'),
(853, 252, 'Rafael Rangel'),
(854, 252, 'San José'),
(855, 252, 'San Miguel'),
(856, 252, 'Vega de Guaramacal'),
(857, 253, 'Cheregüé'),
(858, 253, 'Granados'),
(859, 253, 'Sabana Grande'),
(860, 254, 'Arnoldo Gabaldón'),
(861, 254, 'Bolivia'),
(862, 254, 'Carrillo'),
(863, 254, 'Cegarra'),
(864, 254, 'Chejendé'),
(865, 254, 'Manuel Salvador Ulloa'),
(866, 254, 'San José'),
(867, 255, 'Carache'),
(868, 255, 'Cuicas'),
(869, 255, 'La Concepción'),
(870, 255, 'Panamericana'),
(871, 255, 'Santa Cruz'),
(872, 256, 'Escuque'),
(873, 256, 'La Unión (El Alto Escuque)'),
(874, 256, 'Sabana Libre'),
(875, 256, 'Santa Rita'),
(876, 257, 'Antonio José de Sucre'),
(877, 257, 'El Socorro'),
(878, 257, 'Los Caprichos'),
(879, 258, 'Arnoldo Gabaldón'),
(880, 258, 'Campo Elías'),
(881, 259, 'El Progreso'),
(882, 259, 'La Ceiba'),
(883, 259, 'Santa Apolonia'),
(884, 259, 'Tres de Febrero'),
(885, 260, 'Agua Caliente'),
(886, 260, 'Agua Santa'),
(887, 260, 'El Cenizo'),
(888, 260, 'El Dividive'),
(889, 260, 'Valerita'),
(890, 261, 'Buena Vista'),
(891, 261, 'Monte Carmelo'),
(892, 261, 'Santa María del Horcón'),
(893, 262, 'El Baño'),
(894, 262, 'Jalisco'),
(895, 262, 'Motatán'),
(896, 263, 'La Concepción'),
(897, 263, 'Pampanito'),
(898, 263, 'Pampanito II'),
(899, 264, 'Flor de Patria'),
(900, 264, 'La Paz'),
(901, 264, 'Pampán'),
(902, 264, 'Santa Ana'),
(903, 265, 'Betijoque'),
(904, 265, 'José Gregorio Hernández'),
(905, 265, 'La Pueblita'),
(906, 265, 'Los Cedros'),
(907, 266, 'Antonio Nicolás Briceño'),
(908, 266, 'Campo Alegre'),
(909, 266, 'Carvajal'),
(910, 266, 'José Leonardo Suárez'),
(911, 267, 'El Paraíso'),
(912, 267, 'Junín'),
(913, 267, 'Sabana de Mendoza'),
(914, 267, 'Valmore Rodríguez'),
(915, 268, 'Andrés Linares'),
(916, 268, 'Chiquinquirá'),
(917, 268, 'Cristóbal Mendoza'),
(918, 268, 'Cruz Carrillo'),
(919, 268, 'Matriz'),
(920, 268, 'Monseñor Carrillo'),
(921, 268, 'Tres Esquinas'),
(922, 269, 'Cabimbú'),
(923, 269, 'Jajó'),
(924, 269, 'La Mesa de Esnujaque'),
(925, 269, 'La Quebrada'),
(926, 269, 'Santiago'),
(927, 269, 'Tuñame'),
(928, 270, 'Juan Ignacio Montilla'),
(929, 270, 'La Beatriz'),
(930, 270, 'La Puerta'),
(931, 270, 'Mendoza del Valle de Momboy'),
(932, 270, 'Mercedes Díaz'),
(933, 270, 'San Luis'),
(934, 271, 'Andrés Bello'),
(935, 272, 'Antonio Rómulo Costa'),
(936, 273, 'Ayacucho'),
(937, 273, 'Rivas Berti'),
(938, 273, 'San Pedro del Río'),
(939, 274, 'Bolívar'),
(940, 274, 'General Juan Vicente Gómez'),
(941, 274, 'Isaías Medina Angarita'),
(942, 274, 'Palotal'),
(943, 275, 'Amenodoro Rangel Lamus'),
(944, 275, 'Cárdenas'),
(945, 275, 'La Florida'),
(946, 276, 'Córdoba'),
(947, 277, 'Alberto Adriani'),
(948, 277, 'Fernández Feo'),
(949, 277, 'Santo Domingo'),
(950, 278, 'Francisco de Miranda'),
(951, 279, 'Boca de Grita'),
(952, 279, 'García de Hevia'),
(953, 279, 'José Antonio Páez'),
(954, 280, 'Guásimos'),
(955, 281, 'Independencia'),
(956, 281, 'Juan Germán Roscio'),
(957, 281, 'Román Cárdenas'),
(958, 282, 'José María Vargas'),
(959, 283, 'Bramón'),
(960, 283, 'Junín'),
(961, 283, 'La Petrólea'),
(962, 283, 'Quinimarí'),
(963, 284, 'Emilio Constantino Guerrero'),
(964, 284, 'Jáuregui'),
(965, 284, 'Monseñor Miguel Antonio Salas'),
(966, 285, 'Cipriano Castro'),
(967, 285, 'Libertad'),
(968, 285, 'Manuel Felipe Rugeles'),
(969, 286, 'Capital Libertador'),
(970, 286, 'Doradas'),
(971, 286, 'Emeterio Ochoa'),
(972, 286, 'San Joaquín de Navay'),
(973, 287, 'Constitución'),
(974, 287, 'Lobatera'),
(975, 288, 'Michelena'),
(976, 289, 'La Palmita'),
(977, 289, 'Panamericano'),
(978, 290, 'Nueva Arcadia'),
(979, 290, 'Pedro María Ureña'),
(980, 291, 'Delicias'),
(981, 291, 'Pecaya'),
(982, 292, 'Boconó'),
(983, 292, 'Hernández'),
(984, 292, 'Samuel Darío Maldonado'),
(985, 293, 'Dr. Francisco Romero Lobo'),
(986, 293, 'La Concordia'),
(987, 293, 'Pedro María Morantes'),
(988, 293, 'San Juan Bautista'),
(989, 293, 'San Sebastián'),
(990, 294, 'San Judas Tadeo'),
(991, 295, 'Seboruco'),
(992, 296, 'Simón Rodríguez'),
(993, 297, 'Eleazar López Contreras'),
(994, 297, 'San Pablo'),
(995, 297, 'Sucre'),
(996, 298, 'San José Obrero'),
(997, 298, 'San Juan Eudes'),
(998, 299, 'Cárdenas'),
(999, 299, 'Juan Pablo Peñalosa'),
(1000, 299, 'Potosí'),
(1001, 299, 'Uribante'),
(1002, 300, 'Caraballeda'),
(1003, 300, 'Carayaca'),
(1004, 300, 'Carlos Soublette'),
(1005, 300, 'Caruao Chuspa'),
(1006, 300, 'Catia La Mar'),
(1007, 300, 'El Junko'),
(1008, 300, 'La Guaira'),
(1009, 300, 'Macuto'),
(1010, 300, 'Maiquetía'),
(1011, 300, 'Naiguatá'),
(1012, 300, 'Urimare'),
(1013, 301, 'Arístides Bastidas'),
(1014, 302, 'Bolívar'),
(1015, 303, 'Campo Elías'),
(1016, 303, 'Chivacoa'),
(1017, 304, 'Cocorote'),
(1018, 305, 'Independencia'),
(1019, 306, 'José Antonio Páez'),
(1020, 307, 'La Trinidad'),
(1021, 308, 'Manuel Monge'),
(1022, 309, 'Nirgua'),
(1023, 309, 'Salóm'),
(1024, 309, 'Temerla'),
(1025, 310, 'San Andrés'),
(1026, 310, 'Yaritagua'),
(1027, 311, 'Albarico'),
(1028, 311, 'San Felipe'),
(1029, 311, 'San Javier'),
(1030, 312, 'Sucre'),
(1031, 313, 'Urachiche'),
(1032, 314, 'El Guayabo'),
(1033, 314, 'Farriar'),
(1034, 315, 'Isla de Toas'),
(1035, 315, 'Monagas'),
(1036, 316, 'General Urdaneta'),
(1037, 316, 'Libertador'),
(1038, 316, 'Manuel Guanipa Matos'),
(1039, 316, 'Marcelino Briceño'),
(1040, 316, 'Pueblo Nuevo'),
(1041, 317, 'Ambrosio'),
(1042, 317, 'Arístides Calvani'),
(1043, 317, 'Carmen Herrera'),
(1044, 317, 'Germán Ríos Linares'),
(1045, 317, 'Jorge Hernández'),
(1046, 317, 'La Rosa'),
(1047, 317, 'Punta Gorda'),
(1048, 317, 'Rómulo Betancourt'),
(1049, 317, 'San Benito'),
(1050, 318, 'Encontrados'),
(1051, 318, 'Udón Pérez'),
(1052, 319, 'Moralito'),
(1053, 319, 'San Carlos del Zulia'),
(1054, 319, 'Santa Bárbara'),
(1055, 319, 'Santa Cruz del Zulia'),
(1056, 319, 'Urribarrí'),
(1057, 320, 'Carlos Quevedo'),
(1058, 320, 'Francisco Javier Pulgar'),
(1059, 320, 'Guamo-Gavilanes'),
(1060, 320, 'Simón Rodríguez'),
(1061, 321, 'José Ramón Yépez'),
(1062, 321, 'La Concepción'),
(1063, 321, 'Mariano Parra León'),
(1064, 321, 'San José'),
(1065, 322, 'Barí'),
(1066, 322, 'Jesús María Semprún'),
(1067, 323, 'Andrés Bello'),
(1068, 323, 'Chiquinquirá'),
(1069, 323, 'Concepción'),
(1070, 323, 'El Carmelo'),
(1071, 323, 'Potreritos'),
(1072, 324, 'Alonso de Ojeda'),
(1073, 324, 'Campo Lara'),
(1074, 324, 'Eleazar López Contreras'),
(1075, 324, 'Libertad'),
(1076, 324, 'Venezuela'),
(1077, 325, 'Bartolomé de las Casas'),
(1078, 325, 'Libertad'),
(1079, 325, 'Río Negro'),
(1080, 325, 'San José de Perijá'),
(1081, 326, 'La Sierrita'),
(1082, 326, 'Las Parcelas'),
(1083, 326, 'Luis de Vicente'),
(1084, 326, 'Monseñor Marcos Sergio Godoy'),
(1085, 326, 'Ricaurte'),
(1086, 326, 'San Rafael'),
(1087, 326, 'Tamare'),
(1088, 327, 'Antonio Borjas Romero'),
(1089, 327, 'Bolívar'),
(1090, 327, 'Cacique Mara'),
(1091, 327, 'Carracciolo Parra Pérez'),
(1092, 327, 'Cecilio Acosta'),
(1093, 327, 'Chiquinquirá'),
(1094, 327, 'Coquivacoa'),
(1095, 327, 'Cristo de Aranza'),
(1096, 327, 'Francisco Eugenio Bustamante'),
(1097, 327, 'Idelfonzo Vásquez'),
(1098, 327, 'Juana de Ávila'),
(1099, 327, 'Luis Hurtado Higuera'),
(1100, 327, 'Manuel Dagnino'),
(1101, 327, 'Olegario Villalobos.'),
(1102, 327, 'Raúl Leoni'),
(1103, 327, 'San Isidro'),
(1104, 327, 'Santa Lucía'),
(1105, 327, 'Venancio Pulgar'),
(1106, 328, 'Altagracia'),
(1107, 328, 'Ana María Campos'),
(1108, 328, 'Faría'),
(1109, 328, 'San Antonio'),
(1110, 328, 'San José'),
(1111, 329, 'Alta Guajira'),
(1112, 329, 'Elías Sánchez Rubio'),
(1113, 329, 'Guajira'),
(1114, 329, 'Sinamaica'),
(1115, 330, 'Donaldo García'),
(1116, 330, 'El Rosario'),
(1117, 330, 'Sixto Zambrano'),
(1118, 331, 'Domitila Flores'),
(1119, 331, 'El Bajo'),
(1120, 331, 'Francisco Ochoa'),
(1121, 331, 'Los Cortijos'),
(1122, 331, 'Marcial Hernández'),
(1123, 331, 'San Francisco'),
(1124, 332, 'El Mene'),
(1125, 332, 'José Cenobio Urribarrí'),
(1126, 332, 'Pedro Lucas Urribarrí'),
(1127, 332, 'Santa Rita'),
(1128, 333, 'Manuel Manrique'),
(1129, 333, 'Rafael Maria Baralt'),
(1130, 333, 'Rafael Urdaneta'),
(1131, 334, 'Bobures'),
(1132, 334, 'El Batey'),
(1133, 334, 'Gibraltar'),
(1134, 334, 'Heras'),
(1135, 334, 'Monseñor Arturo Álvarez'),
(1136, 334, 'Rómulo Gallegos'),
(1137, 335, 'La Victoria'),
(1138, 335, 'Rafael Urdaneta'),
(1139, 335, 'Raúl Cuenca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas_puntuacion`
--

CREATE TABLE `tarjetas_puntuacion` (
  `id_tarjeta` int NOT NULL,
  `id_combate` int NOT NULL,
  `id_juez` int NOT NULL,
  `numero_round` int NOT NULL,
  `puntos_local` tinyint NOT NULL,
  `puntos_visitante` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tarjetas_puntuacion`
--

INSERT INTO `tarjetas_puntuacion` (`id_tarjeta`, `id_combate`, `id_juez`, `numero_round`, `puntos_local`, `puntos_visitante`) VALUES
(12, 8, 1, 1, 10, 9),
(13, 8, 1, 2, 10, 8),
(14, 2, 1, 1, 10, 9),
(15, 2, 1, 2, 10, 9),
(16, 2, 1, 3, 10, 9),
(17, 2, 2, 1, 10, 9),
(18, 2, 2, 2, 9, 10),
(19, 2, 2, 3, 10, 9),
(20, 2, 3, 1, 10, 9),
(21, 2, 3, 2, 10, 9),
(22, 2, 3, 3, 10, 9),
(23, 2, 4, 1, 10, 9),
(24, 2, 4, 2, 10, 8),
(25, 2, 4, 3, 10, 9),
(26, 1, 4, 1, 6, 10),
(27, 13, 7, 1, 10, 9),
(29, 14, 7, 1, 10, 9),
(31, 11, 7, 1, 10, 9),
(32, 11, 7, 2, 10, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneos`
--

CREATE TABLE `torneos` (
  `id_torneo` int NOT NULL,
  `nombre_torneo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `lugar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneos`
--

INSERT INTO `torneos` (`id_torneo`, `nombre_torneo`, `fecha_inicio`, `fecha_fin`, `lugar`) VALUES
(1, 'Copa Nacional 2025', '2025-03-01', '2025-03-15', 'Caracas'),
(2, 'Torneo Regional', '2025-05-10', '2025-05-20', 'Valencia'),
(3, 'fdsdfsdfsdf', '2026-09-10', NULL, 'aqui alla cuya'),
(4, 'asdasd', '2026-11-16', '2026-11-17', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('Administrador','Entrenador','Consulta') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `rol`, `activo`, `created_at`) VALUES
(1, 'admin', '$2b$10$1qnb76B6dReyEqwTnaTWO.hz7QeU6.0AIVI.uAqXJAEb49WHUQCtG', 'Administrador', 1, '2026-06-13 03:05:35'),
(2, 'entrenador1', '$2y$12$8onMlf0yzgYpKFxzzCTE6OestfmAHEJwppB8AQwuAULTfVjbMSk62', 'Entrenador', 1, '2026-06-13 03:05:35'),
(3, 'consulta1', '$2y$12$vSHqrRGwitY10rgQk/D.8OcVnuJ/EGPR5pR2/RkzIq.QYm/HO96vy', 'Consulta', 1, '2026-06-13 03:05:35'),
(4, 'check', '$2y$12$3kUGxKrFR4w.Mg6USpjMjuYgfB1LiKkC2IroSG54jfosyV/4TYk22', 'Consulta', 0, '2026-11-11 22:17:39');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_atletas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_atletas` (
`activo` tinyint(1)
,`cedula` varchar(20)
,`derrotas` decimal(23,0)
,`empates` decimal(23,0)
,`fecha_nacimiento` date
,`foto` varchar(255)
,`id_atleta` int
,`id_categoria` int
,`id_club` int
,`papellido` varchar(50)
,`pnombre` varchar(50)
,`sapellido` varchar(50)
,`snombre` varchar(50)
,`total_combates` decimal(23,0)
,`victorias` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_atletas`
--
DROP TABLE IF EXISTS `vista_atletas`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vista_atletas`  AS SELECT `a`.`id_atleta` AS `id_atleta`, `a`.`pnombre` AS `pnombre`, `a`.`snombre` AS `snombre`, `a`.`papellido` AS `papellido`, `a`.`sapellido` AS `sapellido`, `a`.`cedula` AS `cedula`, `a`.`fecha_nacimiento` AS `fecha_nacimiento`, `a`.`id_club` AS `id_club`, `a`.`id_categoria` AS `id_categoria`, `a`.`foto` AS `foto`, `a`.`activo` AS `activo`, coalesce(sum((case when ((`c`.`resultado` <> 'Pendiente') and (`c`.`resultado` is not null)) then 1 else 0 end)),0) AS `total_combates`, coalesce(sum((case when (((`c`.`id_atleta_local` = `a`.`id_atleta`) and (`c`.`resultado` = 'Local')) or ((`c`.`id_atleta_visitante` = `a`.`id_atleta`) and (`c`.`resultado` = 'Visitante'))) then 1 else 0 end)),0) AS `victorias`, coalesce(sum((case when (((`c`.`id_atleta_local` = `a`.`id_atleta`) and (`c`.`resultado` = 'Visitante')) or ((`c`.`id_atleta_visitante` = `a`.`id_atleta`) and (`c`.`resultado` = 'Local'))) then 1 else 0 end)),0) AS `derrotas`, coalesce(sum((case when (`c`.`resultado` = 'Empate') then 1 else 0 end)),0) AS `empates` FROM (`atletas` `a` left join `combates` `c` on(((`a`.`id_atleta` = `c`.`id_atleta_local`) or (`a`.`id_atleta` = `c`.`id_atleta_visitante`)))) GROUP BY `a`.`id_atleta` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD PRIMARY KEY (`id_atleta`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `idx_atletas_categoria` (`id_categoria`),
  ADD KEY `idx_atletas_club` (`id_club`);

--
-- Indices de la tabla `atleta_documentos`
--
ALTER TABLE `atleta_documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_atleta` (`id_atleta`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clubes`
--
ALTER TABLE `clubes`
  ADD PRIMARY KEY (`id_club`),
  ADD KEY `fk_clubes_parroquia` (`id_parroquia`);

--
-- Indices de la tabla `combates`
--
ALTER TABLE `combates`
  ADD PRIMARY KEY (`id_combate`),
  ADD KEY `idx_combates_fecha` (`fecha`),
  ADD KEY `idx_combates_torneo` (`id_torneo`),
  ADD KEY `idx_combates_local` (`id_atleta_local`),
  ADD KEY `idx_combates_visitante` (`id_atleta_visitante`);

--
-- Indices de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD PRIMARY KEY (`id_entrenador`),
  ADD UNIQUE KEY `cedula_entrenador` (`cedula_entrenador`);

--
-- Indices de la tabla `entrenador_club`
--
ALTER TABLE `entrenador_club`
  ADD PRIMARY KEY (`id_entrenador`,`id_club`),
  ADD KEY `id_club` (`id_club`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre_estado` (`nombre_estado`);

--
-- Indices de la tabla `jueces`
--
ALTER TABLE `jueces`
  ADD PRIMARY KEY (`id_juez`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`),
  ADD KEY `id_municipio` (`id_municipio`);

--
-- Indices de la tabla `tarjetas_puntuacion`
--
ALTER TABLE `tarjetas_puntuacion`
  ADD PRIMARY KEY (`id_tarjeta`),
  ADD UNIQUE KEY `uq_combate_juez_round` (`id_combate`,`id_juez`,`numero_round`),
  ADD KEY `fk_tarjetas_juez` (`id_juez`);

--
-- Indices de la tabla `torneos`
--
ALTER TABLE `torneos`
  ADD PRIMARY KEY (`id_torneo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atletas`
--
ALTER TABLE `atletas`
  MODIFY `id_atleta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `atleta_documentos`
--
ALTER TABLE `atleta_documentos`
  MODIFY `id_documento` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `clubes`
--
ALTER TABLE `clubes`
  MODIFY `id_club` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `combates`
--
ALTER TABLE `combates`
  MODIFY `id_combate` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id_entrenador` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `jueces`
--
ALTER TABLE `jueces`
  MODIFY `id_juez` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1140;

--
-- AUTO_INCREMENT de la tabla `tarjetas_puntuacion`
--
ALTER TABLE `tarjetas_puntuacion`
  MODIFY `id_tarjeta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `torneos`
--
ALTER TABLE `torneos`
  MODIFY `id_torneo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD CONSTRAINT `atletas_ibfk_1` FOREIGN KEY (`id_club`) REFERENCES `clubes` (`id_club`) ON DELETE SET NULL,
  ADD CONSTRAINT `atletas_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL;

--
-- Filtros para la tabla `atleta_documentos`
--
ALTER TABLE `atleta_documentos`
  ADD CONSTRAINT `atleta_documentos_ibfk_1` FOREIGN KEY (`id_atleta`) REFERENCES `atletas` (`id_atleta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clubes`
--
ALTER TABLE `clubes`
  ADD CONSTRAINT `fk_clubes_parroquia` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL;

--
-- Filtros para la tabla `combates`
--
ALTER TABLE `combates`
  ADD CONSTRAINT `combates_ibfk_1` FOREIGN KEY (`id_torneo`) REFERENCES `torneos` (`id_torneo`) ON DELETE SET NULL,
  ADD CONSTRAINT `combates_ibfk_2` FOREIGN KEY (`id_atleta_local`) REFERENCES `atletas` (`id_atleta`) ON DELETE CASCADE,
  ADD CONSTRAINT `combates_ibfk_3` FOREIGN KEY (`id_atleta_visitante`) REFERENCES `atletas` (`id_atleta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `entrenador_club`
--
ALTER TABLE `entrenador_club`
  ADD CONSTRAINT `entrenador_club_ibfk_1` FOREIGN KEY (`id_entrenador`) REFERENCES `entrenadores` (`id_entrenador`) ON DELETE CASCADE,
  ADD CONSTRAINT `entrenador_club_ibfk_2` FOREIGN KEY (`id_club`) REFERENCES `clubes` (`id_club`) ON DELETE CASCADE;

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE CASCADE;

--
-- Filtros para la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD CONSTRAINT `parroquias_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tarjetas_puntuacion`
--
ALTER TABLE `tarjetas_puntuacion`
  ADD CONSTRAINT `fk_tarjetas_combate` FOREIGN KEY (`id_combate`) REFERENCES `combates` (`id_combate`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tarjetas_juez` FOREIGN KEY (`id_juez`) REFERENCES `jueces` (`id_juez`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
