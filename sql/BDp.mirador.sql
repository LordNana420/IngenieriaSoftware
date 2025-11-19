-- MySQL Script generado por MySQL Workbench
-- Adaptado para ejecución directa en MySQL
-- Thu Oct 16 21:41:37 2025

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bd_pmirador
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bd_pmirador` DEFAULT CHARACTER SET utf8;
USE `bd_pmirador`;

-- -----------------------------------------------------
-- Table `Estado_Mercancia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Estado_Mercancia` (
  `idEstado_Mercancia` INT NOT NULL,
  `Valor` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEstado_Mercancia`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Tipo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Tipo` (
  `idTipo_Mercancia` INT NOT NULL,
  `Valor` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idTipo_Mercancia`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cliente` (
  `idCliente` INT NOT NULL,
  `Nombre` VARCHAR(45) NULL,
  `Apellido` VARCHAR(45) NULL,
  `Telefono` VARCHAR(45) NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Estado_Empleado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Estado_Empleado` (
  `idEstado_Empleado` INT NOT NULL,
  `Valor` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEstado_Empleado`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Empleado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Empleado` (
  `idEmpleado` INT NOT NULL,
  `Nombre` VARCHAR(45) NULL,
  `Apellido` VARCHAR(45) NULL,
  `Telefono` VARCHAR(45) NULL,
  `Estado_Empleado_idEstado_Empleado` INT NOT NULL,
  PRIMARY KEY (`idEmpleado`),
  INDEX `fk_Empleado_Estado_Empleado1_idx` (`Estado_Empleado_idEstado_Empleado` ASC),
  CONSTRAINT `fk_Empleado_Estado_Empleado1`
    FOREIGN KEY (`Estado_Empleado_idEstado_Empleado`)
    REFERENCES `Estado_Empleado` (`idEstado_Empleado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `venta` (
  `idventa` INT NOT NULL,
  `Cliente_idCliente` INT NOT NULL,
  `Empleado_idEmpleado` INT NOT NULL,
  PRIMARY KEY (`idventa`),
  INDEX `fk_venta_Cliente1_idx` (`Cliente_idCliente` ASC),
  INDEX `fk_venta_Empleado1_idx` (`Empleado_idEmpleado` ASC),
  CONSTRAINT `fk_venta_Cliente1`
    FOREIGN KEY (`Cliente_idCliente`)
    REFERENCES `Cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_venta_Empleado1`
    FOREIGN KEY (`Empleado_idEmpleado`)
    REFERENCES `Empleado` (`idEmpleado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Inventario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Inventario` (
  `idInventario` INT NOT NULL,
  `Cantidad_Total` FLOAT NULL,
  `Inventariocol` VARCHAR(45) NULL,
  `venta_idventa` INT NOT NULL,
  PRIMARY KEY (`idInventario`),
  INDEX `fk_Inventario_venta1_idx` (`venta_idventa` ASC),
  CONSTRAINT `fk_Inventario_venta1`
    FOREIGN KEY (`venta_idventa`)
    REFERENCES `venta` (`idventa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Mercancia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Mercancia` (
  `idMercancia` INT NOT NULL,
  `Nombre` VARCHAR(45) NOT NULL,
  `Fecha_vencimiento` DATE NOT NULL,
  `Fecha_Ingreso` DATE NOT NULL,
  `Cantidad_Mercancia` FLOAT NULL,
  `Precio_Unitario` FLOAT NULL,
  `Estado_Mercancia_idEstado_Mercancia` INT NOT NULL,
  `Tipo_idEstado_Tipo` INT NOT NULL,
  `Inventario_idInventario` INT NOT NULL,
  PRIMARY KEY (`idMercancia`, `Fecha_Ingreso`),
  INDEX `fk_Mercancia_Estado_Mercancia_idx` (`Estado_Mercancia_idEstado_Mercancia` ASC),
  INDEX `fk_Mercancia_Tipo1_idx` (`Tipo_idEstado_Tipo` ASC),
  INDEX `fk_Mercancia_Inventario1_idx` (`Inventario_idInventario` ASC),
  CONSTRAINT `fk_Mercancia_Estado_Mercancia`
    FOREIGN KEY (`Estado_Mercancia_idEstado_Mercancia`)
    REFERENCES `Estado_Mercancia` (`idEstado_Mercancia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Mercancia_Tipo1`
    FOREIGN KEY (`Tipo_idEstado_Tipo`)
    REFERENCES `Tipo` (`idTipo_Mercancia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Mercancia_Inventario1`
    FOREIGN KEY (`Inventario_idInventario`)
    REFERENCES `Inventario` (`idInventario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Estado_Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Estado_Proveedor` (
  `idEstado_Proveedor` INT NOT NULL,
  `Valor` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEstado_Proveedor`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Proveedor` (
  `NIT` INT NOT NULL,
  `Direccion` VARCHAR(45) NULL,
  `Nombre_Proveedor` VARCHAR(45) NULL,
  `Estado_Proveedor_idEstado_Mercancia` INT NOT NULL,
  PRIMARY KEY (`NIT`),
  INDEX `fk_Proveedor_Estado_Proveedor1_idx` (`Estado_Proveedor_idEstado_Mercancia` ASC),
  CONSTRAINT `fk_Proveedor_Estado_Proveedor1`
    FOREIGN KEY (`Estado_Proveedor_idEstado_Mercancia`)
    REFERENCES `Estado_Proveedor` (`idEstado_Proveedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Mercancia_has_Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Mercancia_has_Proveedor` (
  `Mercancia_idMercancia` INT NOT NULL,
  `Mercancia_Fecha_Ingreso` DATE NOT NULL,
  `Proveedor_NIT` INT NOT NULL,
  PRIMARY KEY (`Mercancia_idMercancia`, `Mercancia_Fecha_Ingreso`, `Proveedor_NIT`),
  INDEX `fk_Mercancia_has_Proveedor_Proveedor1_idx` (`Proveedor_NIT` ASC),
  INDEX `fk_Mercancia_has_Proveedor_Mercancia1_idx` (`Mercancia_idMercancia` ASC, `Mercancia_Fecha_Ingreso` ASC),
  CONSTRAINT `fk_Mercancia_has_Proveedor_Mercancia1`
    FOREIGN KEY (`Mercancia_idMercancia`, `Mercancia_Fecha_Ingreso`)
    REFERENCES `Mercancia` (`idMercancia`, `Fecha_Ingreso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Mercancia_has_Proveedor_Proveedor1`
    FOREIGN KEY (`Proveedor_NIT`)
    REFERENCES `Proveedor` (`NIT`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Lote`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Lote` (
  `idLote` INT NOT NULL,
  `venta_idventa` INT NOT NULL,
  PRIMARY KEY (`idLote`),
  INDEX `fk_Lote_venta1_idx` (`venta_idventa` ASC),
  CONSTRAINT `fk_Lote_venta1`
    FOREIGN KEY (`venta_idventa`)
    REFERENCES `venta` (`idventa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Producto` (
  `idProducto` INT NOT NULL,
  `Lote_idLote` INT NOT NULL,
  `Inventario_idInventario` INT NOT NULL,
  PRIMARY KEY (`idProducto`),
  INDEX `fk_Producto_Lote1_idx` (`Lote_idLote` ASC),
  INDEX `fk_Producto_Inventario1_idx` (`Inventario_idInventario` ASC),
  CONSTRAINT `fk_Producto_Lote1`
    FOREIGN KEY (`Lote_idLote`)
    REFERENCES `Lote` (`idLote`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Producto_Inventario1`
    FOREIGN KEY (`Inventario_idInventario`)
    REFERENCES `Inventario` (`idInventario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Restaurar configuraciones
-- -----------------------------------------------------
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- ------------------------------
-- TABLA Estado_Mercancia
-- ------------------------------
INSERT INTO Estado_Mercancia (idEstado_Mercancia, Valor) VALUES
(1, 'Disponible'),
(2, 'Agotada'),
(3, 'Vencida');

-- ------------------------------
-- TABLA Tipo
-- ------------------------------
INSERT INTO Tipo (idTipo_Mercancia, Valor) VALUES
(1, 'Bebida'),
(2, 'Comida'),
(3, 'Aseo'),
(4, 'Lácteo');

-- ------------------------------
-- TABLA Cliente
-- ------------------------------
INSERT INTO Cliente (idCliente, Nombre, Apellido, Telefono, estado) VALUES
(1, 'Ana', 'Ramírez', '3001234567'),
(2, 'Carlos', 'Gómez', '3017654321'),
(3, 'Laura', 'Torres', '3029876543');

-- ------------------------------
-- TABLA Estado_Empleado
-- ------------------------------
INSERT INTO Estado_Empleado (idEstado_Empleado, Valor) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- ------------------------------
-- TABLA Empleado
-- ------------------------------
INSERT INTO Empleado (idEmpleado, Nombre, Apellido, Telefono, Estado_Empleado_idEstado_Empleado) VALUES
(1, 'María', 'Pérez', '3159876543', 1),
(2, 'Jorge', 'López', '3161237890', 1),
(3, 'Sandra', 'Morales', '3176543210', 2);

-- ------------------------------
-- TABLA venta
-- ------------------------------
INSERT INTO venta (idventa, Cliente_idCliente, Empleado_idEmpleado) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 1);

-- ------------------------------
-- TABLA Inventario
-- ------------------------------
INSERT INTO Inventario (idInventario, Cantidad_Total, Inventariocol, venta_idventa) VALUES
(1, 100, 'Depósito Central', 1),
(2, 50, 'Sucursal Norte', 2),
(3, 200, 'Sucursal Sur', 3);

-- ------------------------------
-- TABLA Mercancia
-- ------------------------------
INSERT INTO Mercancia (
  idMercancia, Nombre, Fecha_vencimiento, Fecha_Ingreso, Cantidad_Mercancia, Precio_Unitario,
  Estado_Mercancia_idEstado_Mercancia, Tipo_idEstado_Tipo, Inventario_idInventario
) VALUES
(1, 'Agua Mineral 1L', '2026-05-01', '2025-10-10', 30, 2500, 1, 1, 1),
(2, 'Leche Entera 1L', '2025-12-15', '2025-10-11', 20, 3500, 1, 4, 2),
(3, 'Detergente 1kg', '2027-01-10', '2025-10-12', 15, 8500, 1, 3, 1),
(4, 'Galletas de Chocolate', '2026-02-20', '2025-10-09', 10, 4500, 2, 2, 3);

-- ------------------------------
-- TABLA Estado_Proveedor
-- ------------------------------
INSERT INTO Estado_Proveedor (idEstado_Proveedor, Valor) VALUES
(1, 'Activo'),
(2, 'Suspendido');

-- ------------------------------
-- TABLA Proveedor
-- ------------------------------
INSERT INTO Proveedor (NIT, Direccion, Nombre_Proveedor, Estado_Proveedor_idEstado_Mercancia) VALUES
(9001, 'Calle 10 #45-12', 'Distribuidora Los Andes', 1),
(9002, 'Carrera 25 #12-20', 'Alimentos del Valle', 1),
(9003, 'Avenida 3 #5-30', 'Suministros del Sur', 2);

-- ------------------------------
-- TABLA Mercancia_has_Proveedor
-- ------------------------------
INSERT INTO Mercancia_has_Proveedor (Mercancia_idMercancia, Mercancia_Fecha_Ingreso, Proveedor_NIT) VALUES
(1, '2025-10-10', 9001),
(2, '2025-10-11', 9002),
(3, '2025-10-12', 9003),
(4, '2025-10-09', 9001);

-- ------------------------------
-- TABLA Lote
-- ------------------------------
INSERT INTO Lote (idLote, venta_idventa) VALUES
(1, 1),
(2, 2),
(3, 3);

-- ------------------------------
-- TABLA Producto
-- ------------------------------
INSERT INTO Producto (idProducto, Lote_idLote, Inventario_idInventario) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 2, 2),
(4, 3, 3),
(5, 3, 3);

ALTER TABLE Mercancia
ADD COLUMN Stock_Minimo INT NOT NULL DEFAULT 10;

ALTER TABLE Mercancia
ADD COLUMN Stock_Maximo INT NOT NULL;
UPDATE Producto SET stock_maximo = 100 WHERE idProducto = 1; -- Agua Mineral 1L
UPDATE Producto SET stock_maximo = 50  WHERE idProducto = 2; -- Leche Entera 1L
UPDATE Producto SET stock_maximo = 40  WHERE idProducto = 3; -- Detergente 1kg
UPDATE Producto SET stock_maximo = 30  WHERE idProducto = 4; -- Galletas de Chocolate

CREATE TABLE IF NOT EXISTS `Producto_has_venta` (
  `Producto_idProducto` INT NOT NULL,
  `Venta_idVenta` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `Precio_unitario` INT NOT NULL,
  `Precio` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`Producto_idProducto`, `Venta_idVenta`),
  INDEX `fk_Producto_has_venta_Producto1_idx` (`Producto_idProducto` ASC),
  INDEX `fk_Producto_has_venta_Venta1_idx` (`Venta_idVenta` ASC),
  CONSTRAINT `fk_Producto_has_venta_Producto1`
    FOREIGN KEY (`Producto_idProducto`)
    REFERENCES `Producto` (`idProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Producto_has_venta_Venta1`
    FOREIGN KEY (`Venta_idVenta`)
    REFERENCES `venta` (`idVenta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

ALTER TABLE venta 
ADD COLUMN total INT NOT NULL;

ALTER TABLE venta 
ADD COLUMN fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;



