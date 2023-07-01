-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2023 a las 04:13:34
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `balance`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tlb_categoria`
--

CREATE TABLE `tlb_categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tlb_categoria`
--

INSERT INTO `tlb_categoria` (`id_categoria`, `nombre_categoria`, `estado`) VALUES
(11, 'Cine', 'ACTIVO'),
(12, 'Restaurante', 'ACTIVO'),
(13, 'compraventa', 'ACTIVO'),
(14, 'Super mercado', 'ACTIVO'),
(15, 'Tienda de Ropa', 'ACTIVO'),
(16, 'Banco', 'ACTIVO'),
(17, 'Panaderia', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tlb_gasto`
--

CREATE TABLE `tlb_gasto` (
  `id_gasto` int(11) NOT NULL,
  `nombre_gasto` varchar(100) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tlb_gasto`
--

INSERT INTO `tlb_gasto` (`id_gasto`, `nombre_gasto`, `estado`) VALUES
(1, 'Gastos', 'ACTIVO'),
(2, 'Ganancias', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tlb_operacion`
--

CREATE TABLE `tlb_operacion` (
  `id_operacion` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `monto` float NOT NULL,
  `id_gasto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tlb_operacion`
--

INSERT INTO `tlb_operacion` (`id_operacion`, `descripcion`, `monto`, `id_gasto`, `id_categoria`, `fecha`) VALUES
(11, 'Pago de credito', 100000, 2, 16, '2023-06-30'),
(12, 'pago de impuestos', 800000, 1, 13, '2023-07-04'),
(13, 'pago del mercado', 100000, 1, 14, '2023-06-29');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tlb_categoria`
--
ALTER TABLE `tlb_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `tlb_gasto`
--
ALTER TABLE `tlb_gasto`
  ADD PRIMARY KEY (`id_gasto`);

--
-- Indices de la tabla `tlb_operacion`
--
ALTER TABLE `tlb_operacion`
  ADD PRIMARY KEY (`id_operacion`),
  ADD KEY `fk_1` (`id_gasto`),
  ADD KEY `fk_2` (`id_categoria`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tlb_categoria`
--
ALTER TABLE `tlb_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tlb_gasto`
--
ALTER TABLE `tlb_gasto`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tlb_operacion`
--
ALTER TABLE `tlb_operacion`
  MODIFY `id_operacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tlb_operacion`
--
ALTER TABLE `tlb_operacion`
  ADD CONSTRAINT `fk_1` FOREIGN KEY (`id_gasto`) REFERENCES `tlb_gasto` (`id_gasto`),
  ADD CONSTRAINT `fk_2` FOREIGN KEY (`id_categoria`) REFERENCES `tlb_categoria` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
