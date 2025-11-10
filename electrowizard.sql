-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2025 a las 05:39:47
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
-- Base de datos: `electrowizard`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` varchar(10) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
('Cat1', 'Televisores'),
('Cat2', 'Refrigeradores'),
('Cat3', 'Lavadoras'),
('Cat4', 'Pequeños Electrodomésticos'),
('Cat5', 'Gamer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_categoria` varchar(50) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `marca`, `nombre_producto`, `precio`, `stock`, `id_categoria`, `imagen`, `descripcion`, `id_usuario`) VALUES
(1, 'Samsung', 'Smart TV Samsung 55\" 4K UHD', 3599000.00, 15, 'Cat1', 'imagen1.jpg', 'Televisor 4K con HDR y control por voz.', 7),
(2, 'LG', 'LG OLED 65\" Ultra HD', 5199000.00, 10, 'Cat1', 'imagen2.jpg', 'OLED con inteligencia artificial y Dolby Vision.', 7),
(3, 'Sony', 'Sony Bravia 50\" 4K HDR', 3899000.00, 12, 'Cat1', 'imagen3.jpg', 'Smart TV con Android TV y gran contraste.', 7),
(4, 'TCL', 'TCL 43\" Smart TV Roku', 1999000.00, 20, 'Cat1', 'imagen4.jpg', 'TV económica con sistema Roku integrado.', 7),
(5, 'Hisense', 'Hisense 65\" QLED 4K', 3299000.00, 8, 'Cat1', 'imagen5.jpg', 'Pantalla QLED con colores vibrantes.', 7),
(6, 'Panasonic', 'Panasonic 40\" Full HD', 1699000.00, 18, 'Cat1', 'imagen6.jpg', 'Televisor con sonido envolvente.', 7),
(7, 'Philips', 'Philips Ambilight 55\"', 3099000.00, 10, 'Cat1', 'imagen7.jpg', 'Luces LED dinámicas en la parte trasera.', 7),
(8, 'JVC', 'JVC 32\" Smart TV', 1299000.00, 25, 'Cat1', 'imagen8.jpg', 'Ideal para habitaciones pequeñas.', 7),
(9, 'Sharp', 'Sharp 50\" Aquos 4K', 2499000.00, 9, 'Cat1', 'imagen9.jpg', 'Excelente calidad de imagen y conectividad.', 7),
(10, 'Vizio', 'Vizio 70\" QLED Smart TV', 4199000.00, 7, 'Cat1', 'imagen10.jpg', 'Pantalla grande con Dolby Atmos.', 7),
(11, 'Samsung', 'Refrigerador Samsung No Frost 400L', 2999000.00, 10, 'Cat2', 'imagen11.jpg', 'Ahorro energético clase A++.', 7),
(12, 'LG', 'Refrigerador LG Door-in-Door 500L', 3599000.00, 8, 'Cat2', 'imagen12.jpg', 'Diseño elegante y eficiente.', 7),
(13, 'Whirlpool', 'Refrigerador Whirlpool Doble Puerta 420L', 2599000.00, 12, 'Cat2', 'imagen13.jpg', 'Gran capacidad con bajo consumo.', 7),
(14, 'Mabe', 'Refrigerador Mabe Top Mount 300L', 1799000.00, 20, 'Cat2', 'imagen14.jpg', 'Compacto y funcional.', 7),
(15, 'Haier', 'Refrigerador Haier Inverter 380L', 2199000.00, 15, 'Cat2', 'imagen15.jpg', 'Tecnología inverter silenciosa.', 7),
(16, 'Bosch', 'Refrigerador Bosch Serie 6 450L', 3799000.00, 5, 'Cat2', 'imagen16.jpg', 'Control de temperatura digital.', 7),
(17, 'Electrolux', 'Refrigerador Electrolux No Frost 360L', 2399000.00, 14, 'Cat2', 'imagen17.jpg', 'Puerta reversible y eficiente.', 7),
(18, 'GE', 'Refrigerador GE French Door 600L', 4499000.00, 6, 'Cat2', 'imagen18.jpg', 'Estilo premium con dispensador de agua.', 7),
(19, 'Frigidaire', 'Refrigerador Frigidaire 280L Compacto', 1699000.00, 18, 'Cat2', 'imagen19.jpg', 'Ideal para departamentos pequeños.', 7),
(20, 'Daewoo', 'Refrigerador Daewoo Silver Line 350L', 1999000.00, 16, 'Cat2', 'imagen20.jpg', 'Buen balance entre capacidad y precio.', 7),
(21, 'LG', 'Lavadora LG Inverter 15kg', 2399000.00, 10, 'Cat3', 'imagen21.jpg', 'Ahorro de energía con tecnología inverter.', 7),
(22, 'Samsung', 'Lavadora Samsung EcoBubble 12kg', 2599000.00, 9, 'Cat3', 'imagen22.jpg', 'Lavado potente con burbujas.', 7),
(23, 'Whirlpool', 'Lavadora Whirlpool Carga Superior 14kg', 1899000.00, 11, 'Cat3', 'imagen23.jpg', 'Fácil de usar y duradera.', 7),
(24, 'Mabe', 'Lavadora Mabe Automática 10kg', 1499000.00, 14, 'Cat3', 'imagen24.jpg', 'Ideal para familias pequeñas.', 7),
(25, 'Electrolux', 'Lavadora Electrolux Turbo 13kg', 1799000.00, 10, 'Cat3', 'imagen25.jpg', 'Lavado rápido y silencioso.', 7),
(26, 'Bosch', 'Lavadora Bosch Serie 4 9kg', 1699000.00, 8, 'Cat3', 'imagen26.jpg', 'Eficiencia energética A+++.', 7),
(27, 'Daewoo', 'Lavadora Daewoo Digital 11kg', 1599000.00, 12, 'Cat3', 'imagen27.jpg', 'Pantalla digital y múltiples programas.', 7),
(28, 'Panasonic', 'Lavadora Panasonic Carga Frontal 8kg', 1799000.00, 7, 'Cat3', 'imagen28.jpg', 'Compacta y moderna.', 7),
(29, 'GE', 'Lavadora GE Automática 13kg', 1699000.00, 13, 'Cat3', 'imagen29.jpg', 'Eficiente y confiable.', 7),
(30, 'Haier', 'Lavadora Haier Smart 9kg', 1599000.00, 15, 'Cat3', 'imagen30.jpg', 'Control WiFi y bajo ruido.', 7),
(31, 'Oster', 'Licuadora Oster Reversible 600W', 299000.00, 30, 'Cat4', 'imagen31.jpg', 'Cuchillas de acero inoxidable.', 7),
(32, 'Black+Decker', 'Tostadora 4 rebanadas', 159000.00, 25, 'Cat4', 'imagen32.jpg', 'Diseño moderno y fácil limpieza.', 7),
(33, 'Philips', 'Freidora de aire 4L', 389000.00, 20, 'Cat4', 'imagen33.jpg', 'Cocina saludable sin aceite.', 7),
(34, 'Hamilton Beach', 'Cafetera 12 tazas', 259000.00, 18, 'Cat4', 'imagen34.jpg', 'Programable con temporizador.', 7),
(35, 'Imusa', 'Sanduchera Imusa 700W', 139000.00, 22, 'Cat4', 'imagen35.jpg', 'Compacta y práctica.', 7),
(36, 'KitchenAid', 'Batidora de mano 9 velocidades', 449000.00, 15, 'Cat4', 'imagen36.jpg', 'Ideal para repostería.', 7),
(37, 'Oster', 'Plancha a vapor 1200W', 179000.00, 25, 'Cat4', 'imagen37.jpg', 'Suela antiadherente.', 7),
(38, 'Revlon', 'Secador de pelo 1800W', 229000.00, 20, 'Cat4', 'imagen38.jpg', 'Secado rápido y sin frizz.', 7),
(39, 'Black+Decker', 'Aspiradora portátil 800W', 299000.00, 12, 'Cat4', 'imagen39.jpg', 'Compacta y potente.', 7),
(40, 'Philco', 'Hervidor eléctrico 1.7L', 169000.00, 30, 'Cat4', 'imagen40.jpg', 'Apagado automático.', 7),
(41, 'Logitech', 'Teclado mecánico G Pro', 289000.00, 25, 'Cat5', 'imagen41.jpg', 'Switches GX Blue para precisión.', 7),
(42, 'Razer', 'Mouse Razer Viper Ultimate', 269000.00, 30, 'Cat5', 'imagen42.jpg', 'Inalámbrico con sensor óptico avanzado.', 7),
(43, 'HyperX', 'Audífonos HyperX Cloud II', 239000.00, 20, 'Cat5', 'imagen43.jpg', 'Sonido envolvente 7.1.', 7),
(44, 'ASUS', 'Monitor ASUS TUF 27\" 165Hz', 1299000.00, 10, 'Cat5', 'imagen44.jpg', 'Pantalla rápida para gaming competitivo.', 7),
(45, 'MSI', 'Laptop Gaming MSI GF63', 4999000.00, 8, 'Cat5', 'imagen45.jpg', 'Intel i7 + RTX 4060.', 7),
(46, 'Corsair', 'Silla gamer Corsair T3', 1199000.00, 15, 'Cat5', 'imagen46.jpg', 'Ergonómica con ajuste total.', 7),
(47, 'Acer', 'Monitor Acer Predator 32\"', 1799000.00, 9, 'Cat5', 'imagen47.jpg', 'QHD con 165Hz.', 7),
(48, 'SteelSeries', 'Mousepad XXL', 89900.00, 50, 'Cat5', 'imagen48.jpg', 'Antideslizante y duradero.', 7),
(49, 'Redragon', 'Micrófono Redragon GM300', 199000.00, 18, 'Cat5', 'imagen49.jpg', 'Micrófono cardioide profesional.', 7),
(50, 'Cooler Master', 'Gabinete Gaming RGB', 299000.00, 14, 'Cat5', 'imagen50.jpg', 'Diseño con ventiladores RGB.', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `imagen_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `rol`, `fecha_registro`, `direccion`, `telefono`, `imagen_perfil`) VALUES
(7, 'Administrador', 'admin@electrowizard.com', '12345', 'admin', '2025-11-09 05:55:04', NULL, NULL, NULL),
(8, 'Brayan', 'cliente@electrowizard.com', '12321', 'cliente', '2025-11-09 05:55:04', 'calle 95 a #21 321', '3238249827', 'perfil_69104a5a60ac7.jpg'),
(9, 'juan', 'juan@prueba.com', '12345', 'cliente', '2025-11-09 06:03:53', NULL, NULL, NULL),
(10, 'angel', 'angel007dav@gmail.com', '1032432108', 'admin', '2025-11-09 07:25:33', NULL, NULL, NULL),
(11, 'pepe', 'pepe@gmail.com', '12345', 'cliente', '2025-11-09 22:15:24', '', '', 'perfil_691112c3666b5.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_productos_usuario` (`id_usuario`),
  ADD KEY `fk_productos_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `fk_productos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
