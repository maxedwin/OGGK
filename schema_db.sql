-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-01-2021 a las 15:19:37
-- Versión del servidor: 5.6.41-84.1
-- Versión de PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `solucjc0_solucionesoggk_db_erp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `idalmacen` int(11) NOT NULL,
  `idempresa` int(11) DEFAULT '1',
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `state` int(11) DEFAULT '1',
  `direccion` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `distrito` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `provincia` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `departamento` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenlote`
--

CREATE TABLE `almacenlote` (
  `id` int(11) NOT NULL,
  `idlote` int(11) DEFAULT NULL,
  `idalmacen` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancos`
--

CREATE TABLE `bancos` (
  `idbanco` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajad`
--

CREATE TABLE `cajad` (
  `idcajad` int(11) NOT NULL,
  `idcajah` int(11) NOT NULL,
  `idlote` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unit` float DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajaguiaventa`
--

CREATE TABLE `cajaguiaventa` (
  `idcajaguiaventa` int(11) NOT NULL,
  `idcaja` int(11) DEFAULT NULL,
  `idguia` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajah`
--

CREATE TABLE `cajah` (
  `idcajah` int(11) NOT NULL,
  `id_orden_ventah` int(11) DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idvendedor` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idalmacen` int(11) DEFAULT NULL,
  `codigoNB` varchar(50) CHARACTER SET latin1 NOT NULL,
  `fechaNB` date NOT NULL,
  `moneda` int(11) NOT NULL,
  `tipo_cambio` float NOT NULL,
  `numeracion` int(11) NOT NULL,
  `paga` float NOT NULL DEFAULT '0',
  `vuelto` float NOT NULL DEFAULT '0',
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `total_nc` float DEFAULT NULL,
  `descuento` float NOT NULL DEFAULT '0',
  `tipo` int(11) NOT NULL,
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `f_entrega` date DEFAULT NULL,
  `f_cobro` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `nulled_at` datetime DEFAULT NULL,
  `nulled_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `products_id` int(11) NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uom` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double(8,2) NOT NULL,
  `quantity` double(8,2) DEFAULT NULL,
  `user_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `idcategoria` int(255) NOT NULL,
  `idpadre` int(255) DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET latin1 NOT NULL,
  `idempresa` int(11) DEFAULT '1',
  `state` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `state_tienda` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `charges`
--

CREATE TABLE `charges` (
  `id` int(10) UNSIGNED NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_id` int(11) NOT NULL,
  `source_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idempresa` int(11) NOT NULL DEFAULT '1',
  `idvendedor` int(11) DEFAULT NULL,
  `tipo_documento` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `ruc_dni` bigint(20) DEFAULT NULL,
  `razon_social` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `nombre_comercial` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contacto_nombre` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `contacto_telefono` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `contacto_email` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `estado_entidad` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `tipo_cliente` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_pago` int(11) DEFAULT '1',
  `dias_credito` int(11) DEFAULT NULL,
  `tipo_emp` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `moneda` int(11) DEFAULT '1',
  `referencia` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `distrito` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `provincia` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `departamento` varchar(100) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_no_ruc`
--

CREATE TABLE `clientes_no_ruc` (
  `id_cliente_no_ruc` bigint(20) UNSIGNED NOT NULL,
  `razon_social` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_emp` int(11) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(10,8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clienteubicacion`
--

CREATE TABLE `clienteubicacion` (
  `idcliubic` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `ruc_dni` bigint(20) NOT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `location_type` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `direccion` varchar(200) CHARACTER SET latin1 NOT NULL,
  `distrito` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clienteubicacion_prueba`
--

CREATE TABLE `clienteubicacion_prueba` (
  `idcliubic` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `ruc_dni` bigint(20) NOT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `location_type` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `direccion` varchar(200) CHARACTER SET latin1 NOT NULL,
  `distrito` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_sunat_prods`
--

CREATE TABLE `codigos_sunat_prods` (
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id_color` int(11) NOT NULL,
  `color_nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciond`
--

CREATE TABLE `cotizaciond` (
  `idcotizaciond` int(11) NOT NULL,
  `idcotizacionh` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unit` float DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacionh`
--

CREATE TABLE `cotizacionh` (
  `idcotizacionh` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idvendedor` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `margen` float NOT NULL,
  `moneda` int(11) NOT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `f_entrega` date DEFAULT NULL,
  `f_cobro` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `paga` float DEFAULT NULL,
  `vuelto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` char(6) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `departamento_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distritos`
--

CREATE TABLE `distritos` (
  `id` char(6) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `distrito_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `id_provi` char(6) CHARACTER SET utf8 DEFAULT NULL,
  `id_depa` char(6) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `idempresa` int(11) NOT NULL,
  `empresa` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `ruc` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `razonsocial` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `titular` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `titulardni` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresassucursales`
--

CREATE TABLE `empresassucursales` (
  `id` int(11) NOT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `idsucursal` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `idevaluacion` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idevaluado` int(11) NOT NULL,
  `tipo_evaluacion` int(11) NOT NULL,
  `puntaje` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factguiacompra`
--

CREATE TABLE `factguiacompra` (
  `idfactguiacompra` int(11) NOT NULL,
  `idfact` int(11) DEFAULT NULL,
  `idguia` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_comprad`
--

CREATE TABLE `factura_comprad` (
  `id_factura_comprad` int(11) NOT NULL,
  `id_factura_comprah` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo_unit` float DEFAULT NULL,
  `costo_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_comprah`
--

CREATE TABLE `factura_comprah` (
  `id_factura_comprah` int(11) NOT NULL,
  `id_guia_comprah` int(11) DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idproveedor` int(11) DEFAULT NULL,
  `flete_trans` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `flete_costo` float NOT NULL,
  `serie` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `moneda` int(11) NOT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_emision` date DEFAULT NULL,
  `f_vencimiento` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `paga` float DEFAULT NULL,
  `vuelto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha_recepciond`
--

CREATE TABLE `ficha_recepciond` (
  `id_ficha_recepciond` int(11) NOT NULL,
  `id_ficha_recepcionh` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `idlote` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha_recepcionh`
--

CREATE TABLE `ficha_recepcionh` (
  `id_ficha_recepcionh` int(11) NOT NULL,
  `id_factura_comprah` int(11) DEFAULT NULL,
  `id_orden_comprah` int(11) NOT NULL,
  `flete_trans` int(11) DEFAULT NULL,
  `flete_costo` float DEFAULT NULL,
  `numeracion_guia` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idproveedor` int(11) DEFAULT NULL,
  `idalmacen` int(11) DEFAULT NULL,
  `serie` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_emision` date DEFAULT NULL,
  `f_recepcion` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia_comprad`
--

CREATE TABLE `guia_comprad` (
  `id_guia_comprad` int(11) NOT NULL,
  `id_guia_comprah` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo_unit` float DEFAULT NULL,
  `costo_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia_comprah`
--

CREATE TABLE `guia_comprah` (
  `id_guia_comprah` int(11) NOT NULL,
  `id_orden_comprah` int(11) DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idproveedor` int(11) DEFAULT NULL,
  `flete_trans` int(11) DEFAULT NULL,
  `flete_costo` float NOT NULL,
  `serie` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `moneda` int(11) NOT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_emision` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `paga` float DEFAULT NULL,
  `vuelto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia_remisiond`
--

CREATE TABLE `guia_remisiond` (
  `id_guia_remisiond` int(11) NOT NULL,
  `id_guia_remisionh` int(11) NOT NULL,
  `idlote` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `peso_unit` float DEFAULT NULL,
  `peso_total` float DEFAULT NULL,
  `peso_und` varchar(10) CHARACTER SET latin1 NOT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia_remisionh`
--

CREATE TABLE `guia_remisionh` (
  `id_guia_remisionh` int(11) NOT NULL,
  `id_orden_ventah` int(11) DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idvendedor` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idtransporte` int(11) DEFAULT NULL,
  `iddespachador` int(11) DEFAULT NULL,
  `idalmacen` int(11) NOT NULL,
  `motivo_traslado` int(11) DEFAULT NULL,
  `ubigeo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `numeracion` int(11) NOT NULL,
  `codigoNB` varchar(50) CHARACTER SET latin1 NOT NULL,
  `fechaNB` date NOT NULL,
  `peso_total` varchar(50) CHARACTER SET latin1 NOT NULL,
  `numero_de_bultos` int(11) NOT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `f_entrega` date DEFAULT NULL,
  `f_cobro` date DEFAULT NULL,
  `f_entregado` date NOT NULL,
  `f_reprogramar` date DEFAULT NULL,
  `id_usuario_despachador` int(11) NOT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `paga` float DEFAULT NULL,
  `vuelto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `idlote` int(11) NOT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `codigo` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  `stock_lote` bigint(20) DEFAULT NULL,
  `f_venc` date DEFAULT NULL,
  `costo` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `texto` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_creditod`
--

CREATE TABLE `nota_creditod` (
  `id_nota_creditod` int(11) NOT NULL,
  `id_nota_creditoh` int(11) NOT NULL,
  `idlote` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unit` float DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_creditoh`
--

CREATE TABLE `nota_creditoh` (
  `id_nota_creditoh` int(11) NOT NULL,
  `idcajah` int(11) DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idvendedor` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idalmacen` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  `tipo_descuento` int(11) DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `codigoNB` varchar(15) CHARACTER SET latin1 NOT NULL,
  `fechaNB` date DEFAULT NULL,
  `moneda` int(11) NOT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `razon` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_devolucion` date DEFAULT NULL,
  `f_cobro` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `paga` float DEFAULT NULL,
  `vuelto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_comprad`
--

CREATE TABLE `orden_comprad` (
  `id_orden_comprad` int(11) NOT NULL,
  `id_orden_comprah` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo_unit` float DEFAULT NULL,
  `costo_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_comprah`
--

CREATE TABLE `orden_comprah` (
  `id_orden_comprah` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idproveedor` int(11) DEFAULT NULL,
  `serie` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `moneda` int(11) NOT NULL,
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_emision` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `paga` float DEFAULT NULL,
  `vuelto` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_ventad`
--

CREATE TABLE `orden_ventad` (
  `id_orden_ventad` int(11) NOT NULL,
  `id_orden_ventah` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unit` float DEFAULT NULL,
  `precio_total` float DEFAULT NULL,
  `idempresa` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_ventah`
--

CREATE TABLE `orden_ventah` (
  `id_orden_ventah` int(11) NOT NULL,
  `idcotizacionh` int(11) DEFAULT '0',
  `idempresa` int(11) NOT NULL DEFAULT '1',
  `idsucursal` int(11) NOT NULL DEFAULT '1',
  `idusuario` int(11) NOT NULL DEFAULT '0',
  `idvendedor` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `numeracion` int(11) NOT NULL,
  `codigoNB` varchar(10) CHARACTER SET latin1 NOT NULL,
  `moneda` int(11) NOT NULL DEFAULT '1',
  `subtotal` float DEFAULT NULL,
  `total` float NOT NULL DEFAULT '0',
  `igv` float NOT NULL DEFAULT '0',
  `descuento` float NOT NULL DEFAULT '0',
  `comentarios` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `f_entrega` date DEFAULT NULL,
  `f_cobro` date DEFAULT NULL,
  `estado_doc` int(11) DEFAULT '0',
  `vuelto` float DEFAULT NULL,
  `paga` float DEFAULT NULL,
  `by_client` int(1) DEFAULT NULL,
  `order_status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coupon_amount` float DEFAULT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `ruc_dni` bigint(20) DEFAULT NULL,
  `document_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_digital` int(11) NOT NULL DEFAULT '0',
  `nulled_at` datetime DEFAULT NULL,
  `nulled_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_efectuados`
--

CREATE TABLE `pagos_efectuados` (
  `id_pago_efectuado` int(11) NOT NULL,
  `idfactcompra` int(11) NOT NULL,
  `total` float NOT NULL,
  `pagado` float NOT NULL,
  `por_pagar` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_doc` int(11) NOT NULL,
  `tipo_pago` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `nro_operacion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idbanco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_recibidos`
--

CREATE TABLE `pagos_recibidos` (
  `id_pago_recibido` int(11) NOT NULL,
  `idcajah` int(11) NOT NULL,
  `total` float NOT NULL,
  `pagado` float NOT NULL,
  `por_pagar` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_doc` int(11) NOT NULL,
  `tipo_pago` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `nro_operacion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idbanco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` int(11) NOT NULL,
  `idempresa` int(11) DEFAULT '1',
  `idsucursal` int(11) DEFAULT '1',
  `idcategoria` int(11) NOT NULL,
  `idproveedor` int(11) DEFAULT NULL,
  `barcode` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `nombre` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `detalle` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `codigo_sunat` int(15) DEFAULT NULL,
  `flete` float DEFAULT NULL,
  `costo` float DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `stock_total` bigint(20) DEFAULT NULL,
  `stock_imaginario` bigint(20) NOT NULL,
  `tipo` int(11) DEFAULT '1',
  `state` int(11) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `volumen` float DEFAULT NULL,
  `volumen_und` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `cantidad_caja` int(11) DEFAULT NULL,
  `medida_venta` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `tipo_alm` int(11) DEFAULT '1',
  `peso_unidad` float NOT NULL,
  `peso_unidad_und` varchar(5) CHARACTER SET latin1 NOT NULL,
  `peso_caja` float DEFAULT NULL,
  `peso_caja_und` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `precio_und` float DEFAULT NULL,
  `precio_1a9` float DEFAULT NULL,
  `precio_10a19` float DEFAULT NULL,
  `precio_20a24` float DEFAULT NULL,
  `precio_25a29` float DEFAULT NULL,
  `precio_30` float DEFAULT NULL,
  `image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state_tienda` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idproveedor` int(11) NOT NULL,
  `idempresa` int(11) DEFAULT '1',
  `ruc_dni` bigint(20) DEFAULT NULL,
  `razon_social` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `distrito` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `provincia` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `departamento` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `contacto_nombre` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `contacto_telefono` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `contacto_email` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `est_ent` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `tipo_pago` int(11) DEFAULT NULL,
  `dias_credito` int(11) DEFAULT NULL,
  `tipo_emp` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `moneda` varchar(100) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `id` char(6) NOT NULL DEFAULT '',
  `provincia_name` varchar(100) DEFAULT NULL,
  `id_depa` char(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `idpuesto` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

CREATE TABLE `reclamos` (
  `idreclamo` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `reclamo` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `idservicio` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `descripcion` varchar(300) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `costo` float NOT NULL DEFAULT '0',
  `precio` float NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `idsucursal` int(11) NOT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `sucursal` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `serie` int(250) DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empresa`
--

CREATE TABLE `tipo_empresa` (
  `id_tipoemp` int(255) NOT NULL,
  `tipoemp_nombre` varchar(100) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `idtransaccion` int(255) NOT NULL,
  `idempresa` int(255) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idsucursal` int(255) NOT NULL,
  `idproducto` int(255) NOT NULL,
  `tipo` int(11) NOT NULL COMMENT '(Salida, entrada)',
  `cantidad` int(11) NOT NULL,
  `stockT` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `idalmacen` int(11) NOT NULL,
  `idlote` int(11) NOT NULL,
  `f_emision` date NOT NULL,
  `tipo_documento` int(11) NOT NULL,
  `iddocumento` int(11) NOT NULL,
  `tipo_movimiento` int(11) DEFAULT NULL,
  `quien_uso` int(11) NOT NULL,
  `razon` varchar(200) CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transporte`
--

CREATE TABLE `transporte` (
  `idtransporte` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `nombre_trans` varchar(100) CHARACTER SET latin1 NOT NULL,
  `marca` varchar(100) CHARACTER SET latin1 NOT NULL,
  `tipo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `placa` varchar(100) CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubigeo`
--

CREATE TABLE `ubigeo` (
  `codigo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `idmedida` int(255) NOT NULL,
  `idpadre` int(255) DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `state` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `idempresa` int(11) NOT NULL DEFAULT '1',
  `idsucursal` int(11) NOT NULL DEFAULT '1',
  `idrol` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dni` bigint(20) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `activated` tinyint(1) DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `telefono` int(20) DEFAULT NULL,
  `direccion` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `f_nac` date DEFAULT NULL,
  `sexo` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `f_entrada` date DEFAULT NULL,
  `f_salida` date DEFAULT NULL,
  `est_ent` int(11) DEFAULT '1',
  `puesto` int(11) DEFAULT NULL,
  `distrito` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `provincia` varchar(100) COLLATE utf8_unicode_ci DEFAULT 'AQP',
  `departamento` varchar(100) COLLATE utf8_unicode_ci DEFAULT 'AQP',
  `pin_color` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tienda_user` int(11) NOT NULL,
  `culqi_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_address`
--

CREATE TABLE `user_address` (
  `id` bigint(20) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `iddepartamento` int(11) NOT NULL,
  `idprovincia` int(11) NOT NULL,
  `iddistrito` int(11) NOT NULL,
  `full_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarioruta`
--

CREATE TABLE `usuarioruta` (
  `idusuarioruta` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(10,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `battery_life` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gps_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visit`
--

CREATE TABLE `visit` (
  `id` bigint(20) NOT NULL,
  `idcliubic` int(11) DEFAULT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `id_cliente_no_ruc` bigint(20) DEFAULT NULL,
  `ruc_dni` bigint(20) DEFAULT NULL,
  `motivo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `respuesta` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `orden_venta` bigint(20) DEFAULT NULL,
  `estado_pedido` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idusuario` int(11) NOT NULL,
  `web_app` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`idalmacen`);

--
-- Indices de la tabla `almacenlote`
--
ALTER TABLE `almacenlote`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`idbanco`);

--
-- Indices de la tabla `cajad`
--
ALTER TABLE `cajad`
  ADD PRIMARY KEY (`idcajad`);

--
-- Indices de la tabla `cajaguiaventa`
--
ALTER TABLE `cajaguiaventa`
  ADD PRIMARY KEY (`idcajaguiaventa`);

--
-- Indices de la tabla `cajah`
--
ALTER TABLE `cajah`
  ADD PRIMARY KEY (`idcajah`),
  ADD UNIQUE KEY `codigoNB` (`codigoNB`);

--
-- Indices de la tabla `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcliente`),
  ADD UNIQUE KEY `ruc_dni` (`ruc_dni`);

--
-- Indices de la tabla `clientes_no_ruc`
--
ALTER TABLE `clientes_no_ruc`
  ADD PRIMARY KEY (`id_cliente_no_ruc`),
  ADD UNIQUE KEY `id_cliente_no_ruc` (`id_cliente_no_ruc`);

--
-- Indices de la tabla `clienteubicacion`
--
ALTER TABLE `clienteubicacion`
  ADD PRIMARY KEY (`idcliubic`);

--
-- Indices de la tabla `clienteubicacion_prueba`
--
ALTER TABLE `clienteubicacion_prueba`
  ADD PRIMARY KEY (`idcliubic`),
  ADD UNIQUE KEY `ruc_dni` (`ruc_dni`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id_color`);

--
-- Indices de la tabla `cotizaciond`
--
ALTER TABLE `cotizaciond`
  ADD PRIMARY KEY (`idcotizaciond`);

--
-- Indices de la tabla `cotizacionh`
--
ALTER TABLE `cotizacionh`
  ADD PRIMARY KEY (`idcotizacionh`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `distritos`
--
ALTER TABLE `distritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`idempresa`);

--
-- Indices de la tabla `empresassucursales`
--
ALTER TABLE `empresassucursales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`idevaluacion`);

--
-- Indices de la tabla `factguiacompra`
--
ALTER TABLE `factguiacompra`
  ADD PRIMARY KEY (`idfactguiacompra`);

--
-- Indices de la tabla `factura_comprad`
--
ALTER TABLE `factura_comprad`
  ADD PRIMARY KEY (`id_factura_comprad`);

--
-- Indices de la tabla `factura_comprah`
--
ALTER TABLE `factura_comprah`
  ADD PRIMARY KEY (`id_factura_comprah`);

--
-- Indices de la tabla `ficha_recepciond`
--
ALTER TABLE `ficha_recepciond`
  ADD PRIMARY KEY (`id_ficha_recepciond`);

--
-- Indices de la tabla `ficha_recepcionh`
--
ALTER TABLE `ficha_recepcionh`
  ADD PRIMARY KEY (`id_ficha_recepcionh`);

--
-- Indices de la tabla `guia_comprad`
--
ALTER TABLE `guia_comprad`
  ADD PRIMARY KEY (`id_guia_comprad`);

--
-- Indices de la tabla `guia_comprah`
--
ALTER TABLE `guia_comprah`
  ADD PRIMARY KEY (`id_guia_comprah`);

--
-- Indices de la tabla `guia_remisiond`
--
ALTER TABLE `guia_remisiond`
  ADD PRIMARY KEY (`id_guia_remisiond`);

--
-- Indices de la tabla `guia_remisionh`
--
ALTER TABLE `guia_remisionh`
  ADD PRIMARY KEY (`id_guia_remisionh`),
  ADD UNIQUE KEY `codigoNB` (`codigoNB`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`idlote`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nota_creditod`
--
ALTER TABLE `nota_creditod`
  ADD PRIMARY KEY (`id_nota_creditod`);

--
-- Indices de la tabla `nota_creditoh`
--
ALTER TABLE `nota_creditoh`
  ADD PRIMARY KEY (`id_nota_creditoh`),
  ADD UNIQUE KEY `codigoNB` (`codigoNB`);

--
-- Indices de la tabla `orden_comprad`
--
ALTER TABLE `orden_comprad`
  ADD PRIMARY KEY (`id_orden_comprad`);

--
-- Indices de la tabla `orden_comprah`
--
ALTER TABLE `orden_comprah`
  ADD PRIMARY KEY (`id_orden_comprah`);

--
-- Indices de la tabla `orden_ventad`
--
ALTER TABLE `orden_ventad`
  ADD PRIMARY KEY (`id_orden_ventad`);

--
-- Indices de la tabla `orden_ventah`
--
ALTER TABLE `orden_ventah`
  ADD PRIMARY KEY (`id_orden_ventah`),
  ADD UNIQUE KEY `codigoNB` (`codigoNB`);

--
-- Indices de la tabla `pagos_efectuados`
--
ALTER TABLE `pagos_efectuados`
  ADD PRIMARY KEY (`id_pago_efectuado`);

--
-- Indices de la tabla `pagos_recibidos`
--
ALTER TABLE `pagos_recibidos`
  ADD PRIMARY KEY (`id_pago_recibido`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idproveedor`),
  ADD UNIQUE KEY `ruc_dni` (`ruc_dni`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD PRIMARY KEY (`idreclamo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idservicio`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`idsucursal`);

--
-- Indices de la tabla `tipo_empresa`
--
ALTER TABLE `tipo_empresa`
  ADD PRIMARY KEY (`id_tipoemp`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`idtransaccion`);

--
-- Indices de la tabla `transporte`
--
ALTER TABLE `transporte`
  ADD PRIMARY KEY (`idtransporte`);

--
-- Indices de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`idmedida`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `usuarioruta`
--
ALTER TABLE `usuarioruta`
  ADD PRIMARY KEY (`idusuarioruta`);

--
-- Indices de la tabla `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `idalmacen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `almacenlote`
--
ALTER TABLE `almacenlote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bancos`
--
ALTER TABLE `bancos`
  MODIFY `idbanco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cajad`
--
ALTER TABLE `cajad`
  MODIFY `idcajad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cajaguiaventa`
--
ALTER TABLE `cajaguiaventa`
  MODIFY `idcajaguiaventa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cajah`
--
ALTER TABLE `cajah`
  MODIFY `idcajah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idcategoria` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `charges`
--
ALTER TABLE `charges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes_no_ruc`
--
ALTER TABLE `clientes_no_ruc`
  MODIFY `id_cliente_no_ruc` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clienteubicacion`
--
ALTER TABLE `clienteubicacion`
  MODIFY `idcliubic` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clienteubicacion_prueba`
--
ALTER TABLE `clienteubicacion_prueba`
  MODIFY `idcliubic` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id_color` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizaciond`
--
ALTER TABLE `cotizaciond`
  MODIFY `idcotizaciond` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizacionh`
--
ALTER TABLE `cotizacionh`
  MODIFY `idcotizacionh` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresassucursales`
--
ALTER TABLE `empresassucursales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `idevaluacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factguiacompra`
--
ALTER TABLE `factguiacompra`
  MODIFY `idfactguiacompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura_comprad`
--
ALTER TABLE `factura_comprad`
  MODIFY `id_factura_comprad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura_comprah`
--
ALTER TABLE `factura_comprah`
  MODIFY `id_factura_comprah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ficha_recepciond`
--
ALTER TABLE `ficha_recepciond`
  MODIFY `id_ficha_recepciond` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ficha_recepcionh`
--
ALTER TABLE `ficha_recepcionh`
  MODIFY `id_ficha_recepcionh` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia_comprad`
--
ALTER TABLE `guia_comprad`
  MODIFY `id_guia_comprad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia_comprah`
--
ALTER TABLE `guia_comprah`
  MODIFY `id_guia_comprah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia_remisiond`
--
ALTER TABLE `guia_remisiond`
  MODIFY `id_guia_remisiond` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia_remisionh`
--
ALTER TABLE `guia_remisionh`
  MODIFY `id_guia_remisionh` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `idlote` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_creditod`
--
ALTER TABLE `nota_creditod`
  MODIFY `id_nota_creditod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nota_creditoh`
--
ALTER TABLE `nota_creditoh`
  MODIFY `id_nota_creditoh` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_comprad`
--
ALTER TABLE `orden_comprad`
  MODIFY `id_orden_comprad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_comprah`
--
ALTER TABLE `orden_comprah`
  MODIFY `id_orden_comprah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_ventad`
--
ALTER TABLE `orden_ventad`
  MODIFY `id_orden_ventad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_ventah`
--
ALTER TABLE `orden_ventah`
  MODIFY `id_orden_ventah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos_efectuados`
--
ALTER TABLE `pagos_efectuados`
  MODIFY `id_pago_efectuado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos_recibidos`
--
ALTER TABLE `pagos_recibidos`
  MODIFY `id_pago_recibido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idproveedor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  MODIFY `idreclamo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idservicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `idsucursal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_empresa`
--
ALTER TABLE `tipo_empresa`
  MODIFY `id_tipoemp` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `idtransaccion` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transporte`
--
ALTER TABLE `transporte`
  MODIFY `idtransporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `idmedida` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarioruta`
--
ALTER TABLE `usuarioruta`
  MODIFY `idusuarioruta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `visit`
--
ALTER TABLE `visit`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
