 
 
SET SCHEMA 'METAMSUR_CAJA_DATA';
 
/* SQLINES DEMO *** ema [Compras]    Script Date: 8/07/2024 12:35:26 ******/
CREATE SCHEMA Compras;
/* SQLINES DEMO *** ema [General]    Script Date: 8/07/2024 12:35:26 ******/
CREATE SCHEMA General;
/* SQLINES DEMO *** ema [Logistica]    Script Date: 8/07/2024 12:35:26 ******/
CREATE SCHEMA Logistica;
/* SQLINES DEMO *** ema [Sunat]    Script Date: 8/07/2024 12:35:26 ******/
CREATE SCHEMA Sunat;
/* SQLINES DEMO *** ema [Tesoreria]    Script Date: 8/07/2024 12:35:26 ******/
CREATE SCHEMA Tesoreria;
/* SQLINES DEMO *** ema [Ventas]    Script Date: 8/07/2024 12:35:26 ******/
CREATE SCHEMA Ventas;
/* SQLINES DEMO *** rDefinedFunction [dbo].[SumaColumnas]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE OR REPLACE FUNCTION SumaColumnas()
RETURNS INT
AS $$
    DECLARE v_Resultado INT;
BEGIN
    v_Resultado := (SELECT SUM(total - descuento + recargo) FROM Compras.Ordenes);
    RETURN v_Resultado;
END;
$$ LANGUAGE plpgsql;
/* SQLINES DEMO *** le [Compras].[documentos]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Compras.documentos(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	id_detalle varchar(10) NOT NULL,
	fechaEmi date NOT NULL,
	fechaVen date NULL,
	id_t10tdoc varchar(2) NOT NULL,
	id_t02tcom varchar(1) NOT NULL,
	id_entidades varchar(11) NOT NULL,
	id_t04tipmon varchar(3) NOT NULL,
	id_tasasIgv int NOT NULL,
	serie varchar(4) NOT NULL,
	numero varchar(10) NOT NULL,
	totalBi Double precision NULL,
	descuentoBi Double precision NULL,
	recargoBi Double precision NULL,
	basImp Double precision NULL,
	IGV Double precision NULL,
	totalNg Double precision NULL,
	descuentoNg Double precision NULL,
	recargoNg Double precision NULL,
	noGravadas Double precision NULL,
	otroTributo Double precision NULL,
	precio Double precision NULL,
	detraccion Double precision NULL,
	montoNeto Double precision NULL,
	id_t10tdocMod varchar(2) NULL,
	serieMon varchar(4) NULL,
	observaciones varchar(500) NULL,
	numeroMod varchar(10) NULL,
	id_Usuario int NOT NULL,
	fecha_Registro Timestamp(3) NOT NULL,
	id_dest_tipcaja int NULL,
 CONSTRAINT PK_documentos_2 PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[datos]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.datos(
	id int NOT NULL,
	dato varchar(500) NOT NULL
);
/* SQLINES DEMO *** le [General].[entidades]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.entidades(
	id varchar(11) NOT NULL,
	descripcion varchar(500) NOT NULL,
	estado_contribuyente varchar(50) NULL,
	estado_domiclio varchar(50) NULL,
	provincia varchar(50) NULL,
	distrito varchar(50) NULL,
	direccion varchar(500) NULL,
	idt02doc varchar(1) NOT NULL,
	cta1 varchar(100) NULL,
	cta2 varchar(100) NULL,
	cta3 varchar(100) NULL,
	telefono varchar(20) NULL,
	banco varchar(100) NULL,
 CONSTRAINT PK_entidades_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[estados]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.estados(
	id int NOT NULL,
	descripcion varchar(50) NOT NULL,
 CONSTRAINT PK_estados_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[formasdepago]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.formasdepago(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	descripcion varchar(50) NOT NULL,
	dias int NOT NULL,
 CONSTRAINT PK_formasdepago_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[meses]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.meses(
	id int NOT NULL,
	descripcion varchar(250) NOT NULL,
 CONSTRAINT PK_meses PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[sino]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.sino(
	id int NOT NULL,
	descripcion varchar(2) NOT NULL,
 CONSTRAINT PK_sino_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[tipcamsunat]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.tipcamsunat(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	fecha date NOT NULL,
	compra Double precision NOT NULL,
	venta Double precision NOT NULL,
 CONSTRAINT PK_tipcamsunat_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [General].[usuarios]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE General.usuarios(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	usuario varchar(60) NOT NULL,
	contraseña varchar(60) NOT NULL,
 CONSTRAINT PK_usuarios_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Logistica].[detalle]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Logistica.detalle(
	id_familias varchar(3) NOT NULL,
	id_subfamilia varchar(3) NOT NULL,
	id varchar(10) NOT NULL,
	descripcion varchar(100) NOT NULL,
	id_cuenta int NULL,
 CONSTRAINT PK_detalle PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Logistica].[familias]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Logistica.familias(
	id varchar(3) NOT NULL,
	descripcion varchar(100) NOT NULL,
	id_tipofamilias int NULL,
 CONSTRAINT PK_familias_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Logistica].[subfamilias]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Logistica.subfamilias(
	id_familias varchar(3) NOT NULL,
	id varchar(3) NOT NULL,
	desripcion varchar(50) NOT NULL
);
/* SQLINES DEMO *** le [Logistica].[tipofamilia]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Logistica.tipofamilia(
	id int NOT NULL,
	descripcion varchar(100) NOT NULL
);
/* SQLINES DEMO *** le [Sunat].[tabla02_tipodedocumentodeidentidad]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Sunat.tabla02_tipodedocumentodeidentidad(
	id varchar(1) NOT NULL,
	descripcion varchar(100) NULL,
	abreviado varchar(10) NULL,
 CONSTRAINT PK_tabla02_tipodedocumentodeidentidad_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Sunat].[tabla04_tipodemoneda]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Sunat.tabla04_tipodemoneda(
	Id varchar(3) NOT NULL,
	Descripcion varchar(50) NULL,
 CONSTRAINT PK_tabla04_tipodemoneda_id PRIMARY KEY 
(
	Id
) 
);
/* SQLINES DEMO *** le [Sunat].[tabla10_tipodecomprobantedepagoodocumento]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Sunat.tabla10_tipodecomprobantedepagoodocumento(
	id varchar(2) NOT NULL,
	descripcion varchar(700) NULL,
 CONSTRAINT PK_s_tabla10_tipodecomprobantedepagoodocumento_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Sunat].[tasas_igv]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Sunat.tasas_igv(
	id int NOT NULL,
	tasa varchar(10) NOT NULL,
	numero Double precision NOT NULL,
 CONSTRAINT PK_tasas_igv_id PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[aperturas]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.aperturas(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	id_tipo int NOT NULL,
	numero int NOT NULL,
	año varchar(4) NOT NULL,
	id_mes int NOT NULL,
	fecha date NOT NULL,
 CONSTRAINT PK_aperturas PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[cuentas]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.cuentas(
	id int NOT NULL,
	Descripcion varchar(100) NULL,
	id_tCuenta int NULL,
 CONSTRAINT PK_Libros PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[debehaber]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.debehaber(
	id int NOT NULL,
	descripcion varchar(50) NULL,
 CONSTRAINT PK_debehaber PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[libros]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.libros(
	id int NOT NULL,
	descripcion varchar(50) NULL,
 CONSTRAINT PK_libros_1 PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[movimientosdecaja]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.movimientosdecaja(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	id_libro int NOT NULL,
	id_apertura int NULL,
	mov int NOT NULL,
	fec date NOT NULL,
	id_documentos int NULL,
	id_cuentas int NOT NULL,
	id_dh int NOT NULL,
	monto Double precision NOT NULL,
	montodo Double precision NULL,
	fecha_registro Timestamp(3) NOT NULL,
	glosa varchar(300) NOT NULL,
 CONSTRAINT PK_movimientosdecaja PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[tipoDeCaja]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.tipoDeCaja(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	descripcion varchar(100) NOT NULL,
 CONSTRAINT PK_tipoDeCaja PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Tesoreria].[tipodecuenta]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Tesoreria.tipodecuenta(
	id int NOT NULL,
	descripcion varchar(50) NOT NULL,
 CONSTRAINT PK_tipodecuenta PRIMARY KEY 
(
	id
) 
);
/* SQLINES DEMO *** le [Ventas].[documentos]    Script Date: 8/07/2024 12:35:26 ******/
/* SET ANSI_NULLS ON */
 
/* SET QUOTED_IDENTIFIER ON */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
CREATE TABLE Ventas.documentos(
	id int GENERATED ALWAYS AS IDENTITY(START WITH 1 INCREMENT BY 1) NOT NULL,
	id_detalle varchar(10) NOT NULL,
	fechaEmi date NOT NULL,
	fechaVen date NOT NULL,
	id_t10tdoc varchar(2) NOT NULL,
	id_t02tcom varchar(1) NOT NULL,
	id_entidades varchar(11) NOT NULL,
	id_t04tipmon varchar(3) NOT NULL,
	id_tasasIgv int NOT NULL,
	serie varchar(4) NOT NULL,
	numero varchar(10) NOT NULL,
	totalBi Double precision NULL,
	descuentoBi Double precision NULL,
	recargoBi Double precision NULL,
	basImp Double precision NULL,
	IGV Double precision NULL,
	totalNg Double precision NULL,
	descuentoNg Double precision NULL,
	recargoNg Double precision NULL,
	noGravadas Double precision NULL,
	otroTributo Double precision NULL,
	precio Double precision NULL,
	detraccion Double precision NULL,
	montoNeto Double precision NULL,
	id_t10tdocMod varchar(2) NULL,
	serieMon varchar(4) NULL,
	numeroMod varchar(10) NULL,
	observaciones varchar(500) NULL,
	id_Usuario int NOT NULL,
	fecha_Registro Timestamp(3) NOT NULL,
	id_dest_tipcaja int NULL,
 CONSTRAINT PK_documentos_1 PRIMARY KEY 
(
	id
) 
);
/* SET IDENTITY_INSERT [Compras].[documentos] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (292, '103000001', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '00', '1', '46694933', 'PEN', 0, '0000', '1', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 366000, 0, 366000, NULL, NULL, NULL, NULL, 'PRESTAMO DE SOCIO MACHACA', NULL, 1, CAST('2024-05-17T12:27:52.023' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (294, '102102002', CAST('2024-05-18' AS Date), CAST('2024-05-20' AS Date), '02', '6', '10706743788', 'PEN', 0, 'E001', '354', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 120, 0, 120, NULL, NULL, NULL, NULL, 'VIATICOS CONTADOR_RICARDO', NULL, 1, CAST('2024-05-20T17:44:41.067' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (295, '101104001', CAST('2024-05-19' AS Date), CAST('2024-05-20' AS Date), '01', '6', '20100075858', 'PEN', 1, 'FJ04', '118549', NULL, NULL, NULL, 93.25, 16.78, NULL, NULL, NULL, 0, 0, 110.03, NULL, NULL, NULL, NULL, 'COMBUSTIBLE MIRLESS - DILIGENCIAS', NULL, 1, CAST('2024-05-20T17:52:18.627' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (297, '102101004', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '1', NULL, NULL, NULL, 5, 0, NULL, NULL, NULL, 0, 0, 5, NULL, NULL, NULL, NULL, 'CORRECTOR EN CINTA', NULL, 1, CAST('2024-05-22T18:17:14.933' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (298, '102101002', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '2', NULL, NULL, NULL, 15.5, 0, NULL, NULL, NULL, 0, 0, 15.5, NULL, NULL, NULL, NULL, 'ARTICULOS DE LIMPIEZA', NULL, 1, CAST('2024-05-22T18:19:01.263' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (299, '102102002', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '3', NULL, NULL, NULL, 35, 0, NULL, NULL, NULL, 0, 0, 35, NULL, NULL, NULL, NULL, 'ALMUERZO PERSONAL - OFICINA', NULL, 1, CAST('2024-05-23T16:21:43.080' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (300, '101104001', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '4', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 150, 0, 150, NULL, NULL, NULL, NULL, 'COMBUSTIBLES', NULL, 1, CAST('2024-05-27T15:03:56.710' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (301, '003000002', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '76', '1', '10000001', 'PEN', 0, '0000', '1', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 6000, 0, 6000, NULL, NULL, NULL, NULL, 'COMBUSTIBLE ADELANTO', NULL, 1, CAST('2024-05-27T15:05:15.010' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (302, '001000001', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '1', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 500, 0, 500, NULL, NULL, NULL, NULL, 'DINERO PARA CAJA CHICA', NULL, 1, CAST('2024-05-27T15:05:15.907' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (303, '101102005', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '5', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 260, 0, 260, NULL, NULL, NULL, NULL, 'PAGO COLEGIO INGENIEROS', NULL, 1, CAST('2024-05-27T15:05:16.687' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (304, '003000002', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '76', '1', '48227891', 'PEN', 0, '0000', '2', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 3400, 0, 3400, NULL, NULL, NULL, NULL, 'ADELANTO GUSTABO MENDOZA', NULL, 1, CAST('2024-05-27T15:05:21.003' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (305, '003000002', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '76', '1', '30844237', 'PEN', 0, '0000', '3', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 10000, 0, 10000, NULL, NULL, NULL, NULL, 'COMBUSTIBLE ADELANTO GUTIERREZ', NULL, 1, CAST('2024-05-27T15:05:21.010' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (306, '101104001', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18892', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, 'COMBUSTIBLE MOTO', NULL, 1, CAST('2024-05-27T15:05:21.013' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (307, '101104001', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18887', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, 'COMBUSTIBLE AUTO', NULL, 1, CAST('2024-05-27T15:05:21.020' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (308, '101102003', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '6', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 206.4, 0, 206.4, NULL, NULL, NULL, NULL, 'PAGO SCTR PERSONAL', NULL, 1, CAST('2024-05-27T15:05:21.023' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (310, '101102005', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '01', '6', '20608499211', 'PEN', 1, 'FPP1', '18663', NULL, NULL, NULL, 80.51, 14.49, NULL, NULL, NULL, 0, 0, 95, NULL, NULL, NULL, NULL, 'CONSUMO-COMIDA PRESIDENTE PASCANA', NULL, 1, CAST('2024-05-27T15:05:21.033' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (311, '101101004', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '7', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ARMANDO SANIZO PARABRIZAS TRACTOR', NULL, 1, CAST('2024-05-27T15:05:21.037' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (312, '101102003', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '8', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 50.94, 0, 50.94, NULL, NULL, NULL, NULL, 'PAGO VIDA LEY', NULL, 1, CAST('2024-05-27T15:05:21.040' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (313, '102102002', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '9', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 168, 0, 168, NULL, NULL, NULL, NULL, 'PASAJES MILUSKA', NULL, 1, CAST('2024-05-27T15:05:21.043' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (314, '101102005', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '10', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2728.87, 0, 2728.87, NULL, NULL, NULL, NULL, 'COMPRA ALOTES TRACTORES', NULL, 1, CAST('2024-05-27T15:05:21.047' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (315, '003000002', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '76', '1', '04648307', 'PEN', 0, '0000', '4', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ADELANTO RONAL RIVERA', NULL, 1, CAST('2024-05-27T15:05:21.050' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (317, '102102002', CAST('2024-05-04' AS Date), CAST('2024-05-04' AS Date), '00', '1', '40521566', 'PEN', 0, '0000', '1', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 5450, 0, 5450, NULL, NULL, NULL, NULL, 'JAVIER SEHUIN', NULL, 1, CAST('2024-05-27T15:05:21.060' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (318, '003000002', CAST('2024-05-04' AS Date), CAST('2024-05-04' AS Date), '76', '1', '48227891', 'PEN', 0, '0000', '5', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 800, 0, 800, NULL, NULL, NULL, NULL, 'ADELANTO GUSTABO MENDOZA', NULL, 1, CAST('2024-05-27T15:05:21.063' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (319, '003000002', CAST('2024-05-04' AS Date), CAST('2024-05-04' AS Date), '76', '1', '42333707', 'PEN', 0, '0000', '6', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 3000, 0, 3000, NULL, NULL, NULL, NULL, 'ADELANTO RAUL QUISPE GRIFO DAVICAM', NULL, 1, CAST('2024-05-27T15:05:21.067' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (320, '101104001', CAST('2024-05-05' AS Date), CAST('2024-05-05' AS Date), '01', '6', '20608585649', 'PEN', 1, 'F001', '2401', NULL, NULL, NULL, 423.73, 76.27, NULL, NULL, NULL, 0, 0, 500, NULL, NULL, NULL, NULL, 'COMBUSTIBLE', NULL, 1, CAST('2024-05-27T15:05:21.070' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (322, '001000001', CAST('2024-05-05' AS Date), CAST('2024-05-05' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '2', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 650, 0, 650, NULL, NULL, NULL, NULL, 'TRANSFERENCIA CAJA CHICA', NULL, 1, CAST('2024-05-27T15:05:21.083' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (323, '003000002', CAST('2024-05-05' AS Date), CAST('2024-05-05' AS Date), '76', '1', '30842503', 'PEN', 0, '0000', '7', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2000, 0, 2000, NULL, NULL, NULL, NULL, 'ADELANTO FLORA QUIPE', NULL, 1, CAST('2024-05-27T15:05:21.087' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (324, '101101004', CAST('2024-05-05' AS Date), CAST('2024-05-05' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '11', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1060, 0, 1060, NULL, NULL, NULL, NULL, 'SERV FORMULA 1', NULL, 1, CAST('2024-05-27T15:05:21.090' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (325, '102102002', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '00', '1', '30846465', 'PEN', 0, '0000', '1', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 3500, 0, 3500, NULL, NULL, NULL, NULL, 'JOSE SEHUIN', NULL, 1, CAST('2024-05-27T15:05:21.093' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (326, '102102002', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '00', '1', '30846465', 'PEN', 0, '0000', '2', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1950, 0, 1950, NULL, NULL, NULL, NULL, 'JOSE SEHUIN', NULL, 1, CAST('2024-05-27T15:05:21.100' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (327, '101104001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18931', NULL, NULL, NULL, 127.12, 22.88, NULL, NULL, NULL, 0, 0, 150, NULL, NULL, NULL, NULL, 'COMBUSTIBLE CAMIONETA', NULL, 1, CAST('2024-05-27T15:05:21.103' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (328, '101104001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18937', NULL, NULL, NULL, 84.75, 15.25, NULL, NULL, NULL, 0, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE TRACTOR', NULL, 1, CAST('2024-05-27T15:05:21.110' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (329, '101104001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18932', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, 'COMBUSTIBLE CAMION', NULL, 1, CAST('2024-05-27T15:05:21.113' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (330, '003000002', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '76', '1', '46878984', 'PEN', 0, '0000', '8', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 500, 0, 500, NULL, NULL, NULL, NULL, 'YEMIRA QUISPE HUAMAN', NULL, 1, CAST('2024-05-27T15:06:27.500' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (331, '003000002', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '76', '1', '46878984', 'PEN', 0, '0000', '9', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 6000, 0, 6000, NULL, NULL, NULL, NULL, 'ADELANTO ERICA PEREZ ANCCO', NULL, 1, CAST('2024-05-27T15:06:27.513' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (332, '003000002', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '76', '1', '48227891', 'PEN', 0, '0000', '10', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 3000, 0, 3000, NULL, NULL, NULL, NULL, 'ADELANTO MENDOZA', NULL, 1, CAST('2024-05-27T15:06:27.523' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (333, '101103001', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '12', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1500, 0, 1500, NULL, NULL, NULL, NULL, 'PAGO CESAR PORTUGAL', NULL, 1, CAST('2024-05-27T15:06:27.527' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (335, '101104001', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '01', '6', '20127765279', 'PEN', 1, 'F31P', '14258', NULL, NULL, NULL, 84.75, 15.25, NULL, NULL, NULL, 0, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE CAMIONETA', NULL, 1, CAST('2024-05-27T15:06:27.537' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (336, '003000002', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '76', '1', '46878984', 'PEN', 0, '0000', '11', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 300, 0, 300, NULL, NULL, NULL, NULL, 'ADELANTO LOPEZ', NULL, 1, CAST('2024-05-27T15:06:27.540' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (337, '003000002', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '76', '6', '20539682289', 'PEN', 0, '0000', '12', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 4000, 0, 4000, NULL, NULL, NULL, NULL, 'ADELANTO MIGUEL CALDERON', NULL, 1, CAST('2024-05-27T15:06:27.543' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (338, '102102002', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '13', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 870, 0, 870, NULL, NULL, NULL, NULL, 'ROSMERY PACORI-CANASTAS', NULL, 1, CAST('2024-05-27T15:06:27.550' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (339, '101102005', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '14', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 240, 0, 240, NULL, NULL, NULL, NULL, 'PAGO CARPINTERO YASMANI', NULL, 1, CAST('2024-05-27T15:06:27.553' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (340, '001000001', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '3', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 6160.7, 0, 6160.7, NULL, NULL, NULL, NULL, 'ADELANTO ROSA UGARTE VELA', NULL, 1, CAST('2024-05-27T15:06:27.560' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (341, '003000002', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '76', '1', '29310512', 'PEN', 0, '0000', '13', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 7269, 0, 7269, NULL, NULL, NULL, NULL, 'TRANSPAL MANING AND CONSTRUC-MARIO TORRES', NULL, 1, CAST('2024-05-27T15:06:27.567' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (342, '101102005', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '15', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1398.2, 0, 1398.2, NULL, NULL, NULL, NULL, 'AGRO FERRETERIA', NULL, 1, CAST('2024-05-27T15:06:27.570' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (343, '101104001', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18972', NULL, NULL, NULL, 84.75, 15.25, NULL, NULL, NULL, 0, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE', NULL, 1, CAST('2024-05-27T15:06:32.020' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (344, '101104001', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '18973', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, 'COMBUSTIBLE', NULL, 1, CAST('2024-05-27T15:06:32.027' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (345, '003000002', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '76', '1', '48227891', 'PEN', 0, '0000', '14', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2000, 0, 2000, NULL, NULL, NULL, NULL, 'ADELANTO MENDOZA', NULL, 1, CAST('2024-05-27T15:06:32.030' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (346, '003000002', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '76', '1', '29296086', 'PEN', 0, '0000', '15', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 6160.7, 0, 6160.7, NULL, NULL, NULL, NULL, 'ADELANTO ROSA JULIANA UGARTE VELA', NULL, 1, CAST('2024-05-27T15:06:32.037' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (347, '003000002', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '76', '1', '42333707', 'PEN', 0, '0000', '16', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2500, 0, 2500, NULL, NULL, NULL, NULL, 'ADELANTO RAUL QUISPE', NULL, 1, CAST('2024-05-27T15:06:32.040' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (348, '003000002', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '76', '1', '30847515', 'PEN', 0, '0000', '17', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 5390.1, 0, 5390.1, NULL, NULL, NULL, NULL, 'ADELNATO MOLINO SENEN', NULL, 1, CAST('2024-05-27T15:06:32.047' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (349, '003000002', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '76', '1', '30425342', 'PEN', 0, '0000', '18', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 5000, 0, 5000, NULL, NULL, NULL, NULL, 'ADELANTO ELOY MOLINA', NULL, 1, CAST('2024-05-27T15:06:32.050' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (355, '102101002', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '16', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1500, 0, 1500, NULL, NULL, NULL, NULL, 'PAGO CONTRATO', NULL, 1, CAST('2024-05-27T15:06:32.080' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (356, '003000002', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '76', '1', '80267101', 'PEN', 0, '0000', '19', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2000, 0, 2000, NULL, NULL, NULL, NULL, 'ADELANTO URIEL', NULL, 1, CAST('2024-05-27T15:06:32.087' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (357, '101104001', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '01', '6', '20100075858', 'PEN', 1, 'FJ04', '118204', NULL, NULL, NULL, 127.12, 22.88, NULL, NULL, NULL, 0, 0, 150, NULL, NULL, NULL, NULL, 'COMBUSTIBLE', NULL, 1, CAST('2024-05-27T15:06:32.090' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (358, '102102002', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '00', '1', '40521566', 'PEN', 0, '0000', '4', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'JAVIER SEHUIN LAPTOP', NULL, 1, CAST('2024-05-27T15:06:32.097' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (359, '101104001', CAST('2024-05-12' AS Date), CAST('2024-05-12' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '19020', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, 'COMBUSTIBLE-AUTO', NULL, 1, CAST('2024-05-27T15:06:32.100' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (360, '101104001', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '19028', NULL, NULL, NULL, 84.75, 15.25, NULL, NULL, NULL, 0, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE-CAMIONETA', NULL, 1, CAST('2024-05-27T15:06:32.107' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (362, '003000002', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '76', '1', '48227891', 'PEN', 0, '0000', '20', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ADELANTO GUSTAVO MENDOZA', NULL, 1, CAST('2024-05-27T15:06:32.117' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (363, '003000002', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '76', '1', '42333707', 'PEN', 0, '0000', '21', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 800, 0, 800, NULL, NULL, NULL, NULL, 'ADELANTO RAUL QUISPE', NULL, 1, CAST('2024-05-27T15:06:32.123' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (364, '001000001', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '6', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 500, 0, 500, NULL, NULL, NULL, NULL, 'CAJA CHICA', NULL, 1, CAST('2024-05-27T15:06:32.127' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (365, '003000002', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '76', '1', '42333707', 'PEN', 0, '0000', '22', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 800, 0, 800, NULL, NULL, NULL, NULL, 'ADELANTO RAUL QUISPE', NULL, 1, CAST('2024-05-27T15:06:32.130' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (366, '101103001', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '17', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 270, 0, 270, NULL, NULL, NULL, NULL, 'ASESORIA DE SEGURIDAD', NULL, 1, CAST('2024-05-27T15:06:32.137' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (367, '003000002', CAST('2024-05-15' AS Date), CAST('2024-05-15' AS Date), '76', '1', '29596652', 'PEN', 0, '0000', '23', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ADELANTO JOSE LUIS CONDORI', NULL, 1, CAST('2024-05-27T15:06:32.140' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (368, '003000002', CAST('2024-05-15' AS Date), CAST('2024-05-15' AS Date), '76', '1', '29596652', 'PEN', 0, '0000', '24', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ADELANTO JOSE LUIS CONDORI', NULL, 1, CAST('2024-05-27T15:06:32.147' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (369, '003000002', CAST('2024-05-15' AS Date), CAST('2024-05-15' AS Date), '76', '1', '29596652', 'PEN', 0, '0000', '25', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ADELANTO JOSE LUIS CONDORI', NULL, 1, CAST('2024-05-27T15:06:32.150' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (370, '001000001', CAST('2024-05-15' AS Date), CAST('2024-05-15' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '7', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 100, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE CAMIONETA', NULL, 1, CAST('2024-05-27T15:06:32.153' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (371, '001000001', CAST('2024-05-16' AS Date), CAST('2024-05-16' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '9', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 700, 0, 700, NULL, NULL, NULL, NULL, 'ADELANTO RAUL QUISPE', NULL, 1, CAST('2024-05-27T15:06:32.160' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (372, '101103001', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '18', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'RONAL RIVERA', NULL, 1, CAST('2024-05-27T15:06:32.170' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (373, '101104001', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '19', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 900, 0, 900, NULL, NULL, NULL, NULL, 'COMBUSTIBLE Y REGULARIZACION DE FACT IBERIA', NULL, 1, CAST('2024-05-27T15:06:32.173' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (374, '101101004', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '20', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1200, 0, 1200, NULL, NULL, NULL, NULL, 'PAGO PRABRIZAS TRACTOR-ARMANDO', NULL, 1, CAST('2024-05-27T15:06:32.180' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (375, '003000002', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '76', '1', '44174474', 'PEN', 0, '0000', '26', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 3520, 0, 3520, NULL, NULL, NULL, NULL, 'ADELANTO YESICA PARAYRO', NULL, 1, CAST('2024-05-27T15:06:32.187' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (376, '001000001', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '11', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 500, 0, 500, NULL, NULL, NULL, NULL, 'CAJA CHICA', NULL, 1, CAST('2024-05-27T15:06:32.190' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (377, '102102002', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '02', '6', '10706743788', 'PEN', 0, 'E001', '353', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 800, 0, 800, NULL, NULL, NULL, NULL, 'PAGO RICARDO PINEDA', NULL, 1, CAST('2024-05-27T15:06:32.193' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (378, '101104001', CAST('2024-05-19' AS Date), CAST('2024-05-19' AS Date), '01', '6', '10105548813', 'PEN', 1, 'FE02', '416', NULL, NULL, NULL, 84.75, 15.25, NULL, NULL, NULL, 0, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE', NULL, 1, CAST('2024-05-27T15:06:32.197' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (379, '101101004', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '21', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 708.5, 0, 708.5, NULL, NULL, NULL, NULL, 'PAGO TACTORES-FORMULA 1', NULL, 1, CAST('2024-05-27T15:06:32.200' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (380, '102102002', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '00', '1', '30846465', 'PEN', 0, '0000', '5', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 200, 0, 200, NULL, NULL, NULL, NULL, 'RETIRO JOSE SEHUIN', NULL, 1, CAST('2024-05-27T15:06:32.207' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (381, '101104001', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '19127', NULL, NULL, NULL, 84.75, 15.25, NULL, NULL, NULL, 0, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE CAMIONETA', NULL, 1, CAST('2024-05-27T15:06:32.210' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (382, '101104001', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '19116', NULL, NULL, NULL, 33.9, 6.1, NULL, NULL, NULL, 0, 0, 40, NULL, NULL, NULL, NULL, 'COMBUSTIBLE MOTO', NULL, 1, CAST('2024-05-27T15:06:32.213' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (383, '102102002', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '01', '6', '20608499211', 'PEN', 1, 'F001', '19072', NULL, NULL, NULL, 57.2, 10.3, NULL, NULL, NULL, 0, 0, 67.5, NULL, NULL, NULL, NULL, 'CONSUMO-COMIDA', NULL, 1, CAST('2024-05-27T15:06:32.217' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (384, '102101003', CAST('2024-05-27' AS Date), CAST('2024-05-27' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '22', NULL, NULL, NULL, 320, 0, NULL, NULL, NULL, 0, 0, 320, NULL, NULL, NULL, NULL, 'LIMPIEZA OFICINA - MAYO', NULL, 1, CAST('2024-05-27T16:29:39.500' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (385, '101102005', CAST('2024-05-27' AS Date), CAST('2024-05-27' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '23', NULL, NULL, NULL, 12, 0, NULL, NULL, NULL, 0, 0, 12, NULL, NULL, NULL, NULL, 'ALMUERZO ADMINISTRADORA', NULL, 1, CAST('2024-05-27T16:37:20.183' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (387, '101103002', CAST('2024-05-25' AS Date), CAST('2024-05-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '24', NULL, NULL, NULL, 42.7, 0, NULL, NULL, NULL, 0, 0, 42.7, NULL, NULL, NULL, NULL, 'COPIA LITERAL PARTIDA', NULL, 1, CAST('2024-05-28T11:30:05.233' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (388, '102101003', CAST('2024-05-23' AS Date), CAST('2024-06-08' AS Date), '14', '6', '20100188628', 'PEN', 1, 'S001', '24480198', NULL, NULL, NULL, 55.76, 10.04, NULL, NULL, NULL, 0, 0, 65.8, NULL, NULL, NULL, NULL, 'PAGO LUZ - MAYO', NULL, 1, CAST('2024-05-28T11:41:18.557' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (389, '102101003', CAST('2024-05-25' AS Date), CAST('2024-06-03' AS Date), '01', '6', '20605977406', 'PEN', 1, 'F001', '171582', NULL, NULL, NULL, 61.35, 11.04, NULL, NULL, NULL, 0, 0, 72.39, NULL, NULL, NULL, NULL, 'PAGO INTERNET MAYO', NULL, 1, CAST('2024-05-28T12:02:16.777' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (390, '101102005', CAST('2024-05-27' AS Date), CAST('2024-05-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '25', NULL, NULL, NULL, 11, 0, NULL, NULL, NULL, 0, 0, 11, NULL, NULL, NULL, NULL, 'ALMUERZO CONTADOR', NULL, 1, CAST('2024-05-28T13:58:24.603' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (391, '102101004', CAST('2024-05-28' AS Date), CAST('2024-05-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '26', NULL, NULL, NULL, 18, 0, NULL, NULL, NULL, 0, 0, 18, NULL, NULL, NULL, NULL, 'PAQUETE HOJAS BOND', NULL, 1, CAST('2024-05-28T17:25:24.160' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (392, '101102002', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '27', NULL, NULL, NULL, 60, 0, NULL, NULL, NULL, 0, 0, 60, NULL, NULL, NULL, NULL, 'VIATICOS CONTADOR (ABELARDO)', NULL, 1, CAST('2024-05-30T08:31:25.233' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (393, '101102005', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '28', NULL, NULL, NULL, 6, 0, NULL, NULL, NULL, 0, 0, 6, NULL, NULL, NULL, NULL, 'DESAYUNO LEYDA', NULL, 1, CAST('2024-05-30T08:32:17.857' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (394, '101102005', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '29', NULL, NULL, NULL, 12, 0, NULL, NULL, NULL, 0, 0, 12, NULL, NULL, NULL, NULL, 'MENU LEYDA', NULL, 1, CAST('2024-05-30T14:48:53.473' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (395, '101102002', CAST('2024-05-30' AS Date), CAST('2024-05-31' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '30', NULL, NULL, NULL, 10, 0, NULL, NULL, NULL, 0, 0, 10, NULL, NULL, NULL, NULL, 'MOVILIDAD MARK  - APOYO CLAVE SOL FLORA MARIA APAZA QUISPE', NULL, 1, CAST('2024-05-31T17:30:13.557' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (396, '101102004', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '31', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1240, 0, 1240, NULL, NULL, NULL, NULL, 'PAGO CORREDOR ISAUL MAMANI', NULL, 1, CAST('2024-06-05T10:54:12.520' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (397, '102102002', CAST('2024-05-21' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '32', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'PAGO PRESTAMO PORTUGAL-TERRENO', NULL, 1, CAST('2024-06-05T10:58:34.417' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (398, '101104001', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '33', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 100, 0, 100, NULL, NULL, NULL, NULL, 'COMBSUTBILE IBERIA', NULL, 1, CAST('2024-06-05T11:10:29.590' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (399, '101103001', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '34', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 500, 0, 500, NULL, NULL, NULL, NULL, 'MARIA DEL PILAR-SEGURIDAD PROYECTO', NULL, 1, CAST('2024-06-05T11:11:34.820' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (400, '102102002', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '35', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 300, 0, 300, NULL, NULL, NULL, NULL, 'RETIRO', NULL, 1, CAST('2024-06-05T11:12:28.133' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (401, '003000002', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '76', '6', '20456089306', 'PEN', 0, '0000', '27', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 5280, 0, 5280, NULL, NULL, NULL, NULL, 'ADELANTO-MOLINO SAN ROMAN', NULL, 1, CAST('2024-06-05T11:15:33.587' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (402, '101102004', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '02', '6', '10731968476', 'PEN', 0, 'E001', '6', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1733, 0, 1733, NULL, NULL, NULL, NULL, 'PAGO CORREDOR RICHARD', NULL, 1, CAST('2024-06-05T11:19:40.477' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (403, '101102004', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '02', '6', '10443498473', 'PEN', 0, 'E001', '2', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1540, 0, 1540, NULL, NULL, NULL, NULL, ' PAGO CORREDOR MILTON', NULL, 1, CAST('2024-06-05T11:29:33.453' AS TIMESTAMP(3)), NULL);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (404, '003000002', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '76', '6', '10461727331', 'PEN', 0, '0000', '28', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1000, 0, 1000, NULL, NULL, NULL, NULL, 'ADELANTO-SUELDO MIRLESS', NULL, 1, CAST('2024-06-05T11:33:42.473' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (405, '101104001', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '36', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 100, 0, 100, NULL, NULL, NULL, NULL, 'COMBUSTIBLE-IBERIA', NULL, 1, CAST('2024-06-05T12:00:23.273' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (406, '102102002', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '37', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 300, 0, 300, NULL, NULL, NULL, NULL, 'RETIRO', NULL, 1, CAST('2024-06-05T12:00:54.127' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (407, '101104001', CAST('2024-05-25' AS Date), CAST('2024-05-25' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '38', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 150, 0, 150, NULL, NULL, NULL, NULL, 'COMBUSTIBLE-IBERIA', NULL, 1, CAST('2024-06-05T12:05:57.043' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (408, '101102004', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '02', '6', '10308522119', 'PEN', 0, 'E001', '2', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2600, 0, 2600, NULL, NULL, NULL, NULL, 'PAGO CORREDOR-RUFINO', NULL, 1, CAST('2024-06-05T12:08:15.797' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (409, '101102004', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '02', '6', '10736575707', 'PEN', 0, 'E001', '64', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 2800, 0, 2800, NULL, NULL, NULL, NULL, 'PAGO CORREDOR-JESUS', NULL, 1, CAST('2024-06-05T12:12:46.260' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (410, '102102002', CAST('2024-05-27' AS Date), CAST('2024-05-27' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '39', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 230, 0, 230, NULL, NULL, NULL, NULL, 'CONSUMO-COMIDA', NULL, 1, CAST('2024-06-05T12:13:24.503' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (411, '101102005', CAST('2024-06-01' AS Date), CAST('2024-06-01' AS Date), '01', '6', '10308460687', 'PEN', 1, 'E001', '172', NULL, NULL, NULL, 127.96, 23.03, NULL, NULL, NULL, 0, 0, 150.99, NULL, NULL, NULL, NULL, 'PENSION LEYDA', NULL, 1, CAST('2024-06-05T12:32:59.960' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (412, '102101003', CAST('2024-06-03' AS Date), CAST('2024-06-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '40', NULL, NULL, NULL, 14, 0, NULL, NULL, NULL, 0, 0, 14, NULL, NULL, NULL, NULL, 'PAPEL HIGIENICO - AZUCAR OFICINAS', NULL, 1, CAST('2024-06-05T12:37:59.837' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (413, '102101004', CAST('2024-06-04' AS Date), CAST('2024-06-04' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '41', NULL, NULL, NULL, 45, 0, NULL, NULL, NULL, 0, 0, 45, NULL, NULL, NULL, NULL, 'ARCHIVADORES', NULL, 1, CAST('2024-06-05T13:11:02.040' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (414, '101103002', CAST('2024-06-05' AS Date), CAST('2024-06-05' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '42', NULL, NULL, NULL, 30.9, 0, NULL, NULL, NULL, 0, 0, 30.9, NULL, NULL, NULL, NULL, 'VIGENCIA PODER - SUNARP', NULL, 1, CAST('2024-06-05T13:12:42.790' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (415, '101103001', CAST('2024-06-05' AS Date), CAST('2024-06-06' AS Date), '01', '6', '20605586377', 'PEN', 1, 'E001', '208', NULL, NULL, NULL, 259.32, 46.68, NULL, NULL, NULL, 0, 0, 306, NULL, NULL, NULL, NULL, 'ELABORACION BROCHURE EMPRESA - PAGO SEGUNDA PARTE', NULL, 1, CAST('2024-06-10T09:52:29.653' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (416, '101102002', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '75', NULL, NULL, NULL, 300, 0, NULL, NULL, NULL, 0, 0, 300, NULL, NULL, NULL, NULL, 'PASAJES MILUSKA - MAYO (25 DIAS)', NULL, 1, CAST('2024-06-10T12:26:48.570' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (417, '101102005', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '76', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'DESAYUNO LEYDA', NULL, 1, CAST('2024-06-10T14:07:21.733' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (418, '101102005', CAST('1024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '77', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'CENA LEYDA', NULL, 1, CAST('2024-06-10T14:09:05.660' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (419, '101102005', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '78', NULL, NULL, NULL, 147, 0, NULL, NULL, NULL, 0, 0, 147, NULL, NULL, NULL, NULL, 'PAGO DESAYUNO LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)', NULL, 1, CAST('2024-06-10T14:24:11.350' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (420, '101102005', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '79', NULL, NULL, NULL, 147, 0, NULL, NULL, NULL, 0, 0, 147, NULL, NULL, NULL, NULL, 'PAGO CENA LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)', NULL, 1, CAST('2024-06-10T14:26:18.010' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (421, '101102005', CAST('2024-05-31' AS Date), CAST('2024-05-31' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '80', NULL, NULL, NULL, 12, 0, NULL, NULL, NULL, 0, 0, 12, NULL, NULL, NULL, NULL, 'MENU LEYDA', NULL, 1, CAST('2024-06-10T14:30:39.230' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (422, '101102002', CAST('2024-06-10' AS Date), CAST('2024-06-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '154', NULL, NULL, NULL, 6, 0, NULL, NULL, NULL, 0, 0, 6, NULL, NULL, NULL, NULL, 'PAGO MOVILIDAD COCACHACRA - MARK DETRACCIONES', NULL, 1, CAST('2024-06-11T10:27:25.690' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (423, '101102005', CAST('2024-06-11' AS Date), CAST('2024-06-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '155', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'DESAYUNO LEYDA', NULL, 1, CAST('2024-06-11T10:28:37.460' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (424, '101102005', CAST('2024-06-11' AS Date), CAST('2024-06-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '156', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'CENA LEYDA', NULL, 1, CAST('2024-06-12T09:33:08.380' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (425, '102101003', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '157', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'BOLSAS DE BASURA X100 UND', NULL, 1, CAST('2024-06-12T09:34:22.340' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (426, '102101002', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '158', NULL, NULL, NULL, 7.1, 0, NULL, NULL, NULL, 0, 0, 7.1, NULL, NULL, NULL, NULL, 'LEJIA + LIMPIATODO', NULL, 1, CAST('2024-06-12T09:35:44.737' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (427, '101102005', CAST('2024-06-12' AS Date), CAST('2024-06-12' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '159', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'DESAYUNO LEYDA', NULL, 1, CAST('2024-06-12T09:36:58.827' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (428, '101102005', CAST('2024-06-11' AS Date), CAST('2024-06-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '160', NULL, NULL, NULL, 12, 0, NULL, NULL, NULL, 0, 0, 12, NULL, NULL, NULL, NULL, 'MENU LEYDA', NULL, 1, CAST('2024-06-12T09:37:54.367' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (429, '102101002', CAST('2024-06-12' AS Date), CAST('2024-06-12' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '161', NULL, NULL, NULL, 7.5, 0, NULL, NULL, NULL, 0, 0, 7.5, NULL, NULL, NULL, NULL, 'PAPEL HIGIENICO X6 UND', NULL, 1, CAST('2024-06-12T09:40:08.200' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (430, '101104001', CAST('2024-06-12' AS Date), CAST('2024-06-12' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '19413', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, 'COMBUSTIBLE MIRLESS - FIRMA DDJJ', NULL, 1, CAST('2024-06-12T11:54:46.913' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (431, '102101003', CAST('2024-04-03' AS Date), CAST('2024-04-23' AS Date), '14', '6', '20100211034', 'PEN', 1, '0', '71500727', NULL, NULL, NULL, 10.42, 1.88, NULL, NULL, NULL, 0, 0, 12.3, NULL, NULL, NULL, NULL, 'PAGO SERVICIO DE AGUA', NULL, 1, CAST('2024-06-13T08:37:51.543' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (432, '101102005', CAST('2024-06-12' AS Date), CAST('2024-06-12' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '162', NULL, NULL, NULL, 10, 0, NULL, NULL, NULL, 0, 0, 10, NULL, NULL, NULL, NULL, 'CENA LEYDA', NULL, 1, CAST('2024-06-13T08:38:32.603' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (433, '101102005', CAST('2024-06-13' AS Date), CAST('2024-06-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '163', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'DESAYUNO LEYDA', NULL, 1, CAST('2024-06-13T08:39:44.703' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (434, '102102001', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '164', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.3, 0, 0.3, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T15:06:45.423' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (435, '102102001', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '165', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 37.8, 0, 37.8, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T15:11:32.533' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (436, '102102001', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '166', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 24, 0, 24, NULL, NULL, NULL, NULL, 'COMISION EXCESO DE MOVIMIENTOS', NULL, 1, CAST('2024-06-13T15:13:18.457' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (437, '102102001', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '167', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 43.9, 0, 43.9, NULL, NULL, NULL, NULL, 'COMISION DE TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T15:13:53.950' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (438, '102102001', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '168', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.65, 0, 0.65, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T15:14:27.590' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (439, '102102001', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '169', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 35.38, 0, 35.38, NULL, NULL, NULL, NULL, 'COMISION DE TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T15:16:20.193' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (440, '102102001', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '170', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.2, 0, 0.2, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T15:16:54.437' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (441, '102102001', CAST('2024-05-04' AS Date), CAST('2024-05-04' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '171', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 29, 0, 29, NULL, NULL, NULL, NULL, 'COMISION DE TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T15:22:46.380' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (442, '102102001', CAST('2024-05-04' AS Date), CAST('2024-05-04' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '172', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.4, 0, 0.4, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T15:23:17.953' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (443, '102102001', CAST('2024-05-05' AS Date), CAST('2024-05-05' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '173', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 38.8, 0, 38.8, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T15:31:21.390' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (444, '102102001', CAST('2024-05-05' AS Date), CAST('2024-05-05' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '174', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.15, 0, 0.15, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T15:31:52.270' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (445, '102102001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '175', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 28.05, 0, 28.05, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T15:41:02.527' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (446, '102102001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '176', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1.2, 0, 1.2, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T15:41:31.333' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (447, '102102001', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '178', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 59.77, 0, 59.77, NULL, NULL, NULL, NULL, 'COMISION DE TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T16:30:38.533' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (448, '102102001', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '179', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 12, 0, 12, NULL, NULL, NULL, NULL, 'NOTA DE ABONO', NULL, 1, CAST('2024-06-13T16:31:21.660' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (449, '102102001', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '180', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T16:31:48.220' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (450, '102102002', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '181', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 200, 0, 200, NULL, NULL, NULL, NULL, 'RETIRO SR JOSE', NULL, 1, CAST('2024-06-13T16:32:20.183' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (451, '102102002', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '182', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 650, 0, 650, NULL, NULL, NULL, NULL, 'VARIOS', NULL, 1, CAST('2024-06-13T16:40:27.953' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (452, '102102002', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '183', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 55.88, 0, 55.88, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-13T16:41:01.540' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (453, '102102002', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '184', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 100, 0, 100, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-13T16:41:31.213' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (454, '102102002', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '185', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 300, 0, 300, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-13T16:42:14.363' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (455, '102102001', CAST('2024-05-08' AS Date), CAST('2025-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '186', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 51.7, 0, 51.7, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-13T16:42:54.163' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (456, '102102001', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '187', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.9, 0, 0.9, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-13T16:43:26.030' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (457, '102102002', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '188', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 97.82, 0, 97.82, NULL, NULL, NULL, NULL, 'PASAJE AVION LATAN', NULL, 1, CAST('2024-06-14T08:34:52.980' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (458, '102102001', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '189', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 22.1, 0, 22.1, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-14T08:35:23.523' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (459, '102102001', CAST('2024-05-09' AS Date), CAST('2024-05-09' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '190', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.4, 0, 0.4, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-14T08:35:52.057' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (460, '102102002', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '191', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 400, 0, 400, NULL, NULL, NULL, NULL, 'VARIOS', NULL, 1, CAST('2024-06-14T08:37:07.303' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (461, '102102001', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '192', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 74.95, 0, 74.95, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIA', NULL, 1, CAST('2024-06-14T08:37:41.953' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (462, '102102001', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '193', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.6, 0, 0.6, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-14T08:38:04.053' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (463, '102102002', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '195', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1500, 0, 1500, NULL, NULL, NULL, NULL, 'JAVIER SEHUIN', NULL, 1, CAST('2024-06-14T08:43:16.337' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (465, '102102001', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '197', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 22.1, 0, 22.1, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-14T08:44:18.847' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (466, '102102001', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '198', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.2, 0, 0.2, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-14T08:44:40.500' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (467, '102102001', CAST('2024-05-12' AS Date), CAST('2024-05-12' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '199', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 4.3, 0, 4.3, NULL, NULL, NULL, NULL, 'OMISION TRANSFERENCIA', NULL, 1, CAST('2024-06-14T09:24:52.793' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (468, '102102002', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '200', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 215, 0, 215, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-14T09:29:50.990' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (469, '102102001', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '201', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 315, 0, 315, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-14T09:30:26.300' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (470, '102102002', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '202', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 555, 0, 555, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-14T09:30:58.890' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (471, '102102001', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '203', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 29.6, 0, 29.6, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-14T09:31:43.733' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (472, '102102001', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '204', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0.05, 0, 0.05, NULL, NULL, NULL, NULL, 'ITF', NULL, 1, CAST('2024-06-14T09:32:19.327' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (473, '102102002', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '205', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 165, 0, 165, NULL, NULL, NULL, NULL, 'CONSUMO', NULL, 1, CAST('2024-06-14T09:45:42.800' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (474, '101102005', CAST('2024-06-14' AS Date), CAST('2024-06-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '206', NULL, NULL, NULL, 8, 0, NULL, NULL, NULL, 0, 0, 8, NULL, NULL, NULL, NULL, 'CENA LEYDA', NULL, 1, CAST('2024-06-14T09:47:02.610' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Compras.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, observaciones, numeroMod, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (475, '102102001', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '207', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 14.8, 0, 14.8, NULL, NULL, NULL, NULL, 'COMISION TRANSFERENCIAS', NULL, 1, CAST('2024-06-14T09:48:50.227' AS TIMESTAMP(3)), NULL);
/* SET IDENTITY_INSERT [Compras].[documentos] OFF */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.datos (id, dato) VALUES (1, 'oxzdu4ZBlghIaetvqYux8CocEVJABQAkptMBcpUyQVhXr5sF3vb0ABZxJF40');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('04648307', 'RIVERA CALDERON RONALD ELVIN', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10000001', 'ENTIDADES VARIAS', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10025247154', 'CACERES RIVERA MARCELINO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10046291757', 'LUCAY CARRANZA MARIA ELENA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10105548813', 'HERRERA SALAZAR JOSE DOMINGO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10239385724', 'MENDOZA SANDOVAL VICENTINA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10292643352', 'CCASO MAMANI GREGORIO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10292735117', 'MOSCOSO SALAS MARCIAL RUPERTO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10292805557', 'PEREZ BERMEJO ZONIA ISAURA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10293034708', 'PALOMINO CARDENAS ARTURO SILVIO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10293818911', 'TORRES CARPIO EDWIN ISAAC', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10293877012', 'BERMEJO VARGAS MARIO ALFREDO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10294578647', 'ANCO ESCOBAR PASTOR HUMBERTO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10295594948', 'HUAMANI LUPINTA ROLANDO JUSTO', 'BAJA DEFINITIVA', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10295782248', 'SALAS BRICEÑO MARTHA PILAR', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', '-', '-', '-', '-', 'BCP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10296048629', 'TAPARA MAYHUA JULIA NANCI', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10296101902', 'CHOQUE CAPARO ROBERTO PERCY', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10296459033', 'MIRANDA NUÑEZ KATTY SONIA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10297148481', 'MAMANI CAIRA MANUEL', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10306740593', 'ARONI CASTILLO CECILIA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10306750742', 'BELLIDO MAQUERA PERCY ANTONIO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10308460687', 'CALDERON CCORA DORA ALICIA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10308522119', 'GONZALES HUAMAN RUFINO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10410841148', 'QUISPE RUIZ JUAN CESAR', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10411204265', 'VARGAS CCAMA SONIA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10416802403', 'PEREZ PACORI RIGAN ELOY', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10420725570', 'HUMORA VILCA HUGO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10440673207', 'CURALLA CCONISLLA LUZ KARINA', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10443498473', 'QUISPE GOMEL MILTON RONAL', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10461727331', 'SEHUIN MAMANI MIRLESS YULIET', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10476253221', 'AYALA HUAYTAPUMA FRANCISCO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10477161079', 'LARICO YUPANQUE YESSICA MAYUMI', 'SUSPENSION TEMPORAL', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10706743788', 'PINEDA GUZMAN RICARDO NOE', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10714580359', 'VALENZUELA SALAS ABELARDO FRANCO', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10731968476', 'QUIROZ VIZCARRA RICHARD JESUS', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10736575707', 'MONGE CAHUAS JESUS KEVIN', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('10753776031', 'QUISPE OVIEDO DAYANA MERCEDES', 'ACTIVO', 'HABIDO', '-', '-', '-', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100043140', 'SCOTIABANK PERU SAA', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN ISIDRO', 'AV. CANAVAL Y MOREYRA NRO. 522 - LIMA LIMA SAN ISIDRO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100047218', 'BANCO DE CREDITO DEL PERU', 'ACTIVO', 'HABIDO', 'LIMA', 'LA MOLINA', 'CAL. CENTENARIO NRO. 156 URB. LAS LADERAS DE MELGAREJO - LIMA LIMA LA MOLINA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100075858', 'GRIFO SAN IGNACIO S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'ATE', 'AV. SANTA CECILIA NRO. 575 URB. LOS SAUCES - LIMA LIMA ATE', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100128056', 'SAGA FALABELLA S A', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN ISIDRO', 'AV. PASEO DE LA REPUBLICA NRO. 3220 URB. JARDIN - LIMA LIMA SAN ISIDRO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100130204', 'BANCO BBVA PERU', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN ISIDRO', 'AV. REP DE PANAMA NRO. 3055 URB. EL PALOMAR - LIMA LIMA SAN ISIDRO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100147514', 'SOUTHERN PERU COPPER CORPORATION, SUCURSAL DEL PERÚ', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTIAGO DE SURCO', 'AV. CAMINOS DEL INCA NRO. 171 URB. CHACARILLA DEL ESTANQUE - LIMA LIMA SANTIAGO DE SURCO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100188628', 'SOCIEDAD ELECTRICA DEL SUR OESTE S A', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. CONSUELO NRO. 310 - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100202396', 'AUTRISA AUTOMOTRIZ ANDINA S.A.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. PARRA NRO. 122 URB. VALLECITO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100205573', 'EL POLLO REAL S.A.C', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. PIEROLA NRO. 111 INT. A ---- CERCADO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100211034', 'SEDAPAR S.A.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. VIRGEN DEL PILAR NRO. 1701 - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20100366747', 'LLAMA GAS S A', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTIAGO DE SURCO', 'AV. EL POLO NRO. 397 - LIMA LIMA SANTIAGO DE SURCO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20106897914', 'ENTEL PERU S.A.', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN ISIDRO', 'AV. REPUBLICA DE COLOMBIA NRO. 791 - LIMA LIMA SAN ISIDRO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20109072177', 'CENCOSUD RETAIL PERU S.A.', 'ACTIVO', 'HABIDO', 'LIMA', 'MIRAFLORES', 'CAL. AUGUSTO ANGULO NRO. 130 URB. SAN ANTONIO - LIMA LIMA MIRAFLORES', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20124762996', 'SERV.PROFESIONALES DE IND.YCOMERCIO SRL', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. VENEZUELA NRO. S/N INT. 29 ---- C.C. LA NEGRITA - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20127765279', 'COESTI S.A.', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTIAGO DE SURCO', 'AV. CIRCUNVALACION DEL CLUB G NRO. 134 URB. CLUB EL GOLF LOS INCAS - LIMA LIMA SANTIAGO DE SURCO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20170072465', 'SOCIEDAD MINERA CERRO VERDE S.A.A.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. JACINTO IBAÑEZ NRO. 315 URB. PARQUE INDUSTRIAL - AREQUIPA AREQUIPA AREQUIPA', '6', '-', '-', '-', '-', '-');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20174349348', 'EMPRESA DE TRANSPORTES VALDIVIA S.C.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'SOCABAYA', 'CAL. CHIMBOTE NRO. 302 URB. SAN MARTIN DE SOCABAYA - AREQUIPA AREQUIPA SOCABAYA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20252947265', 'FERRETERIA SAUL PAREDES E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. JESUS NRO. 112 - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20327528611', 'EMPRESA DE TURISMO RAYAL TOUR E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'PAUCARPATA', 'AV. PIZARRO NRO. 120 - AREQUIPA AREQUIPA PAUCARPATA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20330262428', 'COMPAÑIA MINERA ANTAMINA S.A.', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTIAGO DE SURCO', 'AV. EL DERBY NRO. 055 DPTO. 801 - LIMA LIMA SANTIAGO DE SURCO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20389230724', 'SODIMAC PERU S.A.', 'BAJA DEFINITIVA', 'HABIDO', 'LIMA', 'SURQUILLO', 'AV. ANGAMOS ESTE NRO. 1805 INT. 2 - LIMA LIMA SURQUILLO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20406522998', 'GRIFO CRUZ AYABACAS EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 'ACTIVO', 'HABIDO', 'SAN ROMÁN', 'JULIACA', '---- PARC. POJORONI CARRET. HU NRO. SN - PUNO SAN ROMÁN JULIACA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20434874778', 'EMPRESA DE TRANSPORTES Y SERVICIOS CRAMER S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CERRO COLORADO', 'JR. INDEPENDENCIA NRO. 103 P.J. ALTO LIBERTAD - AREQUIPA AREQUIPA CERRO COLORADO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20434898103', 'INDUSTRIA PANIFICADORA AMERICANA EIRL', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. SAN JUAN DE DIOS NRO. 323 - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20453930549', 'EMPRESA DE TRANSPORTES TURISTICOS PACHAMARCA TOURS S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'JR. PAZ SOLDAN LT. 4 MZ. F2 P.J. CASIMIRO CUADROS II - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20453981291', 'EMPRESA DE TRANSPORTES SIDERAL TOURS S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'LT. 8 MZ. M-2 C.H. DEAN VALDIVIA - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20454041888', 'JHOSIMAR E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'LT. 10 MZ. M2 C.H. DEAN VALDIVIA (ENACE) - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20454083963', 'LA SOLUCION DISTRIBUCIONES E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. SAN JUAN DE DIOS NRO. 402 INT. 101 - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20454118180', 'FERROSISTEMAS S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. QUIROZ NRO. 125C URB. MARIA ISABEL - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20454475888', 'LEDDTOURS E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CERRO COLORADO', 'CAL. 28 DE JULIO NRO. 314A P.J. MARISCAL CASTILLA - AREQUIPA AREQUIPA CERRO COLORADO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20454519923', 'TRANSPORTES F/Y TOUR S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'ALTO SELVA ALEGRE', 'LT. 10 MZ. I A.H. JAVIER HURAUD - AREQUIPA AREQUIPA ALTO SELVA ALEGRE', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20454548354', 'TURISMO INTERNACIONAL AMERICANOS EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. 15 DE AGOSTO NRO. 221 URB. IV CENTENARIO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455008884', 'COMERCIAL PIRAMIDE S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. QUIROZ NRO. 125 URB. MARIA ISABEL - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455041661', 'TRANSPORTES TURISTICOS RUTAS ANDINAS E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'CAL. VICENTE ANGULO NRO. 360 ---- PT. LA TOMILLA - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455084980', 'TRANSPORTES TURISTICOS Y SERVICIOS JOSSEF PERU TOURS S.R.L. - TRANSERJOSSEFPERU S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'UCHUMAYO', 'JR. BRASIL LT. 2 MZ. A URB. EL CARMEN II - AREQUIPA AREQUIPA UCHUMAYO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455363943', 'SERVICIOS TURISTICOS NOAL E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. JERUSALEN NRO. 304 URB. CERCADO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455885133', 'PANIFICADORA LA BAGUETTA S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'YANAHUARA', 'LT. 9 MZ. B URB. VALENCIA - AREQUIPA AREQUIPA YANAHUARA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455958114', 'FRANZTUR E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'SOCABAYA', 'AV. SOCABAYA NRO. 800 URB. SAN MARTIN DE SOCABAYA - AREQUIPA AREQUIPA SOCABAYA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20455973938', 'MILAGROS TOURS E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'JOSÉ LUIS BUSTAMANTE Y RIVERO', 'LT. 21 MZ. A URB. SANTA MARIA DE LAMBRAMANI - AREQUIPA AREQUIPA JOSÉ LUIS BUSTAMANTE Y RIVERO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20456010131', 'LEITO TOUR S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'JOSÉ LUIS BUSTAMANTE Y RIVERO', 'CAL. INTERNACIONAL NRO. 224 COO. 58 MANUEL PRADO - AREQUIPA AREQUIPA JOSÉ LUIS BUSTAMANTE Y RIVERO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20456089306', 'MOLINO SAN ROMAN S.A.C.', 'ACTIVO', 'HABIDO', 'ISLAY', 'PUNTA DE BOMBÓN', 'CAL. CRUCERO NRO. S/N - AREQUIPA ISLAY PUNTA DE BOMBÓN', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20456118179', 'MAYMANTA S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'JACOBO HUNTER', 'AV. PAISAJISTA NRO. S/N ---- P.T.HUASACACHE - AREQUIPA AREQUIPA JACOBO HUNTER', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20456163123', 'TRANSPORTES TURISTICOS N & N S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'JACOBO HUNTER', 'CAL. PROLONAGION LOS ANGELES - LT. 19-A MZ. P URB. CHILPINILLA - AREQUIPA AREQUIPA JACOBO HUNTER', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20492092313', 'MAKRO SUPERMAYORISTA S.A.', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTIAGO DE SURCO', 'AV. JORGE CHAVEZ NRO. 1218 - LIMA LIMA SANTIAGO DE SURCO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20497821011', 'AUTOSERVICIO DCARMEN S.R.L', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'PJ. EL PALOMAR NRO. S/N - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20498245481', 'CAPRICCIO EXPRESS S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'AV. EL EJERCITO NRO. 793 INT. I05 URB. LOS SAUCES - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20498359061', 'COMARICO SAC', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'YANAHUARA', 'AV. EMMEL NRO. 202 URB. ANTONIO RAIMONDI - AREQUIPA AREQUIPA YANAHUARA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20498523634', 'ACUARIU´S TRAVEL TOUR OPERATOR EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA - ACUTRAVEL E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. SANTA CATALINA NRO. 104 - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20505675836', 'FLSMIDTH S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN ISIDRO', 'AV. JUAN DE ARONA NRO. 755 - LIMA LIMA SAN ISIDRO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20506421781', 'CORPORACIÓN RICO S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'VILLA EL SALVADOR', 'CAR. PANAMERICANA SUR LT. 12 MZ. B Z.I. ASOC. AGRICOLA CONCORDIA - LIMA LIMA VILLA EL SALVADOR', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20508565934', 'HIPERMERCADOS TOTTUS S.A', 'ACTIVO', 'HABIDO', 'LIMA', 'SURQUILLO', 'AV. ANGAMOS ESTE NRO. 1805 INT. P10 - LIMA LIMA SURQUILLO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20519692946', 'L & L REPRES Y SERVICIOS SCRL', 'ACTIVO', 'HABIDO', 'ILO', 'ILO', 'LT. 11 MZ. T URB. CIUDAD JARDIN - MOQUEGUA ILO ILO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20536557858', 'HOMECENTERS PERUANOS S.A.', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN BORJA', 'AV. AVIACION NRO. 2405 - LIMA LIMA SAN BORJA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20539457161', 'TRAVELS TOURS ANA BELEN E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'JOSÉ LUIS BUSTAMANTE Y RIVERO', 'LT. 5 MZ. I COO. LAMBRAMANI - AREQUIPA AREQUIPA JOSÉ LUIS BUSTAMANTE Y RIVERO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20539654153', 'ESTACIÓN DE SERVICIOS IBERIA S.A.C.', 'ACTIVO', 'HABIDO', 'ISLAY', 'DEAN VALDIVIA', 'AV. AREQUIPA NRO. S/N - AREQUIPA ISLAY DEAN VALDIVIA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20539682289', 'INVERSIONES Y SERVICIOS GENERALES CANTARES MC E.I.R.L.', 'ACTIVO', 'HABIDO', 'ISLAY', 'DEAN VALDIVIA', '---- AMAZONAS NRO. 109 - AREQUIPA ISLAY DEAN VALDIVIA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20559188051', 'EMPRESA DE TRANSPORTES Y SERVICIOS GENERALES W & C S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CERRO COLORADO', 'LT. 3 MZ. P A.V. ANDRES AVELINO CACERES - AREQUIPA AREQUIPA CERRO COLORADO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20559270068', 'COMBUSTIBLES Y SERVICIOS D&A S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CERRO COLORADO', 'AV. PERU NRO. 217 P.J. ALTO LIBERTAD - AREQUIPA AREQUIPA CERRO COLORADO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20559274641', 'TRANSPORTES, TURISMO Y SERVICIOS ALI E.I.R.L. - TRANSERV ALI E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'JACOBO HUNTER', 'CAL. ALBERTO GUILLEN LT. 4 MZ. V URB. ALTO LA ALIANZA - AREQUIPA AREQUIPA JACOBO HUNTER', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20600867475', 'TRANSERJOP E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CERRO COLORADO', 'CAL. BRASIL LT. 5 MZ. K Z.I. APTASA - AREQUIPA AREQUIPA CERRO COLORADO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20600897048', 'SOLUCION INTEGRAL EN TECNOLOGIA INFORMATICA S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. OCTAVIO MUÑOZ NAJAR NRO. 221B URB. CERCADO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20601206928', 'AVICRUZ SOCIEDAD ANONIMA CERRADA', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'MARIANO MELGAR', 'PJ. PASEO AREQUIPA NRO. 102 - AREQUIPA AREQUIPA MARIANO MELGAR', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20601758572', 'CORPORACION TURISTICA PERU BABY LAMA SOCIEDAD ANONIMA CERRADA-PERU BABY LAMA S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'LT. 9 MZ. U P.J. VIRGEN DE CHAPI - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20602205411', 'COLCA CANYON ADVENTURE S.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'MARIANO MELGAR', 'CAL. AMERICA NRO. 808 URB. CERCADO DE MARIANO MELGAR - AREQUIPA AREQUIPA MARIANO MELGAR', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20602250262', 'DISTRIBUIDORA NAGAVE E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. SAN CAMILO NRO. 200 URB. CERCADO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20602987940', 'INFORMATICA D´VALD S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. OCTAVIO MUÑOZ NAJAR NRO. 221 INT. 110 URB. CERCADO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20603374623', 'FER COLORS E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. QUIROZ NRO. 114 URB. MARIA ISABEL - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20603436955', 'D´ ROSE CONCESIONES E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'PJ. CUSCO NRO. 203 URB. VALLECITO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20603742843', 'MIA COLORS E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'AV. AV. JESUS NRO. 100 INT. 6-7 OTR. ASPROMAC C.C. DON MANUEL - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20605117318', 'EMPRESA DE TRANSPORTES COPITA TOURS E.I.R.L. - COPITA TOURS E.I.R.L.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CAYMA', 'LT. 8 MZ. G ASC. SAN MARTIN DE PORRES - AREQUIPA AREQUIPA CAYMA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20605586377', 'AGENCIA DE DISEÑO CARAMBOLA S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'YANAHUARA', 'LT. 4 MZ. B COO. VIRGEN DEL CARMEN - AREQUIPA AREQUIPA YANAHUARA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20605927387', 'CINERIS´ S S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. PIZARRO NRO. 529 URB. CERCADO - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20605977406', 'WOW TEL S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTIAGO DE SURCO', 'AV. EL POLO NRO. 401 INT. P10 - LIMA LIMA SANTIAGO DE SURCO', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20606052481', 'AMMYCARS LOGISTICA S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'SACHACA', 'AV. PROGRESO NRO. S/N DPTO. 306 ---- CONJUNTO RESIDENCIAL HUAR - AREQUIPA AREQUIPA SACHACA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20606342668', 'FRUTOS & ESPECIAS DEL MUNDO S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'SANTA ANITA', 'PJ. SAN FRANCISCO DE ASIS NRO. 54 ---- --- - LIMA LIMA SANTA ANITA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20606566558', 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C', 'ACTIVO', 'HABIDO', 'ISLAY', 'DEAN VALDIVIA', 'JR. TARAPACA NRO. S/N ANX. EL BOQUERON - AREQUIPA ISLAY DEAN VALDIVIA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20607501573', 'KALLPA CONSTRUCTORA & BIENES RAICES S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'ALTO SELVA ALEGRE', 'CAL. UPIS SAN JOSE LT. 7 MZ. C URB. RAMIRO PRIALE - AREQUIPA AREQUIPA ALTO SELVA ALEGRE', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20607891762', 'IMPORTACIONES DON LOLO PARILLO SUPO EIRL', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'MARIANO MELGAR', 'AV. JESUS NRO. 305 - AREQUIPA AREQUIPA MARIANO MELGAR', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20608300393', 'COMPAÑIA FOOD RETAIL S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'SAN BORJA', 'CAL. CESAR MORELLI NRO. 181 URB. SAN BORJA NORTE - LIMA LIMA SAN BORJA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20608430301', 'BOTICAS IP S.A.C.', 'ACTIVO', 'HABIDO', 'LIMA', 'CHORRILLOS', 'AV. DEFENSORES DEL MORRO NRO. 1277 - LIMA LIMA CHORRILLOS', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20608499211', 'ALVAS INVERSIONES TAMBO DE ORO RESTAURANT S.R.L.', 'ACTIVO', 'HABIDO', 'ISLAY', 'COCACHACRA', 'NRO. S/N ---- MZ. PTE. SANTA ROSA FISCA - AREQUIPA ISLAY COCACHACRA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20608585649', 'DAVICAM JUNIORS SOCIEDAD ANONIMA CERRADA', 'ACTIVO', 'HABIDO', 'ISLAY', 'PUNTA DE BOMBÓN', 'PRO. MICAELA BASTIDAS NRO. S/N SEC. LA CANOA - AREQUIPA ISLAY PUNTA DE BOMBÓN', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20608782398', 'CORPORACION EDEKA S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'SOCABAYA', 'AV. PAISAJISTA BELLAPAMPA NRO. 306 - AREQUIPA AREQUIPA SOCABAYA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20608889524', 'GRIFO LEON DEL SUR S.R.L', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'CERRO COLORADO', 'KL. 4 ---- VARIANTE DE UCHUMAYO - AREQUIPA AREQUIPA CERRO COLORADO
', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('20610006699', 'HUMBSER COMPANY S.A.C.', 'ACTIVO', 'HABIDO', 'AREQUIPA', 'AREQUIPA', 'CAL. PAUL RIVET NRO. 207 URB. LOS PINOS - AREQUIPA AREQUIPA AREQUIPA', '6', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('29296086', 'UGARTE VELA ROSA JULIANA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('29310512', 'TORRES QUIROZ MARCO ANTONIO', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('29596652', 'CONDORI ESCOBEDO JOSE LUIS', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('30425342', 'MOLINA DIAZ EDGAR ELOY ALBINO', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('30842503', 'APAZA QUISPE FLORA MARIA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('30844237', 'AMPUERO PACHECO MARIA ANTONIETA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('30846465', 'SEHUIN HUMPIRE JOSE EDGARD', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('30847515', 'VELASQUEZ GALLEGOS JULIO CESAR', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('40521566', 'SEHUIN HUMPIRE JAVIER ERNESTO', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('42333707', 'QUISPE CHACO HUGO', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('44174474', 'PANAIFO RUIZ YESICA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('46694933', 'MACHACCA CATARI JUAN ARMANDO', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('46878984', 'PEREZ ANCO ERIKA PATRICIA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('48227891', 'MONTES ARIAS MELISSA VALERIA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('71458035', 'VALENZUELA SALAS ABELARDO FRANCO', '-', '-', '-', '-', '-', '1', '-', '-', '-', '-', '-');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('72656076', 'MAMANI HANAMPA ALDAIR ANDRE', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('72672960', 'VARGAS MAMANI DIANA MILAGROS', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('72843408', 'ABARCA CORRALES SOLANCH DALESKA', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.entidades (id, descripcion, estado_contribuyente, estado_domiclio, provincia, distrito, direccion, idt02doc, cta1, cta2, cta3, telefono, banco) VALUES ('80267101', 'HOLGUIN VILCAHUAMAN ORIEL RAMON', '-', '-', '-', '-', '-', '1', NULL, NULL, NULL, NULL, NULL);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.estados (id, descripcion) VALUES (0, 'PENDIENTE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.estados (id, descripcion) VALUES (1, 'APROBADO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.estados (id, descripcion) VALUES (2, 'RECHAZADO');
 
/* SET IDENTITY_INSERT [General].[formasdepago] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (1, 'CONTADO', 0);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (2, 'CREDITO 5 DIAS', 5);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (3, 'CREDITO 10 DIAS', 10);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (4, 'CREDITO 15 DIAS', 15);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (5, 'CREDITO 20 DIAS', 20);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (6, 'CREDITO 25 DIAS', 25);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (7, 'CREDITO 30 DIAS', 30);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (8, 'CREDITO 35 DIAS', 35);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (9, 'CREDITO 40 DIAS', 40);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (10, 'CREDITO 45 DIAS', 45);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (11, 'CREDITO 50 DIAS', 50);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (12, 'CREDITO 55 DIAS', 55);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (13, 'CREDITO 60 DIAS', 60);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (14, 'CREDITO 65 DIAS', 65);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (15, 'CREDITO 70 DIAS', 70);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (16, 'CREDITO 75 DIAS', 75);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (17, 'CREDITO 80 DIAS', 80);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (18, 'CREDITO 85 DIAS', 85);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.formasdepago (id, descripcion, dias) VALUES (19, 'CREDITO 90 DIAS', 90);
/* SET IDENTITY_INSERT [General].[formasdepago] OFF */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (1, 'ENERO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (2, 'FEBRERO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (3, 'MARZO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (4, 'ABRIL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (5, 'MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (6, 'JUNIO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (7, 'JULIO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (8, 'AGOSTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (9, 'SETIEMBRE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (10, 'OCTUBRE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (11, 'NOVIEMBRE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.meses (id, descripcion) VALUES (12, 'DICIEMBRE');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.sino (id, descripcion) VALUES (1, 'Si');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.sino (id, descripcion) VALUES (2, 'No');
 
/* SET IDENTITY_INSERT [General].[tipcamsunat] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1, CAST('2023-01-01' AS Date), 3.808, 3.82);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (2, CAST('2023-01-02' AS Date), 3.808, 3.82);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (3, CAST('2023-01-03' AS Date), 3.808, 3.82);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (4, CAST('2023-01-04' AS Date), 3.812, 3.823);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (5, CAST('2023-01-05' AS Date), 3.822, 3.827);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (6, CAST('2023-01-06' AS Date), 3.824, 3.83);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (7, CAST('2023-01-07' AS Date), 3.8, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (8, CAST('2023-01-08' AS Date), 3.8, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (9, CAST('2023-01-09' AS Date), 3.8, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (10, CAST('2023-01-10' AS Date), 3.792, 3.803);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (11, CAST('2023-01-11' AS Date), 3.802, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (12, CAST('2023-01-12' AS Date), 3.782, 3.783);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (13, CAST('2023-01-13' AS Date), 3.779, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (14, CAST('2023-01-14' AS Date), 3.802, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (15, CAST('2023-01-15' AS Date), 3.802, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (16, CAST('2023-01-16' AS Date), 3.802, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (17, CAST('2023-01-17' AS Date), 3.815, 3.822);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (18, CAST('2023-01-18' AS Date), 3.837, 3.842);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (19, CAST('2023-01-19' AS Date), 3.829, 3.836);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (20, CAST('2023-01-20' AS Date), 3.857, 3.861);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (21, CAST('2023-01-21' AS Date), 3.852, 3.859);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (22, CAST('2023-01-22' AS Date), 3.852, 3.859);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (23, CAST('2023-01-23' AS Date), 3.852, 3.859);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (24, CAST('2023-01-24' AS Date), 3.866, 3.873);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (25, CAST('2023-01-25' AS Date), 3.88, 3.885);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (26, CAST('2023-01-26' AS Date), 3.892, 3.9);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (27, CAST('2023-01-27' AS Date), 3.861, 3.867);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (28, CAST('2023-01-28' AS Date), 3.824, 3.829);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (29, CAST('2023-01-29' AS Date), 3.824, 3.829);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (30, CAST('2023-01-30' AS Date), 3.824, 3.829);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (31, CAST('2023-01-31' AS Date), 3.853, 3.859);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (32, CAST('2023-02-01' AS Date), 3.844, 3.851);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (33, CAST('2023-02-02' AS Date), 3.843, 3.848);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (34, CAST('2023-02-03' AS Date), 3.83, 3.835);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (35, CAST('2023-02-04' AS Date), 3.843, 3.847);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (36, CAST('2023-02-05' AS Date), 3.843, 3.847);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (37, CAST('2023-02-06' AS Date), 3.843, 3.847);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (38, CAST('2023-02-07' AS Date), 3.849, 3.855);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (39, CAST('2023-02-08' AS Date), 3.843, 3.848);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (40, CAST('2023-02-09' AS Date), 3.852, 3.856);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (41, CAST('2023-02-10' AS Date), 3.863, 3.869);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (42, CAST('2023-02-11' AS Date), 3.853, 3.858);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (43, CAST('2023-02-12' AS Date), 3.853, 3.858);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (44, CAST('2023-02-13' AS Date), 3.853, 3.858);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (45, CAST('2023-02-14' AS Date), 3.849, 3.854);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (46, CAST('2023-02-15' AS Date), 3.858, 3.861);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (47, CAST('2023-02-16' AS Date), 3.858, 3.865);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (48, CAST('2023-02-17' AS Date), 3.862, 3.868);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (49, CAST('2023-02-18' AS Date), 3.839, 3.849);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (50, CAST('2023-02-19' AS Date), 3.839, 3.849);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (51, CAST('2023-02-20' AS Date), 3.839, 3.849);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (52, CAST('2023-02-21' AS Date), 3.84, 3.85);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (53, CAST('2023-02-22' AS Date), 3.831, 3.836);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (54, CAST('2023-02-23' AS Date), 3.821, 3.827);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (55, CAST('2023-02-24' AS Date), 3.8, 3.805);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (56, CAST('2023-02-25' AS Date), 3.81, 3.82);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (57, CAST('2023-02-26' AS Date), 3.81, 3.82);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (58, CAST('2023-02-27' AS Date), 3.81, 3.82);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (59, CAST('2023-02-28' AS Date), 3.811, 3.816);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (60, CAST('2023-03-01' AS Date), 3.803, 3.81);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (61, CAST('2023-03-02' AS Date), 3.779, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (62, CAST('2023-03-03' AS Date), 3.779, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (63, CAST('2023-03-04' AS Date), 3.782, 3.789);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (64, CAST('2023-03-05' AS Date), 3.782, 3.789);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (65, CAST('2023-03-06' AS Date), 3.782, 3.789);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (66, CAST('2023-03-07' AS Date), 3.784, 3.788);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (67, CAST('2023-03-08' AS Date), 3.784, 3.789);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (68, CAST('2023-03-09' AS Date), 3.784, 3.788);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (69, CAST('2023-03-10' AS Date), 3.788, 3.792);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (70, CAST('2023-03-11' AS Date), 3.781, 3.786);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (71, CAST('2023-03-12' AS Date), 3.781, 3.786);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (72, CAST('2023-03-13' AS Date), 3.781, 3.786);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (73, CAST('2023-03-14' AS Date), 3.798, 3.803);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (74, CAST('2023-03-15' AS Date), 3.785, 3.789);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (75, CAST('2023-03-16' AS Date), 3.804, 3.808);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (76, CAST('2023-03-17' AS Date), 3.796, 3.801);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (77, CAST('2023-03-18' AS Date), 3.792, 3.798);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (78, CAST('2023-03-19' AS Date), 3.792, 3.798);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (79, CAST('2023-03-20' AS Date), 3.792, 3.798);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (80, CAST('2023-03-21' AS Date), 3.779, 3.785);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (81, CAST('2023-03-22' AS Date), 3.765, 3.77);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (82, CAST('2023-03-23' AS Date), 3.767, 3.774);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (83, CAST('2023-03-24' AS Date), 3.762, 3.767);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (84, CAST('2023-03-25' AS Date), 3.764, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (85, CAST('2023-03-26' AS Date), 3.764, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (86, CAST('2023-03-27' AS Date), 3.764, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (87, CAST('2023-03-28' AS Date), 3.765, 3.772);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (88, CAST('2023-03-29' AS Date), 3.763, 3.768);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (89, CAST('2023-03-30' AS Date), 3.755, 3.759);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (90, CAST('2023-03-31' AS Date), 3.755, 3.761);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (91, CAST('2023-04-01' AS Date), 3.758, 3.765);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (92, CAST('2023-04-02' AS Date), 3.758, 3.765);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (93, CAST('2023-04-03' AS Date), 3.758, 3.765);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (94, CAST('2023-04-04' AS Date), 3.764, 3.77);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (95, CAST('2023-04-05' AS Date), 3.769, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (96, CAST('2023-04-06' AS Date), 3.769, 3.774);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (97, CAST('2023-04-07' AS Date), 3.769, 3.774);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (98, CAST('2023-04-08' AS Date), 3.769, 3.774);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (99, CAST('2023-04-09' AS Date), 3.769, 3.774);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (100, CAST('2023-04-10' AS Date), 3.769, 3.774);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (101, CAST('2023-04-11' AS Date), 3.773, 3.781);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (102, CAST('2023-04-12' AS Date), 3.783, 3.787);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (103, CAST('2023-04-13' AS Date), 3.773, 3.78);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (104, CAST('2023-04-14' AS Date), 3.774, 3.777);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (105, CAST('2023-04-15' AS Date), 3.778, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (106, CAST('2023-04-16' AS Date), 3.778, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (107, CAST('2023-04-17' AS Date), 3.778, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (108, CAST('2023-04-18' AS Date), 3.779, 3.785);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (109, CAST('2023-04-19' AS Date), 3.774, 3.781);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (110, CAST('2023-04-20' AS Date), 3.774, 3.781);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (111, CAST('2023-04-21' AS Date), 3.762, 3.768);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (112, CAST('2023-04-22' AS Date), 3.762, 3.768);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (113, CAST('2023-04-23' AS Date), 3.762, 3.768);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (114, CAST('2023-04-24' AS Date), 3.762, 3.768);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (115, CAST('2023-04-25' AS Date), 3.754, 3.761);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (116, CAST('2023-04-26' AS Date), 3.752, 3.762);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (117, CAST('2023-04-27' AS Date), 3.742, 3.75);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (118, CAST('2023-04-28' AS Date), 3.731, 3.737);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (119, CAST('2023-04-29' AS Date), 3.711, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (120, CAST('2023-04-30' AS Date), 3.711, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (121, CAST('2023-05-01' AS Date), 3.711, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (122, CAST('2023-05-02' AS Date), 3.711, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (123, CAST('2023-05-03' AS Date), 3.706, 3.715);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (124, CAST('2023-05-04' AS Date), 3.704, 3.709);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (125, CAST('2023-05-05' AS Date), 3.716, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (126, CAST('2023-05-06' AS Date), 3.709, 3.717);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (127, CAST('2023-05-07' AS Date), 3.709, 3.717);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (128, CAST('2023-05-08' AS Date), 3.709, 3.717);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (129, CAST('2023-05-09' AS Date), 3.702, 3.71);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (130, CAST('2023-05-10' AS Date), 3.698, 3.702);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (131, CAST('2023-05-11' AS Date), 3.67, 3.678);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (132, CAST('2023-05-12' AS Date), 3.675, 3.683);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (133, CAST('2023-05-13' AS Date), 3.649, 3.659);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (134, CAST('2023-05-14' AS Date), 3.649, 3.659);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (135, CAST('2023-05-15' AS Date), 3.649, 3.659);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (136, CAST('2023-05-16' AS Date), 3.656, 3.667);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (137, CAST('2023-05-17' AS Date), 3.678, 3.683);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (138, CAST('2023-05-18' AS Date), 3.691, 3.697);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (139, CAST('2023-05-19' AS Date), 3.698, 3.702);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (140, CAST('2023-05-20' AS Date), 3.688, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (141, CAST('2023-05-21' AS Date), 3.688, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (142, CAST('2023-05-22' AS Date), 3.688, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (143, CAST('2023-05-23' AS Date), 3.686, 3.695);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (144, CAST('2023-05-24' AS Date), 3.689, 3.695);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (145, CAST('2023-05-25' AS Date), 3.683, 3.69);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (146, CAST('2023-05-26' AS Date), 3.692, 3.698);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (147, CAST('2023-05-27' AS Date), 3.671, 3.675);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (148, CAST('2023-05-28' AS Date), 3.671, 3.675);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (149, CAST('2023-05-29' AS Date), 3.671, 3.675);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (150, CAST('2023-05-30' AS Date), 3.667, 3.68);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (151, CAST('2023-05-31' AS Date), 3.671, 3.677);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (152, CAST('2023-06-01' AS Date), 3.675, 3.682);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (153, CAST('2023-06-02' AS Date), 3.676, 3.684);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (154, CAST('2023-06-03' AS Date), 3.682, 3.688);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (155, CAST('2023-06-04' AS Date), 3.682, 3.688);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (156, CAST('2023-06-05' AS Date), 3.682, 3.688);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (157, CAST('2023-06-06' AS Date), 3.673, 3.68);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (158, CAST('2023-06-07' AS Date), 3.687, 3.691);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (159, CAST('2023-06-08' AS Date), 3.671, 3.674);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (160, CAST('2023-06-09' AS Date), 3.655, 3.661);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (161, CAST('2023-06-10' AS Date), 3.641, 3.646);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (162, CAST('2023-06-11' AS Date), 3.641, 3.646);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (163, CAST('2023-06-12' AS Date), 3.641, 3.646);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (164, CAST('2023-06-13' AS Date), 3.65, 3.658);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (165, CAST('2023-06-14' AS Date), 3.641, 3.648);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (166, CAST('2023-06-15' AS Date), 3.646, 3.652);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (167, CAST('2023-06-16' AS Date), 3.648, 3.654);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (168, CAST('2023-06-17' AS Date), 3.631, 3.645);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (169, CAST('2023-06-18' AS Date), 3.631, 3.645);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (170, CAST('2023-06-19' AS Date), 3.631, 3.645);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (171, CAST('2023-06-20' AS Date), 3.629, 3.647);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (172, CAST('2023-06-21' AS Date), 3.641, 3.648);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (173, CAST('2023-06-22' AS Date), 3.64, 3.645);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (174, CAST('2023-06-23' AS Date), 3.634, 3.641);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (175, CAST('2023-06-24' AS Date), 3.628, 3.638);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (176, CAST('2023-06-25' AS Date), 3.628, 3.638);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (177, CAST('2023-06-26' AS Date), 3.628, 3.638);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (178, CAST('2023-06-27' AS Date), 3.635, 3.642);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (179, CAST('2023-06-28' AS Date), 3.633, 3.64);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (180, CAST('2023-06-29' AS Date), 3.628, 3.636);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (181, CAST('2023-06-30' AS Date), 3.628, 3.636);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (182, CAST('2023-07-01' AS Date), 3.624, 3.633);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (183, CAST('2023-07-02' AS Date), 3.624, 3.633);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (184, CAST('2023-07-03' AS Date), 3.624, 3.633);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (185, CAST('2023-07-04' AS Date), 3.621, 3.63);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (186, CAST('2023-07-05' AS Date), 3.624, 3.634);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (187, CAST('2023-07-06' AS Date), 3.627, 3.633);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (188, CAST('2023-07-07' AS Date), 3.641, 3.648);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (189, CAST('2023-07-08' AS Date), 3.637, 3.643);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (190, CAST('2023-07-09' AS Date), 3.637, 3.643);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (191, CAST('2023-07-10' AS Date), 3.637, 3.643);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (192, CAST('2023-07-11' AS Date), 3.636, 3.643);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (193, CAST('2023-07-12' AS Date), 3.632, 3.638);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (194, CAST('2023-07-13' AS Date), 3.588, 3.599);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (195, CAST('2023-07-14' AS Date), 3.565, 3.573);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (196, CAST('2023-07-15' AS Date), 3.558, 3.567);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (197, CAST('2023-07-16' AS Date), 3.558, 3.567);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (198, CAST('2023-07-17' AS Date), 3.558, 3.567);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (199, CAST('2023-07-18' AS Date), 3.555, 3.57);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (200, CAST('2023-07-19' AS Date), 3.552, 3.557);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (201, CAST('2023-07-20' AS Date), 3.571, 3.589);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (202, CAST('2023-07-21' AS Date), 3.583, 3.592);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (203, CAST('2023-07-22' AS Date), 3.58, 3.591);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (204, CAST('2023-07-23' AS Date), 3.58, 3.591);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (205, CAST('2023-07-24' AS Date), 3.58, 3.591);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (206, CAST('2023-07-25' AS Date), 3.581, 3.588);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (207, CAST('2023-07-26' AS Date), 3.596, 3.604);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (208, CAST('2023-07-27' AS Date), 3.596, 3.604);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (209, CAST('2023-07-28' AS Date), 3.591, 3.602);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (210, CAST('2023-07-29' AS Date), 3.591, 3.602);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (211, CAST('2023-07-30' AS Date), 3.591, 3.602);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (212, CAST('2023-07-31' AS Date), 3.591, 3.602);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (213, CAST('2023-08-01' AS Date), 3.602, 3.614);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (214, CAST('2023-08-02' AS Date), 3.624, 3.634);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (215, CAST('2023-08-03' AS Date), 3.655, 3.661);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (216, CAST('2023-08-04' AS Date), 3.69, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (217, CAST('2023-08-05' AS Date), 3.687, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (218, CAST('2023-08-06' AS Date), 3.687, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (219, CAST('2023-08-07' AS Date), 3.687, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (220, CAST('2023-08-08' AS Date), 3.686, 3.694);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (221, CAST('2023-08-09' AS Date), 3.704, 3.71);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (222, CAST('2023-08-10' AS Date), 3.691, 3.699);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (223, CAST('2023-08-11' AS Date), 3.672, 3.678);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (224, CAST('2023-08-12' AS Date), 3.678, 3.684);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (225, CAST('2023-08-13' AS Date), 3.678, 3.684);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (226, CAST('2023-08-14' AS Date), 3.678, 3.684);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (227, CAST('2023-08-15' AS Date), 3.695, 3.703);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (228, CAST('2023-08-16' AS Date), 3.713, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (229, CAST('2023-08-17' AS Date), 3.71, 3.717);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (230, CAST('2023-08-18' AS Date), 3.723, 3.73);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (231, CAST('2023-08-19' AS Date), 3.712, 3.72);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (232, CAST('2023-08-20' AS Date), 3.712, 3.72);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (233, CAST('2023-08-21' AS Date), 3.712, 3.72);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (234, CAST('2023-08-22' AS Date), 3.711, 3.722);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (235, CAST('2023-08-23' AS Date), 3.724, 3.729);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (236, CAST('2023-08-24' AS Date), 3.709, 3.716);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (237, CAST('2023-08-25' AS Date), 3.695, 3.701);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1237, CAST('2023-08-26' AS Date), 3.69, 3.699);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1238, CAST('2023-08-27' AS Date), 3.69, 3.699);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1239, CAST('2023-08-28' AS Date), 3.69, 3.699);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1240, CAST('2023-08-29' AS Date), 3.69, 3.699);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1241, CAST('2023-08-30' AS Date), 3.689, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1242, CAST('2023-08-31' AS Date), 3.689, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1243, CAST('2023-09-01' AS Date), 3.692, 3.699);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1244, CAST('2023-09-02' AS Date), 3.688, 3.695);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1245, CAST('2023-09-03' AS Date), 3.688, 3.695);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1246, CAST('2023-09-04' AS Date), 3.688, 3.695);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1247, CAST('2023-09-05' AS Date), 3.68, 3.698);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1248, CAST('2023-09-06' AS Date), 3.703, 3.709);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1249, CAST('2023-09-07' AS Date), 3.703, 3.708);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1250, CAST('2023-09-08' AS Date), 3.708, 3.713);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1251, CAST('2023-09-09' AS Date), 3.712, 3.716);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1252, CAST('2023-09-10' AS Date), 3.712, 3.716);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1253, CAST('2023-09-11' AS Date), 3.712, 3.716);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1254, CAST('2023-09-12' AS Date), 3.696, 3.704);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1255, CAST('2023-09-13' AS Date), 3.701, 3.708);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1256, CAST('2023-09-14' AS Date), 3.701, 3.706);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1257, CAST('2023-09-15' AS Date), 3.707, 3.712);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1258, CAST('2023-09-16' AS Date), 3.716, 3.722);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1259, CAST('2023-09-17' AS Date), 3.716, 3.722);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1260, CAST('2023-09-18' AS Date), 3.716, 3.722);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1261, CAST('2023-09-19' AS Date), 3.704, 3.714);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1262, CAST('2023-09-20' AS Date), 3.711, 3.717);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1263, CAST('2023-09-21' AS Date), 3.712, 3.718);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1264, CAST('2023-09-22' AS Date), 3.738, 3.744);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1265, CAST('2023-09-23' AS Date), 3.744, 3.748);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1266, CAST('2023-09-24' AS Date), 3.744, 3.748);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1267, CAST('2023-09-25' AS Date), 3.744, 3.748);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1268, CAST('2023-09-26' AS Date), 3.765, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1269, CAST('2023-09-27' AS Date), 3.779, 3.787);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1270, CAST('2023-09-28' AS Date), 3.793, 3.799);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1271, CAST('2023-09-29' AS Date), 3.801, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1272, CAST('2023-09-30' AS Date), 3.79, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1273, CAST('2023-10-01' AS Date), 3.79, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1274, CAST('2023-10-02' AS Date), 3.79, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1275, CAST('2023-10-03' AS Date), 3.793, 3.807);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1276, CAST('2023-10-04' AS Date), 3.806, 3.812);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1277, CAST('2023-10-05' AS Date), 3.813, 3.818);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1278, CAST('2023-10-06' AS Date), 3.819, 3.826);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1279, CAST('2023-10-07' AS Date), 3.823, 3.828);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1280, CAST('2023-10-08' AS Date), 3.823, 3.828);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1281, CAST('2023-10-09' AS Date), 3.823, 3.828);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1282, CAST('2023-10-10' AS Date), 3.826, 3.837);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1283, CAST('2023-10-11' AS Date), 3.82, 3.828);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1284, CAST('2023-10-12' AS Date), 3.822, 3.826);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1285, CAST('2023-10-13' AS Date), 3.836, 3.841);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1286, CAST('2023-10-14' AS Date), 3.842, 3.85);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1287, CAST('2023-10-15' AS Date), 3.842, 3.85);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1288, CAST('2023-10-16' AS Date), 3.842, 3.85);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1289, CAST('2023-10-17' AS Date), 3.853, 3.859);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1290, CAST('2023-10-18' AS Date), 3.854, 3.861);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1291, CAST('2023-10-19' AS Date), 3.864, 3.872);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1292, CAST('2023-10-20' AS Date), 3.868, 3.873);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1293, CAST('2023-10-21' AS Date), 3.867, 3.876);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1294, CAST('2023-10-22' AS Date), 3.867, 3.876);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1295, CAST('2023-10-23' AS Date), 3.867, 3.876);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1296, CAST('2023-10-24' AS Date), 3.867, 3.877);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1297, CAST('2023-10-25' AS Date), 3.862, 3.87);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1298, CAST('2023-10-26' AS Date), 3.866, 3.871);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1299, CAST('2023-10-27' AS Date), 3.865, 3.872);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1300, CAST('2023-10-28' AS Date), 3.856, 3.863);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1301, CAST('2023-10-29' AS Date), 3.856, 3.863);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1302, CAST('2023-10-30' AS Date), 3.856, 3.863);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1303, CAST('2023-10-31' AS Date), 3.84, 3.851);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1304, CAST('2023-11-01' AS Date), 3.832, 3.842);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1305, CAST('2023-11-02' AS Date), 3.832, 3.842);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1306, CAST('2023-11-03' AS Date), 3.797, 3.807);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1307, CAST('2023-11-04' AS Date), 3.749, 3.759);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1308, CAST('2023-11-05' AS Date), 3.749, 3.759);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1309, CAST('2023-11-06' AS Date), 3.749, 3.759);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1310, CAST('2023-11-07' AS Date), 3.754, 3.765);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1311, CAST('2023-11-08' AS Date), 3.769, 3.775);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1312, CAST('2023-11-09' AS Date), 3.793, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1313, CAST('2023-11-10' AS Date), 3.79, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1314, CAST('2023-11-11' AS Date), 3.806, 3.813);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1315, CAST('2023-11-12' AS Date), 3.806, 3.813);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1316, CAST('2023-11-13' AS Date), 3.806, 3.813);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1317, CAST('2023-11-14' AS Date), 3.808, 3.813);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1318, CAST('2023-11-15' AS Date), 3.765, 3.777);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1319, CAST('2023-11-16' AS Date), 3.769, 3.775);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1320, CAST('2023-11-17' AS Date), 3.767, 3.772);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1321, CAST('2023-11-18' AS Date), 3.768, 3.775);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1322, CAST('2023-11-19' AS Date), 3.768, 3.775);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1323, CAST('2023-11-20' AS Date), 3.768, 3.775);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1324, CAST('2023-11-21' AS Date), 3.74, 3.749);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1325, CAST('2023-11-22' AS Date), 3.738, 3.745);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1326, CAST('2023-11-23' AS Date), 3.74, 3.746);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1327, CAST('2023-11-24' AS Date), 3.74, 3.746);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1328, CAST('2023-11-25' AS Date), 3.728, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1329, CAST('2023-11-26' AS Date), 3.728, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1330, CAST('2023-11-27' AS Date), 3.728, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1331, CAST('2023-11-28' AS Date), 3.717, 3.729);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1332, CAST('2023-11-29' AS Date), 3.725, 3.732);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1333, CAST('2023-11-30' AS Date), 3.729, 3.737);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1334, CAST('2023-12-01' AS Date), 3.733, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1335, CAST('2023-12-02' AS Date), 3.729, 3.737);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1336, CAST('2023-12-03' AS Date), 3.729, 3.737);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1337, CAST('2023-12-04' AS Date), 3.729, 3.737);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1338, CAST('2023-12-05' AS Date), 3.738, 3.744);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1339, CAST('2023-12-06' AS Date), 3.751, 3.755);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1340, CAST('2023-12-07' AS Date), 3.751, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1341, CAST('2023-12-08' AS Date), 3.749, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1342, CAST('2023-12-09' AS Date), 3.749, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1343, CAST('2023-12-10' AS Date), 3.749, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1344, CAST('2023-12-11' AS Date), 3.749, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1345, CAST('2023-12-12' AS Date), 3.762, 3.774);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1346, CAST('2023-12-13' AS Date), 3.772, 3.778);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1347, CAST('2023-12-14' AS Date), 3.781, 3.785);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1348, CAST('2023-12-15' AS Date), 3.763, 3.772);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1349, CAST('2023-12-16' AS Date), 3.756, 3.771);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1350, CAST('2023-12-17' AS Date), 3.756, 3.771);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1351, CAST('2023-12-18' AS Date), 3.756, 3.771);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1352, CAST('2023-12-19' AS Date), 3.742, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1353, CAST('2023-12-20' AS Date), 3.712, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1354, CAST('2023-12-21' AS Date), 3.702, 3.712);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1355, CAST('2023-12-22' AS Date), 3.702, 3.709);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1356, CAST('2023-12-23' AS Date), 3.682, 3.69);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1357, CAST('2023-12-24' AS Date), 3.682, 3.69);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1358, CAST('2023-12-25' AS Date), 3.682, 3.69);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1359, CAST('2023-12-26' AS Date), 3.682, 3.69);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1360, CAST('2023-12-27' AS Date), 3.677, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1361, CAST('2023-12-28' AS Date), 3.687, 3.694);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1362, CAST('2023-12-29' AS Date), 3.695, 3.705);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1363, CAST('2023-12-30' AS Date), 3.705, 3.713);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1364, CAST('2023-12-31' AS Date), 3.705, 3.713);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1365, CAST('2024-01-01' AS Date), 3.705, 3.713);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1366, CAST('2024-01-02' AS Date), 3.705, 3.713);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1367, CAST('2024-01-03' AS Date), 3.705, 3.713);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1368, CAST('2024-01-04' AS Date), 3.726, 3.738);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1369, CAST('2024-01-05' AS Date), 3.736, 3.74);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1370, CAST('2024-01-06' AS Date), 3.713, 3.723);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1371, CAST('2024-01-07' AS Date), 3.713, 3.723);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1372, CAST('2024-01-08' AS Date), 3.713, 3.723);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1373, CAST('2024-01-09' AS Date), 3.71, 3.719);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1374, CAST('2024-01-10' AS Date), 3.703, 3.708);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1375, CAST('2024-01-11' AS Date), 3.698, 3.704);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1376, CAST('2024-01-12' AS Date), 3.697, 3.703);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1377, CAST('2024-01-13' AS Date), 3.69, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1378, CAST('2024-01-14' AS Date), 3.69, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1379, CAST('2024-01-15' AS Date), 3.69, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1380, CAST('2024-01-16' AS Date), 3.687, 3.704);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1381, CAST('2024-01-17' AS Date), 3.713, 3.718);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1382, CAST('2024-01-18' AS Date), 3.733, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1383, CAST('2024-01-19' AS Date), 3.736, 3.742);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1384, CAST('2024-01-20' AS Date), 3.736, 3.743);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1385, CAST('2024-01-21' AS Date), 3.736, 3.743);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1386, CAST('2024-01-22' AS Date), 3.736, 3.743);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1387, CAST('2024-01-23' AS Date), 3.736, 3.746);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1388, CAST('2024-01-24' AS Date), 3.75, 3.755);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1389, CAST('2024-01-25' AS Date), 3.755, 3.76);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1390, CAST('2024-01-26' AS Date), 3.757, 3.765);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1391, CAST('2024-01-27' AS Date), 3.777, 3.786);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1392, CAST('2024-01-28' AS Date), 3.777, 3.786);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1393, CAST('2024-01-29' AS Date), 3.777, 3.786);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1394, CAST('2024-01-30' AS Date), 3.797, 3.807);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1395, CAST('2024-01-31' AS Date), 3.806, 3.813);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1396, CAST('2024-02-01' AS Date), 3.799, 3.808);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1397, CAST('2024-02-02' AS Date), 3.802, 3.808);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1398, CAST('2024-02-03' AS Date), 3.821, 3.829);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1399, CAST('2024-02-04' AS Date), 3.821, 3.829);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1400, CAST('2024-02-05' AS Date), 3.821, 3.829);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1401, CAST('2024-02-06' AS Date), 3.843, 3.852);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1402, CAST('2024-02-07' AS Date), 3.853, 3.858);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1403, CAST('2024-02-08' AS Date), 3.865, 3.868);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1404, CAST('2024-02-09' AS Date), 3.85, 3.854);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1405, CAST('2024-02-10' AS Date), 3.86, 3.868);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1406, CAST('2024-02-11' AS Date), 3.86, 3.868);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1407, CAST('2024-02-12' AS Date), 3.86, 3.868);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1408, CAST('2024-02-13' AS Date), 3.861, 3.869);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1409, CAST('2024-02-14' AS Date), 3.877, 3.883);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1410, CAST('2024-02-15' AS Date), 3.87, 3.877);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1411, CAST('2024-02-16' AS Date), 3.864, 3.87);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1412, CAST('2024-02-17' AS Date), 3.826, 3.844);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1413, CAST('2024-02-18' AS Date), 3.826, 3.844);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1414, CAST('2024-02-19' AS Date), 3.826, 3.844);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1415, CAST('2024-02-20' AS Date), 3.795, 3.807);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1416, CAST('2024-02-21' AS Date), 3.777, 3.784);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1417, CAST('2024-02-22' AS Date), 3.789, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1418, CAST('2024-02-23' AS Date), 3.803, 3.807);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1419, CAST('2024-02-24' AS Date), 3.794, 3.799);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1420, CAST('2024-02-25' AS Date), 3.794, 3.799);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1421, CAST('2024-02-26' AS Date), 3.794, 3.799);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1422, CAST('2024-02-27' AS Date), 3.793, 3.803);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1423, CAST('2024-02-28' AS Date), 3.787, 3.793);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1424, CAST('2024-02-29' AS Date), 3.79, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1425, CAST('2024-03-01' AS Date), 3.778, 3.782);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1426, CAST('2024-03-02' AS Date), 3.768, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1427, CAST('2024-03-03' AS Date), 3.768, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1428, CAST('2024-03-04' AS Date), 3.768, 3.773);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1429, CAST('2024-03-05' AS Date), 3.762, 3.771);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1430, CAST('2024-03-06' AS Date), 3.764, 3.77);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1431, CAST('2024-03-07' AS Date), 3.73, 3.736);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1432, CAST('2024-03-08' AS Date), 3.721, 3.726);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1433, CAST('2024-03-09' AS Date), 3.688, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1434, CAST('2024-03-10' AS Date), 3.688, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1435, CAST('2024-03-11' AS Date), 3.688, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1436, CAST('2024-03-12' AS Date), 3.683, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1437, CAST('2024-03-13' AS Date), 3.687, 3.695);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1438, CAST('2024-03-14' AS Date), 3.665, 3.671);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1439, CAST('2024-03-15' AS Date), 3.67, 3.677);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1440, CAST('2024-03-16' AS Date), 3.684, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1441, CAST('2024-03-17' AS Date), 3.684, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1442, CAST('2024-03-18' AS Date), 3.684, 3.692);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1443, CAST('2024-03-19' AS Date), 3.689, 3.698);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1444, CAST('2024-03-20' AS Date), 3.698, 3.703);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1445, CAST('2024-03-21' AS Date), 3.69, 3.696);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1446, CAST('2024-03-22' AS Date), 3.695, 3.701);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1447, CAST('2024-03-23' AS Date), 3.69, 3.694);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1448, CAST('2024-03-24' AS Date), 3.69, 3.694);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1449, CAST('2024-03-25' AS Date), 3.69, 3.694);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1450, CAST('2024-03-26' AS Date), 3.698, 3.707);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1451, CAST('2024-03-27' AS Date), 3.721, 3.727);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1452, CAST('2024-03-28' AS Date), 3.714, 3.721);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1453, CAST('2024-03-29' AS Date), 3.714, 3.721);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1454, CAST('2024-03-30' AS Date), 3.714, 3.721);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1455, CAST('2024-03-31' AS Date), 3.714, 3.721);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1456, CAST('2024-04-01' AS Date), 3.714, 3.721);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1457, CAST('2024-04-02' AS Date), 3.717, 3.734);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1458, CAST('2024-04-03' AS Date), 3.704, 3.711);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1459, CAST('2024-04-04' AS Date), 3.685, 3.694);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1460, CAST('2024-04-05' AS Date), 3.673, 3.685);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1461, CAST('2024-04-06' AS Date), 3.678, 3.691);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1462, CAST('2024-04-07' AS Date), 3.678, 3.691);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1463, CAST('2024-04-08' AS Date), 3.678, 3.691);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1464, CAST('2024-04-09' AS Date), 3.666, 3.681);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1465, CAST('2024-04-10' AS Date), 3.684, 3.693);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1466, CAST('2024-04-11' AS Date), 3.701, 3.71);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1467, CAST('2024-04-12' AS Date), 3.702, 3.711);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1468, CAST('2024-04-13' AS Date), 3.698, 3.707);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1469, CAST('2024-04-14' AS Date), 3.698, 3.707);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1470, CAST('2024-04-15' AS Date), 3.698, 3.707);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1471, CAST('2024-04-16' AS Date), 3.711, 3.725);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1472, CAST('2024-04-17' AS Date), 3.747, 3.755);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1473, CAST('2024-04-18' AS Date), 3.738, 3.749);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1474, CAST('2024-04-19' AS Date), 3.735, 3.744);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1475, CAST('2024-04-20' AS Date), 3.693, 3.702);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1476, CAST('2024-04-21' AS Date), 3.693, 3.702);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1477, CAST('2024-04-22' AS Date), 3.693, 3.702);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1478, CAST('2024-04-23' AS Date), 3.685, 3.701);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1479, CAST('2024-04-24' AS Date), 3.694, 3.701);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1480, CAST('2024-04-25' AS Date), 3.714, 3.724);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1481, CAST('2024-04-26' AS Date), 3.735, 3.745);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1482, CAST('2024-04-27' AS Date), 3.745, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1483, CAST('2024-04-28' AS Date), 3.745, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1484, CAST('2024-04-29' AS Date), 3.745, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1485, CAST('2024-04-30' AS Date), 3.726, 3.734);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1486, CAST('2024-05-01' AS Date), 3.743, 3.752);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1487, CAST('2024-05-02' AS Date), 3.743, 3.752);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1488, CAST('2024-05-03' AS Date), 3.747, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1489, CAST('2024-05-04' AS Date), 3.747, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1490, CAST('2024-05-05' AS Date), 3.747, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1491, CAST('2024-05-06' AS Date), 3.747, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1492, CAST('2024-05-07' AS Date), 3.747, 3.757);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1493, CAST('2024-05-08' AS Date), 3.72, 3.728);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1494, CAST('2024-05-09' AS Date), 3.719, 3.724);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1495, CAST('2024-05-10' AS Date), 3.719, 3.724);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1496, CAST('2024-05-11' AS Date), 3.701, 3.708);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1497, CAST('2024-05-12' AS Date), 3.701, 3.708);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1498, CAST('2024-05-13' AS Date), 3.701, 3.708);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1499, CAST('2024-05-14' AS Date), 3.713, 3.723);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1500, CAST('2024-05-15' AS Date), 3.719, 3.728);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1501, CAST('2024-05-16' AS Date), 3.712, 3.718);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1502, CAST('2024-05-17' AS Date), 3.721, 3.728);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1503, CAST('2024-05-18' AS Date), 3.735, 3.742);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1504, CAST('2024-05-19' AS Date), 3.735, 3.742);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1505, CAST('2024-05-20' AS Date), 3.735, 3.742);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1506, CAST('2024-05-21' AS Date), 3.727, 3.736);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1507, CAST('2024-05-22' AS Date), 3.726, 3.733);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1508, CAST('2024-05-23' AS Date), 3.735, 3.743);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1509, CAST('2024-05-24' AS Date), 3.738, 3.746);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1510, CAST('2024-05-25' AS Date), 3.73, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1511, CAST('2024-05-26' AS Date), 3.73, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1512, CAST('2024-05-27' AS Date), 3.73, 3.739);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1513, CAST('2024-05-28' AS Date), 3.731, 3.748);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1514, CAST('2024-05-29' AS Date), 3.738, 3.75);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1515, CAST('2024-05-30' AS Date), 3.752, 3.762);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1516, CAST('2024-05-31' AS Date), 3.744, 3.753);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1517, CAST('2024-06-01' AS Date), 3.735, 3.741);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1518, CAST('2024-06-02' AS Date), 3.735, 3.741);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1519, CAST('2024-06-03' AS Date), 3.735, 3.741);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1520, CAST('2024-06-04' AS Date), 3.735, 3.741);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1521, CAST('2024-06-05' AS Date), 3.735, 3.741);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1522, CAST('2024-06-06' AS Date), 3.741, 3.747);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1523, CAST('2024-06-07' AS Date), 3.747, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1524, CAST('2024-06-08' AS Date), 3.747, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1525, CAST('2024-06-09' AS Date), 3.747, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1526, CAST('2024-06-10' AS Date), 3.747, 3.756);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1527, CAST('2024-06-11' AS Date), 3.765, 3.781);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1528, CAST('2024-06-12' AS Date), 3.779, 3.787);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1529, CAST('2024-06-13' AS Date), 3.766, 3.772);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1530, CAST('2024-06-14' AS Date), 3.77, 3.777);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1531, CAST('2024-06-15' AS Date), 3.764, 3.776);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1532, CAST('2024-06-16' AS Date), 3.764, 3.776);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1533, CAST('2024-06-17' AS Date), 3.764, 3.776);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1534, CAST('2024-06-18' AS Date), 3.783, 3.796);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1535, CAST('2024-06-19' AS Date), 3.78, 3.789);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1536, CAST('2024-06-20' AS Date), 3.81, 3.818);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1537, CAST('2024-06-21' AS Date), 3.802, 3.814);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1538, CAST('2024-06-22' AS Date), 3.795, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1539, CAST('2024-06-23' AS Date), 3.795, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1540, CAST('2024-06-24' AS Date), 3.795, 3.806);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1541, CAST('2024-06-25' AS Date), 3.795, 3.805);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1542, CAST('2024-06-26' AS Date), 3.807, 3.817);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1543, CAST('2024-06-27' AS Date), 3.811, 3.822);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1544, CAST('2024-06-28' AS Date), 3.817, 3.825);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1545, CAST('2024-06-29' AS Date), 3.827, 3.837);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1546, CAST('2024-06-30' AS Date), 3.827, 3.837);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1547, CAST('2024-07-01' AS Date), 3.827, 3.837);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1548, CAST('2024-07-02' AS Date), 3.834, 3.851);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1549, CAST('2024-07-03' AS Date), 3.826, 3.833);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1550, CAST('2024-07-04' AS Date), 3.793, 3.802);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1551, CAST('2024-07-05' AS Date), 3.788, 3.802);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1552, CAST('2024-07-06' AS Date), 3.788, 3.797);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.tipcamsunat (id, fecha, compra, venta) VALUES (1553, CAST('2024-07-07' AS Date), 3.788, 3.797);
/* SET IDENTITY_INSERT [General].[tipcamsunat] OFF */
 
/* SET IDENTITY_INSERT [General].[usuarios] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO General.usuarios (id, usuario, contraseña) VALUES (1, 'Admin', '12345');
/* SET IDENTITY_INSERT [General].[usuarios] OFF */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('001', '000', '001000001', 'TRANSFERENCIAS ENTRE CAJAS', 8);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('002', '000', '002000001', 'INGRESOS DOCUMENTADOS', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('002', '000', '002000002', 'OTROS INGRESOS', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('002', '001', '002001001', 'RETROEXCAVADORA', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('003', '000', '003000001', 'ANTICIPOS A CLIENTES', 11);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('003', '000', '003000002', 'ANTICIPOS DE PROVEEDORES', 12);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('004', '000', '004000001', 'RENDICIONES POR COBRAR', 13);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('004', '000', '004000002', 'RENDICIONES POR PAGAR', 14);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '101', '101101001', 'COCECHADORAS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '101', '101101002', 'OPERADOR-RETROESCAVADORA', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '101', '101101003', 'OPERADOR-TRACTOR', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '101', '101101004', 'TRACTOR', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '101', '101101005', 'RETROESCABADORA', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '102', '101102001', 'EXAMEN MEDICO', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '102', '101102002', 'MOVILIDAD', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '102', '101102003', 'SEGUROS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '102', '101102004', 'SUELDOS', 15);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '102', '101102005', 'VARIOS-TRAB', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '103', '101103001', 'ASESORIA', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '103', '101103002', 'TRAMITES', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('101', '104', '101104001', 'VEHICULOS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('102', '101', '102101001', 'EQUIPOS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('102', '101', '102101002', 'OFICINA', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('102', '101', '102101003', 'SERVICIOS DE OFICINA', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('102', '101', '102101004', 'UTILES DE ESCRITORIO', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('102', '102', '102102001', 'GASTOS FINANCIEROS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('102', '102', '102102002', 'OTROS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('103', '000', '103000001', 'PAGO A SOCIOS', 16);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('002', '001', '2001002', 'TRACTOR 135', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.detalle (id_familias, id_subfamilia, id, descripcion, id_cuenta) VALUES ('002', '001', '2001003', 'TRACTOR110', 1);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('001', 'TRANSFERENCIAS', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('002', 'INGRESOS', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('003', 'ANTICIPOS', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('004', 'RENDICIONES', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('101', 'COSTO', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('102', 'GASTO', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('103', 'SOCIOS', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.familias (id, descripcion, id_tipofamilias) VALUES ('104', 'ADELANTOS', 1);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('101', '101', 'MAQUINISTAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('001', '000', 'GENERAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('002', '000', 'GENERAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('101', '102', 'TRABAJADORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('101', '103', 'TRAMITES-ASESORIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('101', '104', 'VEHICULOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('102', '101', 'OFICINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('102', '102', 'OTROS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('003', '000', 'GENERAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('004', '000', 'GENERAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('103', '000', 'GENERAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('002', '001', 'TRACTOR Y RETRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.subfamilias (id_familias, id, desripcion) VALUES ('104', '000', 'GENERAL');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.tipofamilia (id, descripcion) VALUES (1, 'BALANCE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Logistica.tipofamilia (id, descripcion) VALUES (2, 'RESULTADOS');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla02_tipodedocumentodeidentidad (id, descripcion, abreviado) VALUES ('0', 'OTROS TIPOS DE DOCUMENTOS', 'OTRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla02_tipodedocumentodeidentidad (id, descripcion, abreviado) VALUES ('1', 'DOCUMENTO NACIONAL DE IDENTIDAD (DNI)', 'DNI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla02_tipodedocumentodeidentidad (id, descripcion, abreviado) VALUES ('4', 'CARNET DE EXTRANJERIA', 'C. EXTR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla02_tipodedocumentodeidentidad (id, descripcion, abreviado) VALUES ('6', 'REGISTRO ÚNICO DE CONTRIBUYENTES', 'RUC');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla02_tipodedocumentodeidentidad (id, descripcion, abreviado) VALUES ('7', 'PASAPORTE', 'PAS');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla04_tipodemoneda (Id, Descripcion) VALUES ('PEN', 'NUEVOS SOLES
');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla04_tipodemoneda (Id, Descripcion) VALUES ('USD', 'DÓLARES AMERICANOS
');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('00', 'Otros (especificar)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('01', 'Factura');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('02', 'Recibo por Honorarios');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('03', 'Boleta de Venta');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('04', 'Liquidacion de compra');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('05', 'Boleto de compañia de aviacion comercial por el servicio de transporte aereo de pasajeros');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('06', 'Carta de porte aereo por el servicio de transporte de carga aerea');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('07', 'Nota de credito');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('08', 'Nota de debito');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('09', 'Guia de remision - Remitente');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('10', 'Recibo por Arrendamiento');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('11', 'Poliza emitida por las Bolsas de Valores, Bolsas de Productos o Agentes de Intermediacion por operaciones realizadas en las Bolsas de Valores o Productos o fuera de las mismas, autorizadas por CONASEV');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('12', 'Ticket o cinta emitido por maquina registradora');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('13', 'Documento emitido por bancos, instituciones financieras, crediticias y de seguros que se encuentren bajo el control de la Superintendencia de Banca y Seguros');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('14', 'Recibo por servicios publicos de suministro de energia electrica, agua, telefono, telex y telegraficos y otros servicios complementarios que se incluyan en el recibo de servicio publico');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('15', 'Boleto emitido por las empresas de transporte publico urbano de pasajeros');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('16', 'Boleto de viaje emitido por las empresas de transporte publico interprovincial de pasajeros dentro del pais');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('17', 'Documento emitido por la Iglesia Catolica por el arrendamiento de bienes inmuebles');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('18', 'Documento emitido por las Administradoras Privadas de Fondo de Pensiones que se encuentran bajo la supervision de la Superintendencia de Administradoras Privadas de Fondos de Pensiones');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('19', 'Boleto o entrada por atracciones y espectaculos publicos');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('20', 'Comprobante de Retencion');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('21', 'Conocimiento de embarque por el servicio de transporte de carga maritima');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('22', 'Comprobante por Operaciones No Habituales');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('23', 'Polizas de Adjudicacion emitidas con ocasion del remate o adjudicacion de bienes por venta forzada, por los martilleros o las entidades que rematen o subasten bienes por cuenta de terceros');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('24', 'Certificado de pago de regalias emitidas por PERUPETRO S.A');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('25', 'Documento de Atribucion (Ley del Impuesto General a las Ventas e Impuesto Selectivo al Consumo, Art. 19º, ultimo parrafo, R.S. N° 022-98-SUNAT).');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('26', 'Recibo por el Pago de la Tarifa por Uso de Agua Superficial con fines agrarios y por el pago de la Cuota para la ejecucion de una determinada obra o actividad acordada por la Asamblea General de la Comision de Regantes o Resolucion expedida por el Jefe de la Unidad de Aguas y de Riego (Decreto Supremo N° 003-90-AG, Arts. 28 y 48)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('27', 'Seguro Complementario de Trabajo de Riesgo');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('28', 'Tarifa Unificada de Uso de Aeropuerto');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('29', 'Documentos emitidos por la COFOPRI en calidad de oferta de venta de terrenos, los correspondientes a las subastas publicas y a la retribucion de los servicios que presta');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('30', 'Documentos emitidos por las empresas que desempeñan el rol adquirente en los sistemas de pago mediante tarjetas de credito y debito');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('31', 'Guia de Remision - Transportista');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('32', 'Documentos emitidos por las empresas recaudadoras de la denominada Garantia de Red Principal a la que hace referencia el numeral 7.6 del articulo 7° de la Ley N° 27133 – Ley de Promocion del Desarrollo de la Industria del Gas Natural');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('34', 'Documento del Operador');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('35', 'Documento del Participe');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('36', 'Recibo de Distribucion de Gas Natural');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('37', 'Documentos que emitan los concesionarios del servicio de revisiones tecnicas vehiculares, por la prestacion de dicho ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('50', 'Declaracion unica de Aduanas - Importacion definitiva                 ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('52', 'Despacho Simplificado - Importacion Simplificada                        ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('53', 'Declaracion de Mensajeria o Courier                                         ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('54', 'Liquidacion de Cobranza                                                     ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('70', 'Orden de Compra');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('71', 'Orden de Compra Receptiva');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('72', 'Orden de Compra Correctiva');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('73', 'Sub Orden de Compra');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('74', 'Vaucher de Transferencia');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('75', 'Comprobante interno');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('76', 'Comprobante de Anticipo');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('77', 'Vaucher de Rendicion');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('79', 'Orden de Servicio');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('80', 'Documento de Proceso de Emergencia');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('81', 'Cotizacion');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('87', 'Nota de Credito Especial');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('88', 'Nota de Debito Especial');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('91', 'Comprobante de No Domiciliado                                                 ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('96', 'Exceso de credito fiscal por retiro de bienes                           ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('97', 'Nota de Credito - No Domiciliado');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('98', 'Nota de Debito - No Domiciliado');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tabla10_tipodecomprobantedepagoodocumento (id, descripcion) VALUES ('99', 'Otros -Consolidado de Boletas de Venta');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tasas_igv (id, tasa, numero) VALUES (0, 'No Gravado', 0);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tasas_igv (id, tasa, numero) VALUES (1, '18%', 0.18);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Sunat.tasas_igv (id, tasa, numero) VALUES (2, '10%', 0.1);
 
/* SET IDENTITY_INSERT [Tesoreria].[aperturas] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (29, 1, 1, '2024', 5, CAST('2024-05-20' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (30, 1, 2, '2024', 5, CAST('2024-05-22' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (31, 1, 3, '2024', 5, CAST('2024-05-23' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (32, 2, 1, '2024', 5, CAST('2024-05-01' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (33, 2, 2, '2024', 5, CAST('2024-05-02' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (34, 2, 3, '2024', 5, CAST('2024-05-03' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (35, 2, 4, '2024', 5, CAST('2024-05-04' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (36, 2, 5, '2024', 5, CAST('2024-05-05' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (37, 2, 6, '2024', 5, CAST('2024-05-06' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (38, 2, 7, '2024', 5, CAST('2024-05-07' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (39, 2, 8, '2024', 5, CAST('2024-05-08' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (40, 2, 9, '2024', 5, CAST('2024-05-09' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (41, 2, 10, '2024', 5, CAST('2024-05-10' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (42, 2, 11, '2024', 5, CAST('2024-05-11' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (43, 2, 12, '2024', 5, CAST('2024-05-12' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (44, 2, 13, '2024', 5, CAST('2024-05-13' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (45, 2, 14, '2024', 5, CAST('2024-05-14' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (46, 2, 15, '2024', 5, CAST('2024-05-15' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (47, 2, 16, '2024', 5, CAST('2024-05-16' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (48, 2, 17, '2024', 5, CAST('2024-05-17' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (49, 2, 18, '2024', 5, CAST('2024-05-18' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (50, 2, 19, '2024', 5, CAST('2024-05-19' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (51, 2, 20, '2024', 5, CAST('2024-05-20' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (52, 1, 4, '2024', 5, CAST('2024-05-27' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (53, 1, 5, '2024', 5, CAST('2024-05-28' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (54, 1, 6, '2024', 5, CAST('2024-05-30' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (55, 1, 7, '2024', 5, CAST('2024-05-31' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (56, 2, 21, '2024', 5, CAST('2024-05-21' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (57, 2, 22, '2024', 5, CAST('2024-05-22' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (58, 2, 23, '2024', 5, CAST('2024-05-23' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (59, 2, 24, '2024', 5, CAST('2024-05-24' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (60, 2, 25, '2024', 5, CAST('2024-05-25' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (61, 2, 26, '2024', 3, CAST('2024-05-26' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (62, 2, 27, '2024', 5, CAST('2024-05-27' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (63, 1, 8, '2024', 6, CAST('2024-06-01' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (64, 1, 9, '2024', 6, CAST('2024-06-03' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (65, 1, 10, '2024', 6, CAST('2024-06-04' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (66, 1, 11, '2024', 6, CAST('2024-06-05' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (67, 1, 12, '2024', 6, CAST('2024-06-06' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (68, 1, 13, '2024', 6, CAST('2024-06-10' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (69, 1, 14, '2024', 6, CAST('2024-06-11' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (70, 1, 15, '2024', 6, CAST('2024-06-12' AS Date));
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.aperturas (id, id_tipo, numero, año, id_mes, fecha) VALUES (71, 1, 16, '2024', 6, CAST('2024-06-13' AS Date));
/* SET IDENTITY_INSERT [Tesoreria].[aperturas] OFF */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (1, 'CUENTAS POR COBRAR', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (2, 'DETRACCIONES POR COBRAR', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (3, 'CUENTAS POR PAGAR', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (4, 'DETRACCIONES POR PAGAR', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (5, 'CAJA CHICA', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (6, 'INGRESO', 4);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (7, 'GASTO', 5);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (8, 'TRANSFERENCIAS HACIA OTRAS CUENTAS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (9, 'TRANSFERENCIAS DE OTRAS CUENTAS', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (10, 'CAJA BANCOS', 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (11, 'ANTICIPOS DE CLIENTES', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (12, 'ANTICIPOS A PROVEEDORES', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (13, 'RENDICIONES POR COBRAR', 2);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (14, 'RENDICIONES POR PAGAR', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (15, 'CUENTAS POR PAGAR AL PERSONAL', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (16, 'CUENTAS POR PAGAR A SOCIOS', 3);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.cuentas (id, Descripcion, id_tCuenta) VALUES (17, 'ADELANTOS AL PERSONAL', 2);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.debehaber (id, descripcion) VALUES (1, 'DEBE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.debehaber (id, descripcion) VALUES (2, 'HABER');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.libros (id, descripcion) VALUES (1, 'VENTAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.libros (id, descripcion) VALUES (2, 'COMPRAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.libros (id, descripcion) VALUES (3, 'CAJA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.libros (id, descripcion) VALUES (4, 'APLICACIONES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.libros (id, descripcion) VALUES (5, 'TRANSFERENCIAS');
 
/* SET IDENTITY_INSERT [Tesoreria].[movimientosdecaja] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1163, 2, NULL, 1, CAST('2024-05-01' AS Date), 292, 16, 2, 366000, NULL, CAST('2024-05-17T12:27:52.030' AS TIMESTAMP(3)), 'PRESTAMO DE SOCIO MACHACA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1186, 1, NULL, 1, CAST('2024-04-18' AS Date), 37, 1, 1, 164486.83, NULL, CAST('2024-05-18T07:00:05.700' AS TIMESTAMP(3)), 'SERVICIOS DE ALGUILER VOLQUETE Y RETRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1187, 1, NULL, 1, CAST('2024-04-18' AS Date), 37, 2, 1, 18276, NULL, CAST('2024-05-18T07:00:05.700' AS TIMESTAMP(3)), 'SERVICIOS DE ALGUILER VOLQUETE Y RETRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1191, 3, NULL, 1, CAST('2023-12-31' AS Date), NULL, 5, 1, 428.43, NULL, CAST('2024-05-20T10:58:58.510' AS TIMESTAMP(3)), 'SALDO INICIAL AL 2024-05-19');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1192, 2, NULL, 3, CAST('2024-05-18' AS Date), 294, 3, 2, 120, NULL, CAST('2024-05-20T17:44:41.120' AS TIMESTAMP(3)), 'VIATICOS CONTADOR_RICARDO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1193, 3, 29, 2, CAST('2024-05-20' AS Date), 294, 3, 1, 120, NULL, CAST('2024-05-20T17:44:41.167' AS TIMESTAMP(3)), 'VIATICOS CONTADOR_RICARDO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1194, 3, 29, 2, CAST('2024-05-20' AS Date), 294, 5, 2, 120, NULL, CAST('2024-05-20T17:44:41.173' AS TIMESTAMP(3)), 'VIATICOS CONTADOR_RICARDO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1195, 2, NULL, 4, CAST('2024-05-19' AS Date), 295, 3, 2, 110.03, NULL, CAST('2024-05-20T17:52:19.123' AS TIMESTAMP(3)), 'COMBUSTIBLE MIRLESS - DILIGENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1196, 3, 29, 3, CAST('2024-05-20' AS Date), 295, 3, 1, 110.03, NULL, CAST('2024-05-20T17:52:19.343' AS TIMESTAMP(3)), 'COMBUSTIBLE MIRLESS - DILIGENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1197, 3, 29, 3, CAST('2024-05-20' AS Date), 295, 5, 2, 110.03, NULL, CAST('2024-05-20T17:52:19.353' AS TIMESTAMP(3)), 'COMBUSTIBLE MIRLESS - DILIGENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1201, 2, NULL, 5, CAST('2024-05-22' AS Date), 297, 3, 2, 5, NULL, CAST('2024-05-22T18:17:14.997' AS TIMESTAMP(3)), 'CORRECTOR EN CINTA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1202, 3, 30, 1, CAST('2024-05-22' AS Date), 297, 3, 1, 5, NULL, CAST('2024-05-22T18:17:15.053' AS TIMESTAMP(3)), 'CORRECTOR EN CINTA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1203, 3, 30, 1, CAST('2024-05-22' AS Date), 297, 5, 2, 5, NULL, CAST('2024-05-22T18:17:15.070' AS TIMESTAMP(3)), 'CORRECTOR EN CINTA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1204, 2, NULL, 6, CAST('2024-05-22' AS Date), 298, 3, 2, 15.5, NULL, CAST('2024-05-22T18:19:01.723' AS TIMESTAMP(3)), 'ARTICULOS DE LIMPIEZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1205, 3, 30, 2, CAST('2024-05-22' AS Date), 298, 3, 1, 15.5, NULL, CAST('2024-05-22T18:19:01.793' AS TIMESTAMP(3)), 'ARTICULOS DE LIMPIEZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1206, 3, 30, 2, CAST('2024-05-22' AS Date), 298, 5, 2, 15.5, NULL, CAST('2024-05-22T18:19:01.800' AS TIMESTAMP(3)), 'ARTICULOS DE LIMPIEZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1207, 2, NULL, 7, CAST('2024-05-23' AS Date), 299, 3, 2, 35, NULL, CAST('2024-05-23T16:21:43.160' AS TIMESTAMP(3)), 'ALMUERZO PERSONAL - OFICINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1208, 3, 31, 1, CAST('2024-05-23' AS Date), 299, 3, 1, 35, NULL, CAST('2024-05-23T16:21:43.203' AS TIMESTAMP(3)), 'ALMUERZO PERSONAL - OFICINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1209, 3, 31, 1, CAST('2024-05-23' AS Date), 299, 5, 2, 35, NULL, CAST('2024-05-23T16:21:43.210' AS TIMESTAMP(3)), 'ALMUERZO PERSONAL - OFICINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1210, 2, NULL, 8, CAST('2024-05-01' AS Date), 300, 3, 2, 150, NULL, CAST('2024-05-27T15:04:35.363' AS TIMESTAMP(3)), 'COMBUSTIBLES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1211, 3, 32, 1, CAST('2024-05-01' AS Date), 300, 3, 1, 150, NULL, CAST('2024-05-27T15:04:56.970' AS TIMESTAMP(3)), 'COMBUSTIBLES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1212, 3, 32, 1, CAST('2024-05-01' AS Date), 300, 10, 2, 150, NULL, CAST('2024-05-27T15:04:59.680' AS TIMESTAMP(3)), 'COMBUSTIBLES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1213, 3, 32, 2, CAST('2024-05-01' AS Date), 301, 12, 1, 6000, NULL, CAST('2024-05-27T15:05:15.020' AS TIMESTAMP(3)), 'COMBUSTIBLE ADELANTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1214, 3, 32, 2, CAST('2024-05-01' AS Date), 301, 10, 2, 6000, NULL, CAST('2024-05-27T15:05:15.020' AS TIMESTAMP(3)), 'COMBUSTIBLE ADELANTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1215, 3, 33, 1, CAST('2024-05-02' AS Date), 302, 9, 1, 500, NULL, CAST('2024-05-27T15:05:15.913' AS TIMESTAMP(3)), 'DINERO PARA CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1216, 3, 33, 1, CAST('2024-05-02' AS Date), 302, 10, 2, 500, NULL, CAST('2024-05-27T15:05:15.917' AS TIMESTAMP(3)), 'DINERO PARA CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1217, 2, NULL, 9, CAST('2024-05-02' AS Date), 303, 3, 2, 260, NULL, CAST('2024-05-27T15:05:16.690' AS TIMESTAMP(3)), 'PAGO COLEGIO INGENIEROS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1218, 3, 33, 2, CAST('2024-05-02' AS Date), 303, 3, 1, 260, NULL, CAST('2024-05-27T15:05:16.693' AS TIMESTAMP(3)), 'PAGO COLEGIO INGENIEROS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1219, 3, 33, 2, CAST('2024-05-02' AS Date), 303, 10, 2, 260, NULL, CAST('2024-05-27T15:05:16.693' AS TIMESTAMP(3)), 'PAGO COLEGIO INGENIEROS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1220, 3, 33, 3, CAST('2024-05-02' AS Date), 304, 12, 1, 3400, NULL, CAST('2024-05-27T15:05:21.007' AS TIMESTAMP(3)), 'ADELANTO GUSTABO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1221, 3, 33, 3, CAST('2024-05-02' AS Date), 304, 10, 2, 3400, NULL, CAST('2024-05-27T15:05:21.010' AS TIMESTAMP(3)), 'ADELANTO GUSTABO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1222, 3, 33, 4, CAST('2024-05-02' AS Date), 305, 12, 1, 10000, NULL, CAST('2024-05-27T15:05:21.013' AS TIMESTAMP(3)), 'COMBUSTIBLE ADELANTO GUTIERREZ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1223, 3, 33, 4, CAST('2024-05-02' AS Date), 305, 10, 2, 10000, NULL, CAST('2024-05-27T15:05:21.013' AS TIMESTAMP(3)), 'COMBUSTIBLE ADELANTO GUTIERREZ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1224, 2, NULL, 10, CAST('2024-05-02' AS Date), 306, 3, 2, 50, NULL, CAST('2024-05-27T15:05:21.017' AS TIMESTAMP(3)), 'COMBUSTIBLE MOTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1225, 3, 33, 5, CAST('2024-05-02' AS Date), 306, 3, 1, 50, NULL, CAST('2024-05-27T15:05:21.017' AS TIMESTAMP(3)), 'COMBUSTIBLE MOTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1226, 3, 33, 5, CAST('2024-05-02' AS Date), 306, 10, 2, 50, NULL, CAST('2024-05-27T15:05:21.017' AS TIMESTAMP(3)), 'COMBUSTIBLE MOTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1227, 2, NULL, 11, CAST('2024-05-02' AS Date), 307, 3, 2, 50, NULL, CAST('2024-05-27T15:05:21.020' AS TIMESTAMP(3)), 'COMBUSTIBLE AUTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1228, 3, 33, 6, CAST('2024-05-02' AS Date), 307, 3, 1, 50, NULL, CAST('2024-05-27T15:05:21.020' AS TIMESTAMP(3)), 'COMBUSTIBLE AUTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1229, 3, 33, 6, CAST('2024-05-02' AS Date), 307, 10, 2, 50, NULL, CAST('2024-05-27T15:05:21.020' AS TIMESTAMP(3)), 'COMBUSTIBLE AUTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1230, 2, NULL, 12, CAST('2024-05-02' AS Date), 308, 3, 2, 206.4, NULL, CAST('2024-05-27T15:05:21.023' AS TIMESTAMP(3)), 'PAGO SCTR PERSONAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1231, 3, 33, 7, CAST('2024-05-02' AS Date), 308, 3, 1, 206.4, NULL, CAST('2024-05-27T15:05:21.027' AS TIMESTAMP(3)), 'PAGO SCTR PERSONAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1232, 3, 33, 7, CAST('2024-05-02' AS Date), 308, 10, 2, 206.4, NULL, CAST('2024-05-27T15:05:21.027' AS TIMESTAMP(3)), 'PAGO SCTR PERSONAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1236, 2, NULL, 14, CAST('2024-05-03' AS Date), 310, 3, 2, 95, NULL, CAST('2024-05-27T15:05:21.033' AS TIMESTAMP(3)), 'CONSUMO-COMIDA PRESIDENTE PASCANA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1237, 3, 34, 2, CAST('2024-05-03' AS Date), 310, 3, 1, 95, NULL, CAST('2024-05-27T15:05:21.037' AS TIMESTAMP(3)), 'CONSUMO-COMIDA PRESIDENTE PASCANA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1238, 3, 34, 2, CAST('2024-05-03' AS Date), 310, 10, 2, 95, NULL, CAST('2024-05-27T15:05:21.037' AS TIMESTAMP(3)), 'CONSUMO-COMIDA PRESIDENTE PASCANA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1239, 2, NULL, 15, CAST('2024-05-03' AS Date), 311, 3, 2, 1000, NULL, CAST('2024-05-27T15:05:21.040' AS TIMESTAMP(3)), 'ARMANDO SANIZO PARABRIZAS TRACTOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1240, 3, 34, 3, CAST('2024-05-03' AS Date), 311, 3, 1, 1000, NULL, CAST('2024-05-27T15:05:21.040' AS TIMESTAMP(3)), 'ARMANDO SANIZO PARABRIZAS TRACTOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1241, 3, 34, 3, CAST('2024-05-03' AS Date), 311, 10, 2, 1000, NULL, CAST('2024-05-27T15:05:21.040' AS TIMESTAMP(3)), 'ARMANDO SANIZO PARABRIZAS TRACTOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1242, 2, NULL, 16, CAST('2024-05-03' AS Date), 312, 3, 2, 50.94, NULL, CAST('2024-05-27T15:05:21.040' AS TIMESTAMP(3)), 'PAGO VIDA LEY');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1243, 3, 34, 4, CAST('2024-05-03' AS Date), 312, 3, 1, 50.94, NULL, CAST('2024-05-27T15:05:21.043' AS TIMESTAMP(3)), 'PAGO VIDA LEY');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1244, 3, 34, 4, CAST('2024-05-03' AS Date), 312, 10, 2, 50.94, NULL, CAST('2024-05-27T15:05:21.043' AS TIMESTAMP(3)), 'PAGO VIDA LEY');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1245, 2, NULL, 17, CAST('2024-05-03' AS Date), 313, 3, 2, 168, NULL, CAST('2024-05-27T15:05:21.047' AS TIMESTAMP(3)), 'PASAJES MILUSKA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1246, 3, 34, 5, CAST('2024-05-03' AS Date), 313, 3, 1, 168, NULL, CAST('2024-05-27T15:05:21.047' AS TIMESTAMP(3)), 'PASAJES MILUSKA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1247, 3, 34, 5, CAST('2024-05-03' AS Date), 313, 10, 2, 168, NULL, CAST('2024-05-27T15:05:21.047' AS TIMESTAMP(3)), 'PASAJES MILUSKA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1248, 2, NULL, 18, CAST('2024-05-03' AS Date), 314, 3, 2, 2728.87, NULL, CAST('2024-05-27T15:05:21.050' AS TIMESTAMP(3)), 'COMPRA ALOTES TRACTORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1249, 3, 34, 6, CAST('2024-05-03' AS Date), 314, 3, 1, 2728.87, NULL, CAST('2024-05-27T15:05:21.050' AS TIMESTAMP(3)), 'COMPRA ALOTES TRACTORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1250, 3, 34, 6, CAST('2024-05-03' AS Date), 314, 10, 2, 2728.87, NULL, CAST('2024-05-27T15:05:21.050' AS TIMESTAMP(3)), 'COMPRA ALOTES TRACTORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1251, 3, 34, 7, CAST('2024-05-03' AS Date), 315, 12, 1, 1000, NULL, CAST('2024-05-27T15:05:21.053' AS TIMESTAMP(3)), 'ADELANTO RONAL RIVERA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1252, 3, 34, 7, CAST('2024-05-03' AS Date), 315, 10, 2, 1000, NULL, CAST('2024-05-27T15:05:21.053' AS TIMESTAMP(3)), 'ADELANTO RONAL RIVERA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1256, 2, NULL, 20, CAST('2024-05-04' AS Date), 317, 3, 2, 5450, NULL, CAST('2024-05-27T15:05:21.063' AS TIMESTAMP(3)), 'JAVIER SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1257, 3, 35, 2, CAST('2024-05-04' AS Date), 317, 3, 1, 5450, NULL, CAST('2024-05-27T15:05:21.063' AS TIMESTAMP(3)), 'JAVIER SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1258, 3, 35, 2, CAST('2024-05-04' AS Date), 317, 10, 2, 5450, NULL, CAST('2024-05-27T15:05:21.063' AS TIMESTAMP(3)), 'JAVIER SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1259, 3, 35, 3, CAST('2024-05-04' AS Date), 318, 12, 1, 800, NULL, CAST('2024-05-27T15:05:21.067' AS TIMESTAMP(3)), 'ADELANTO GUSTABO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1260, 3, 35, 3, CAST('2024-05-04' AS Date), 318, 10, 2, 800, NULL, CAST('2024-05-27T15:05:21.067' AS TIMESTAMP(3)), 'ADELANTO GUSTABO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1261, 3, 35, 4, CAST('2024-05-04' AS Date), 319, 12, 1, 3000, NULL, CAST('2024-05-27T15:05:21.070' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE GRIFO DAVICAM');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1262, 3, 35, 4, CAST('2024-05-04' AS Date), 319, 10, 2, 3000, NULL, CAST('2024-05-27T15:05:21.070' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE GRIFO DAVICAM');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1263, 2, NULL, 21, CAST('2024-05-05' AS Date), 320, 3, 2, 500, NULL, CAST('2024-05-27T15:05:21.073' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1264, 3, 36, 1, CAST('2024-05-05' AS Date), 320, 3, 1, 500, NULL, CAST('2024-05-27T15:05:21.077' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1265, 3, 36, 1, CAST('2024-05-05' AS Date), 320, 10, 2, 500, NULL, CAST('2024-05-27T15:05:21.077' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1269, 3, 36, 3, CAST('2024-05-05' AS Date), 322, 9, 1, 650, NULL, CAST('2024-05-27T15:05:21.083' AS TIMESTAMP(3)), 'TRANSFERENCIA CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1270, 3, 36, 3, CAST('2024-05-05' AS Date), 322, 10, 2, 650, NULL, CAST('2024-05-27T15:05:21.087' AS TIMESTAMP(3)), 'TRANSFERENCIA CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1271, 3, 36, 4, CAST('2024-05-05' AS Date), 323, 12, 1, 2000, NULL, CAST('2024-05-27T15:05:21.090' AS TIMESTAMP(3)), 'ADELANTO FLORA QUIPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1272, 3, 36, 4, CAST('2024-05-05' AS Date), 323, 10, 2, 2000, NULL, CAST('2024-05-27T15:05:21.090' AS TIMESTAMP(3)), 'ADELANTO FLORA QUIPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1273, 2, NULL, 23, CAST('2024-05-05' AS Date), 324, 3, 2, 1060, NULL, CAST('2024-05-27T15:05:21.090' AS TIMESTAMP(3)), 'SERV FORMULA 1');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1274, 3, 36, 5, CAST('2024-05-05' AS Date), 324, 3, 1, 1060, NULL, CAST('2024-05-27T15:05:21.093' AS TIMESTAMP(3)), 'SERV FORMULA 1');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1275, 3, 36, 5, CAST('2024-05-05' AS Date), 324, 10, 2, 1060, NULL, CAST('2024-05-27T15:05:21.093' AS TIMESTAMP(3)), 'SERV FORMULA 1');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1276, 2, NULL, 24, CAST('2024-05-06' AS Date), 325, 3, 2, 3500, NULL, CAST('2024-05-27T15:05:21.093' AS TIMESTAMP(3)), 'JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1277, 3, 37, 1, CAST('2024-05-06' AS Date), 325, 3, 1, 3500, NULL, CAST('2024-05-27T15:05:21.097' AS TIMESTAMP(3)), 'JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1278, 3, 37, 1, CAST('2024-05-06' AS Date), 325, 10, 2, 3500, NULL, CAST('2024-05-27T15:05:21.097' AS TIMESTAMP(3)), 'JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1282, 2, NULL, 26, CAST('2024-05-06' AS Date), 327, 3, 2, 150, NULL, CAST('2024-05-27T15:05:21.107' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1283, 3, 37, 3, CAST('2024-05-06' AS Date), 327, 3, 1, 150, NULL, CAST('2024-05-27T15:05:21.107' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1284, 3, 37, 3, CAST('2024-05-06' AS Date), 327, 10, 2, 150, NULL, CAST('2024-05-27T15:05:21.107' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1285, 2, NULL, 27, CAST('2024-05-06' AS Date), 328, 3, 2, 100, NULL, CAST('2024-05-27T15:05:21.110' AS TIMESTAMP(3)), 'COMBUSTIBLE TRACTOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1286, 3, 37, 4, CAST('2024-05-06' AS Date), 328, 3, 1, 100, NULL, CAST('2024-05-27T15:05:21.110' AS TIMESTAMP(3)), 'COMBUSTIBLE TRACTOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1287, 3, 37, 4, CAST('2024-05-06' AS Date), 328, 10, 2, 100, NULL, CAST('2024-05-27T15:05:21.110' AS TIMESTAMP(3)), 'COMBUSTIBLE TRACTOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1288, 2, NULL, 28, CAST('2024-05-06' AS Date), 329, 3, 2, 50, NULL, CAST('2024-05-27T15:05:21.117' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMION');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1289, 3, 37, 5, CAST('2024-05-06' AS Date), 329, 3, 1, 50, NULL, CAST('2024-05-27T15:05:21.120' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMION');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1290, 3, 37, 5, CAST('2024-05-06' AS Date), 329, 10, 2, 50, NULL, CAST('2024-05-27T15:05:21.120' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMION');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1291, 3, 37, 6, CAST('2024-05-06' AS Date), 38, 10, 1, 20000, NULL, CAST('2024-05-27T15:06:19.657' AS TIMESTAMP(3)), 'DEPOSITO JUAN MACHACA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1292, 3, 37, 6, CAST('2024-05-06' AS Date), 38, 16, 2, 20000, NULL, CAST('2024-05-27T15:06:22.867' AS TIMESTAMP(3)), 'DEPOSITO JUAN MACHACA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1295, 3, 38, 1, CAST('2024-05-07' AS Date), 331, 12, 1, 6000, NULL, CAST('2024-05-27T15:06:27.520' AS TIMESTAMP(3)), 'ADELANTO ERICA PEREZ ANCCO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1296, 3, 38, 1, CAST('2024-05-07' AS Date), 331, 10, 2, 6000, NULL, CAST('2024-05-27T15:06:27.520' AS TIMESTAMP(3)), 'ADELANTO ERICA PEREZ ANCCO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1297, 3, 38, 2, CAST('2024-05-07' AS Date), 332, 12, 1, 3000, NULL, CAST('2024-05-27T15:06:27.523' AS TIMESTAMP(3)), 'ADELANTO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1298, 3, 38, 2, CAST('2024-05-07' AS Date), 332, 10, 2, 3000, NULL, CAST('2024-05-27T15:06:27.527' AS TIMESTAMP(3)), 'ADELANTO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1299, 2, NULL, 29, CAST('2024-05-07' AS Date), 333, 3, 2, 1500, NULL, CAST('2024-05-27T15:06:27.530' AS TIMESTAMP(3)), 'PAGO CESAR PORTUGAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1300, 3, 38, 3, CAST('2024-05-07' AS Date), 333, 3, 1, 1500, NULL, CAST('2024-05-27T15:06:27.530' AS TIMESTAMP(3)), 'PAGO CESAR PORTUGAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1301, 3, 38, 3, CAST('2024-05-07' AS Date), 333, 10, 2, 1500, NULL, CAST('2024-05-27T15:06:27.530' AS TIMESTAMP(3)), 'PAGO CESAR PORTUGAL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1305, 2, NULL, 31, CAST('2024-05-07' AS Date), 335, 3, 2, 100, NULL, CAST('2024-05-27T15:06:27.540' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1306, 3, 38, 5, CAST('2024-05-07' AS Date), 335, 3, 1, 100, NULL, CAST('2024-05-27T15:06:27.540' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1307, 3, 38, 5, CAST('2024-05-07' AS Date), 335, 10, 2, 100, NULL, CAST('2024-05-27T15:06:27.540' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1308, 3, 38, 6, CAST('2024-05-07' AS Date), 336, 12, 1, 300, NULL, CAST('2024-05-27T15:06:27.543' AS TIMESTAMP(3)), 'ADELANTO LOPEZ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1309, 3, 38, 6, CAST('2024-05-07' AS Date), 336, 10, 2, 300, NULL, CAST('2024-05-27T15:06:27.543' AS TIMESTAMP(3)), 'ADELANTO LOPEZ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1310, 3, 39, 1, CAST('2024-05-08' AS Date), 337, 12, 1, 4000, NULL, CAST('2024-05-27T15:06:27.550' AS TIMESTAMP(3)), 'ADELANTO MIGUEL CALDERON');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1311, 3, 39, 1, CAST('2024-05-08' AS Date), 337, 10, 2, 4000, NULL, CAST('2024-05-27T15:06:27.550' AS TIMESTAMP(3)), 'ADELANTO MIGUEL CALDERON');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1312, 2, NULL, 32, CAST('2024-05-08' AS Date), 338, 3, 2, 870, NULL, CAST('2024-05-27T15:06:27.550' AS TIMESTAMP(3)), 'ROSMERY PACORI-CANASTAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1313, 3, 39, 2, CAST('2024-05-08' AS Date), 338, 3, 1, 870, NULL, CAST('2024-05-27T15:06:27.553' AS TIMESTAMP(3)), 'ROSMERY PACORI-CANASTAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1314, 3, 39, 2, CAST('2024-05-08' AS Date), 338, 10, 2, 870, NULL, CAST('2024-05-27T15:06:27.553' AS TIMESTAMP(3)), 'ROSMERY PACORI-CANASTAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1315, 2, NULL, 33, CAST('2024-05-08' AS Date), 339, 3, 2, 240, NULL, CAST('2024-05-27T15:06:27.557' AS TIMESTAMP(3)), 'PAGO CARPINTERO YASMANI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1316, 3, 39, 3, CAST('2024-05-08' AS Date), 339, 3, 1, 240, NULL, CAST('2024-05-27T15:06:27.557' AS TIMESTAMP(3)), 'PAGO CARPINTERO YASMANI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1317, 3, 39, 3, CAST('2024-05-08' AS Date), 339, 10, 2, 240, NULL, CAST('2024-05-27T15:06:27.557' AS TIMESTAMP(3)), 'PAGO CARPINTERO YASMANI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1318, 3, 39, 4, CAST('2024-05-08' AS Date), 340, 9, 1, 6160.7, NULL, CAST('2024-05-27T15:06:27.563' AS TIMESTAMP(3)), 'ADELANTO ROSA UGARTE VELA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1319, 3, 39, 4, CAST('2024-05-08' AS Date), 340, 10, 2, 6160.7, NULL, CAST('2024-05-27T15:06:27.563' AS TIMESTAMP(3)), 'ADELANTO ROSA UGARTE VELA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1320, 3, 39, 5, CAST('2024-05-08' AS Date), 341, 12, 1, 7269, NULL, CAST('2024-05-27T15:06:27.567' AS TIMESTAMP(3)), 'TRANSPAL MANING AND CONSTRUC-MARIO TORRES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1321, 3, 39, 5, CAST('2024-05-08' AS Date), 341, 10, 2, 7269, NULL, CAST('2024-05-27T15:06:27.567' AS TIMESTAMP(3)), 'TRANSPAL MANING AND CONSTRUC-MARIO TORRES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1322, 2, NULL, 34, CAST('2024-05-08' AS Date), 342, 3, 2, 1398.2, NULL, CAST('2024-05-27T15:06:27.570' AS TIMESTAMP(3)), 'AGRO FERRETERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1323, 3, 39, 6, CAST('2024-05-08' AS Date), 342, 3, 1, 1398.2, NULL, CAST('2024-05-27T15:06:27.570' AS TIMESTAMP(3)), 'AGRO FERRETERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1324, 3, 39, 6, CAST('2024-05-08' AS Date), 342, 10, 2, 1398.2, NULL, CAST('2024-05-27T15:06:27.570' AS TIMESTAMP(3)), 'AGRO FERRETERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1325, 3, 39, 7, CAST('2024-05-08' AS Date), 39, 10, 1, 6160.7, NULL, CAST('2024-05-27T15:06:32.017' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1326, 3, 39, 7, CAST('2024-05-08' AS Date), 39, 8, 2, 6160.7, NULL, CAST('2024-05-27T15:06:32.020' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1327, 2, NULL, 35, CAST('2024-05-09' AS Date), 343, 3, 2, 100, NULL, CAST('2024-05-27T15:06:32.020' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1328, 3, 40, 1, CAST('2024-05-09' AS Date), 343, 3, 1, 100, NULL, CAST('2024-05-27T15:06:32.023' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1329, 3, 40, 1, CAST('2024-05-09' AS Date), 343, 10, 2, 100, NULL, CAST('2024-05-27T15:06:32.027' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1330, 2, NULL, 36, CAST('2024-05-09' AS Date), 344, 3, 2, 50, NULL, CAST('2024-05-27T15:06:32.030' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1331, 3, 40, 2, CAST('2024-05-09' AS Date), 344, 3, 1, 50, NULL, CAST('2024-05-27T15:06:32.030' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1332, 3, 40, 2, CAST('2024-05-09' AS Date), 344, 10, 2, 50, NULL, CAST('2024-05-27T15:06:32.030' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1333, 3, 40, 3, CAST('2024-05-09' AS Date), 345, 12, 1, 2000, NULL, CAST('2024-05-27T15:06:32.033' AS TIMESTAMP(3)), 'ADELANTO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1334, 3, 40, 3, CAST('2024-05-09' AS Date), 345, 10, 2, 2000, NULL, CAST('2024-05-27T15:06:32.033' AS TIMESTAMP(3)), 'ADELANTO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1335, 3, 40, 4, CAST('2024-05-09' AS Date), 346, 12, 1, 6160.7, NULL, CAST('2024-05-27T15:06:32.040' AS TIMESTAMP(3)), 'ADELANTO ROSA JULIANA UGARTE VELA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1336, 3, 40, 4, CAST('2024-05-09' AS Date), 346, 10, 2, 6160.7, NULL, CAST('2024-05-27T15:06:32.040' AS TIMESTAMP(3)), 'ADELANTO ROSA JULIANA UGARTE VELA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1337, 3, 41, 1, CAST('2024-05-10' AS Date), 347, 12, 1, 2500, NULL, CAST('2024-05-27T15:06:32.043' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1338, 3, 41, 1, CAST('2024-05-10' AS Date), 347, 10, 2, 2500, NULL, CAST('2024-05-27T15:06:32.047' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1339, 3, 41, 2, CAST('2024-05-10' AS Date), 348, 12, 1, 5390.1, NULL, CAST('2024-05-27T15:06:32.050' AS TIMESTAMP(3)), 'ADELNATO MOLINO SENEN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1340, 3, 41, 2, CAST('2024-05-10' AS Date), 348, 10, 2, 5390.1, NULL, CAST('2024-05-27T15:06:32.050' AS TIMESTAMP(3)), 'ADELNATO MOLINO SENEN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1341, 3, 41, 3, CAST('2024-05-10' AS Date), 349, 12, 1, 5000, NULL, CAST('2024-05-27T15:06:32.053' AS TIMESTAMP(3)), 'ADELANTO ELOY MOLINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1342, 3, 41, 3, CAST('2024-05-10' AS Date), 349, 10, 2, 5000, NULL, CAST('2024-05-27T15:06:32.053' AS TIMESTAMP(3)), 'ADELANTO ELOY MOLINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1358, 2, NULL, 42, CAST('2024-05-11' AS Date), 355, 3, 2, 1500, NULL, CAST('2024-05-27T15:06:32.083' AS TIMESTAMP(3)), 'PAGO CONTRATO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1359, 3, 42, 5, CAST('2024-05-11' AS Date), 355, 3, 1, 1500, NULL, CAST('2024-05-27T15:06:32.083' AS TIMESTAMP(3)), 'PAGO CONTRATO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1360, 3, 42, 5, CAST('2024-05-11' AS Date), 355, 10, 2, 1500, NULL, CAST('2024-05-27T15:06:32.083' AS TIMESTAMP(3)), 'PAGO CONTRATO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1361, 3, 42, 6, CAST('2024-05-11' AS Date), 356, 12, 1, 2000, NULL, CAST('2024-05-27T15:06:32.090' AS TIMESTAMP(3)), 'ADELANTO URIEL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1362, 3, 42, 6, CAST('2024-05-11' AS Date), 356, 10, 2, 2000, NULL, CAST('2024-05-27T15:06:32.090' AS TIMESTAMP(3)), 'ADELANTO URIEL');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1363, 2, NULL, 43, CAST('2024-05-11' AS Date), 357, 3, 2, 150, NULL, CAST('2024-05-27T15:06:32.093' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1364, 3, 42, 7, CAST('2024-05-11' AS Date), 357, 3, 1, 150, NULL, CAST('2024-05-27T15:06:32.093' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1365, 3, 42, 7, CAST('2024-05-11' AS Date), 357, 10, 2, 150, NULL, CAST('2024-05-27T15:06:32.093' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1366, 2, NULL, 44, CAST('2024-05-11' AS Date), 358, 3, 2, 1000, NULL, CAST('2024-05-27T15:06:32.100' AS TIMESTAMP(3)), 'JAVIER SEHUIN LAPTOP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1367, 3, 42, 8, CAST('2024-05-11' AS Date), 358, 3, 1, 1000, NULL, CAST('2024-05-27T15:06:32.100' AS TIMESTAMP(3)), 'JAVIER SEHUIN LAPTOP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1368, 3, 42, 8, CAST('2024-05-11' AS Date), 358, 10, 2, 1000, NULL, CAST('2024-05-27T15:06:32.100' AS TIMESTAMP(3)), 'JAVIER SEHUIN LAPTOP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1369, 2, NULL, 45, CAST('2024-05-12' AS Date), 359, 3, 2, 50, NULL, CAST('2024-05-27T15:06:32.103' AS TIMESTAMP(3)), 'COMBUSTIBLE-AUTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1370, 3, 43, 1, CAST('2024-05-12' AS Date), 359, 3, 1, 50, NULL, CAST('2024-05-27T15:06:32.107' AS TIMESTAMP(3)), 'COMBUSTIBLE-AUTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1371, 3, 43, 1, CAST('2024-05-12' AS Date), 359, 10, 2, 50, NULL, CAST('2024-05-27T15:06:32.107' AS TIMESTAMP(3)), 'COMBUSTIBLE-AUTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1372, 2, NULL, 46, CAST('2024-05-13' AS Date), 360, 3, 2, 100, NULL, CAST('2024-05-27T15:06:32.110' AS TIMESTAMP(3)), 'COMBUSTIBLE-CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1373, 3, 44, 1, CAST('2024-05-13' AS Date), 360, 3, 1, 100, NULL, CAST('2024-05-27T15:06:32.110' AS TIMESTAMP(3)), 'COMBUSTIBLE-CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1374, 3, 44, 1, CAST('2024-05-13' AS Date), 360, 10, 2, 100, NULL, CAST('2024-05-27T15:06:32.113' AS TIMESTAMP(3)), 'COMBUSTIBLE-CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1378, 3, 44, 3, CAST('2024-05-13' AS Date), 362, 12, 1, 1000, NULL, CAST('2024-05-27T15:06:32.120' AS TIMESTAMP(3)), 'ADELANTO GUSTAVO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1379, 3, 44, 3, CAST('2024-05-13' AS Date), 362, 10, 2, 1000, NULL, CAST('2024-05-27T15:06:32.120' AS TIMESTAMP(3)), 'ADELANTO GUSTAVO MENDOZA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1380, 3, 44, 4, CAST('2024-05-13' AS Date), 363, 12, 1, 800, NULL, CAST('2024-05-27T15:06:32.123' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1381, 3, 44, 4, CAST('2024-05-13' AS Date), 363, 10, 2, 800, NULL, CAST('2024-05-27T15:06:32.127' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1382, 3, 44, 5, CAST('2024-05-13' AS Date), 364, 9, 1, 500, NULL, CAST('2024-05-27T15:06:32.130' AS TIMESTAMP(3)), 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1383, 3, 44, 5, CAST('2024-05-13' AS Date), 364, 10, 2, 500, NULL, CAST('2024-05-27T15:06:32.130' AS TIMESTAMP(3)), 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1384, 3, 45, 1, CAST('2024-05-14' AS Date), 365, 12, 1, 800, NULL, CAST('2024-05-27T15:06:32.133' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1385, 3, 45, 1, CAST('2024-05-14' AS Date), 365, 10, 2, 800, NULL, CAST('2024-05-27T15:06:32.133' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1386, 2, NULL, 48, CAST('2024-05-14' AS Date), 366, 3, 2, 270, NULL, CAST('2024-05-27T15:06:32.137' AS TIMESTAMP(3)), 'ASESORIA DE SEGURIDAD');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1387, 3, 45, 2, CAST('2024-05-14' AS Date), 366, 3, 1, 270, NULL, CAST('2024-05-27T15:06:32.140' AS TIMESTAMP(3)), 'ASESORIA DE SEGURIDAD');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1388, 3, 45, 2, CAST('2024-05-14' AS Date), 366, 10, 2, 270, NULL, CAST('2024-05-27T15:06:32.140' AS TIMESTAMP(3)), 'ASESORIA DE SEGURIDAD');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1389, 3, 46, 1, CAST('2024-05-15' AS Date), 367, 12, 1, 1000, NULL, CAST('2024-05-27T15:06:32.143' AS TIMESTAMP(3)), 'ADELANTO JOSE LUIS CONDORI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1390, 3, 46, 1, CAST('2024-05-15' AS Date), 367, 10, 2, 1000, NULL, CAST('2024-05-27T15:06:32.143' AS TIMESTAMP(3)), 'ADELANTO JOSE LUIS CONDORI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1391, 3, 46, 2, CAST('2024-05-15' AS Date), 368, 12, 1, 1000, NULL, CAST('2024-05-27T15:06:32.147' AS TIMESTAMP(3)), 'ADELANTO JOSE LUIS CONDORI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1392, 3, 46, 2, CAST('2024-05-15' AS Date), 368, 10, 2, 1000, NULL, CAST('2024-05-27T15:06:32.150' AS TIMESTAMP(3)), 'ADELANTO JOSE LUIS CONDORI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1393, 3, 46, 3, CAST('2024-05-15' AS Date), 369, 12, 1, 1000, NULL, CAST('2024-05-27T15:06:32.153' AS TIMESTAMP(3)), 'ADELANTO JOSE LUIS CONDORI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1394, 3, 46, 3, CAST('2024-05-15' AS Date), 369, 10, 2, 1000, NULL, CAST('2024-05-27T15:06:32.153' AS TIMESTAMP(3)), 'ADELANTO JOSE LUIS CONDORI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1395, 3, 46, 4, CAST('2024-05-15' AS Date), 370, 9, 1, 100, NULL, CAST('2024-05-27T15:06:32.157' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1396, 3, 46, 4, CAST('2024-05-15' AS Date), 370, 10, 2, 100, NULL, CAST('2024-05-27T15:06:32.157' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1397, 3, 46, 5, CAST('2024-05-15' AS Date), 40, 10, 1, 100, NULL, CAST('2024-05-27T15:06:32.160' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1398, 3, 46, 5, CAST('2024-05-15' AS Date), 40, 8, 2, 100, NULL, CAST('2024-05-27T15:06:32.160' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1399, 3, 47, 1, CAST('2024-05-16' AS Date), 371, 9, 1, 700, NULL, CAST('2024-05-27T15:06:32.163' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1400, 3, 47, 1, CAST('2024-05-16' AS Date), 371, 10, 2, 700, NULL, CAST('2024-05-27T15:06:32.163' AS TIMESTAMP(3)), 'ADELANTO RAUL QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1401, 3, 47, 2, CAST('2024-05-16' AS Date), 41, 10, 1, 700, NULL, CAST('2024-05-27T15:06:32.167' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1402, 3, 47, 2, CAST('2024-05-16' AS Date), 41, 8, 2, 700, NULL, CAST('2024-05-27T15:06:32.170' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1403, 2, NULL, 49, CAST('2024-05-17' AS Date), 372, 3, 2, 1000, NULL, CAST('2024-05-27T15:06:32.170' AS TIMESTAMP(3)), 'RONAL RIVERA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1404, 3, 48, 1, CAST('2024-05-17' AS Date), 372, 3, 1, 1000, NULL, CAST('2024-05-27T15:06:32.173' AS TIMESTAMP(3)), 'RONAL RIVERA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1405, 3, 48, 1, CAST('2024-05-17' AS Date), 372, 10, 2, 1000, NULL, CAST('2024-05-27T15:06:32.173' AS TIMESTAMP(3)), 'RONAL RIVERA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1406, 2, NULL, 50, CAST('2024-05-17' AS Date), 373, 3, 2, 900, NULL, CAST('2024-05-27T15:06:32.177' AS TIMESTAMP(3)), 'COMBUSTIBLE Y REGULARIZACION DE FACT IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1407, 3, 48, 2, CAST('2024-05-17' AS Date), 373, 3, 1, 900, NULL, CAST('2024-05-27T15:06:32.177' AS TIMESTAMP(3)), 'COMBUSTIBLE Y REGULARIZACION DE FACT IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1408, 3, 48, 2, CAST('2024-05-17' AS Date), 373, 10, 2, 900, NULL, CAST('2024-05-27T15:06:32.177' AS TIMESTAMP(3)), 'COMBUSTIBLE Y REGULARIZACION DE FACT IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1409, 2, NULL, 51, CAST('2024-05-18' AS Date), 374, 3, 2, 1200, NULL, CAST('2024-05-27T15:06:32.180' AS TIMESTAMP(3)), 'PAGO PRABRIZAS TRACTOR-ARMANDO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1410, 3, 49, 1, CAST('2024-05-18' AS Date), 374, 3, 1, 1200, NULL, CAST('2024-05-27T15:06:32.183' AS TIMESTAMP(3)), 'PAGO PRABRIZAS TRACTOR-ARMANDO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1411, 3, 49, 1, CAST('2024-05-18' AS Date), 374, 10, 2, 1200, NULL, CAST('2024-05-27T15:06:32.183' AS TIMESTAMP(3)), 'PAGO PRABRIZAS TRACTOR-ARMANDO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1412, 3, 49, 2, CAST('2024-05-18' AS Date), 375, 12, 1, 3520, NULL, CAST('2024-05-27T15:06:32.187' AS TIMESTAMP(3)), 'ADELANTO YESICA PARAYRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1413, 3, 49, 2, CAST('2024-05-18' AS Date), 375, 10, 2, 3520, NULL, CAST('2024-05-27T15:06:32.190' AS TIMESTAMP(3)), 'ADELANTO YESICA PARAYRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1414, 3, 49, 3, CAST('2024-05-18' AS Date), 376, 9, 1, 500, NULL, CAST('2024-05-27T15:06:32.190' AS TIMESTAMP(3)), 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1415, 3, 49, 3, CAST('2024-05-18' AS Date), 376, 10, 2, 500, NULL, CAST('2024-05-27T15:06:32.193' AS TIMESTAMP(3)), 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1416, 2, NULL, 52, CAST('2024-05-18' AS Date), 377, 3, 2, 800, NULL, CAST('2024-05-27T15:06:32.193' AS TIMESTAMP(3)), 'PAGO RICARDO PINEDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1417, 3, 49, 4, CAST('2024-05-18' AS Date), 377, 3, 1, 800, NULL, CAST('2024-05-27T15:06:32.197' AS TIMESTAMP(3)), 'PAGO RICARDO PINEDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1418, 3, 49, 4, CAST('2024-05-18' AS Date), 377, 10, 2, 800, NULL, CAST('2024-05-27T15:06:32.197' AS TIMESTAMP(3)), 'PAGO RICARDO PINEDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1419, 2, NULL, 53, CAST('2024-05-19' AS Date), 378, 3, 2, 100, NULL, CAST('2024-05-27T15:06:32.197' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1420, 3, 50, 1, CAST('2024-05-19' AS Date), 378, 3, 1, 100, NULL, CAST('2024-05-27T15:06:32.200' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1421, 3, 50, 1, CAST('2024-05-19' AS Date), 378, 10, 2, 100, NULL, CAST('2024-05-27T15:06:32.200' AS TIMESTAMP(3)), 'COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1422, 2, NULL, 54, CAST('2024-05-20' AS Date), 379, 3, 2, 708.5, NULL, CAST('2024-05-27T15:06:32.203' AS TIMESTAMP(3)), 'PAGO TACTORES-FORMULA 1');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1423, 3, 51, 1, CAST('2024-05-20' AS Date), 379, 3, 1, 708.5, NULL, CAST('2024-05-27T15:06:32.203' AS TIMESTAMP(3)), 'PAGO TACTORES-FORMULA 1');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1424, 3, 51, 1, CAST('2024-05-20' AS Date), 379, 10, 2, 708.5, NULL, CAST('2024-05-27T15:06:32.207' AS TIMESTAMP(3)), 'PAGO TACTORES-FORMULA 1');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1425, 2, NULL, 55, CAST('2024-05-20' AS Date), 380, 3, 2, 200, NULL, CAST('2024-05-27T15:06:32.210' AS TIMESTAMP(3)), 'RETIRO JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1426, 3, 51, 2, CAST('2024-05-20' AS Date), 380, 3, 1, 200, NULL, CAST('2024-05-27T15:06:32.210' AS TIMESTAMP(3)), 'RETIRO JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1427, 3, 51, 2, CAST('2024-05-20' AS Date), 380, 10, 2, 200, NULL, CAST('2024-05-27T15:06:32.210' AS TIMESTAMP(3)), 'RETIRO JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1428, 2, NULL, 56, CAST('2024-05-20' AS Date), 381, 3, 2, 100, NULL, CAST('2024-05-27T15:06:32.213' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1429, 3, 51, 3, CAST('2024-05-20' AS Date), 381, 3, 1, 100, NULL, CAST('2024-05-27T15:06:32.213' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1430, 3, 51, 3, CAST('2024-05-20' AS Date), 381, 10, 2, 100, NULL, CAST('2024-05-27T15:06:32.213' AS TIMESTAMP(3)), 'COMBUSTIBLE CAMIONETA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1431, 2, NULL, 57, CAST('2024-05-20' AS Date), 382, 3, 2, 40, NULL, CAST('2024-05-27T15:06:32.217' AS TIMESTAMP(3)), 'COMBUSTIBLE MOTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1432, 3, 51, 4, CAST('2024-05-20' AS Date), 382, 3, 1, 40, NULL, CAST('2024-05-27T15:06:32.217' AS TIMESTAMP(3)), 'COMBUSTIBLE MOTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1433, 3, 51, 4, CAST('2024-05-20' AS Date), 382, 10, 2, 40, NULL, CAST('2024-05-27T15:06:32.217' AS TIMESTAMP(3)), 'COMBUSTIBLE MOTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1434, 2, NULL, 58, CAST('2024-05-20' AS Date), 383, 3, 2, 67.5, NULL, CAST('2024-05-27T15:06:32.220' AS TIMESTAMP(3)), 'CONSUMO-COMIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1435, 3, 51, 5, CAST('2024-05-20' AS Date), 383, 3, 1, 67.5, NULL, CAST('2024-05-27T15:06:32.220' AS TIMESTAMP(3)), 'CONSUMO-COMIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1436, 3, 51, 5, CAST('2024-05-20' AS Date), 383, 10, 2, 67.5, NULL, CAST('2024-05-27T15:06:32.220' AS TIMESTAMP(3)), 'CONSUMO-COMIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1443, 2, NULL, 326, CAST('2024-05-06' AS Date), 326, 3, 2, 1950, NULL, CAST('2024-05-27T15:37:11.557' AS TIMESTAMP(3)), 'JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1444, 3, 37, 2, CAST('2024-05-06' AS Date), 326, 3, 1, 1950, NULL, CAST('2024-05-27T15:37:11.560' AS TIMESTAMP(3)), 'JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1445, 3, 37, 2, CAST('2024-05-06' AS Date), 326, 10, 2, 1950, NULL, CAST('2024-05-27T15:37:11.560' AS TIMESTAMP(3)), 'JOSE SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1446, 4, NULL, 1, CAST('2024-05-27' AS Date), 39, 8, 1, 6160.7, NULL, CAST('2024-05-27T15:44:43.883' AS TIMESTAMP(3)), 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C 0000-4');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1447, 4, NULL, 1, CAST('2024-05-27' AS Date), 340, 9, 2, 6160.7, NULL, CAST('2024-05-27T15:44:43.887' AS TIMESTAMP(3)), 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C 0000-3');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1448, 4, NULL, 2, CAST('2024-05-27' AS Date), 40, 8, 1, 100, NULL, CAST('2024-05-27T15:46:02.593' AS TIMESTAMP(3)), 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C 0000-8');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1449, 4, NULL, 2, CAST('2024-05-27' AS Date), 41, 8, 1, 700, NULL, CAST('2024-05-27T15:46:02.593' AS TIMESTAMP(3)), 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C 0000-10');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1450, 4, NULL, 2, CAST('2024-05-27' AS Date), 370, 9, 2, 100, NULL, CAST('2024-05-27T15:46:02.597' AS TIMESTAMP(3)), 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C 0000-7');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1451, 4, NULL, 2, CAST('2024-05-27' AS Date), 371, 9, 2, 700, NULL, CAST('2024-05-27T15:46:02.597' AS TIMESTAMP(3)), 'TECNICOS MECANICOS TAMBEÑOS DEL SUR S.A.C 0000-9');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1452, 3, NULL, 2, CAST('2023-12-31' AS Date), NULL, 10, 1, 119294.48, NULL, CAST('2024-05-27T16:01:16.313' AS TIMESTAMP(3)), 'SALDO INICIAL CAJA BANCOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1453, 3, 52, 1, CAST('2024-05-27' AS Date), 42, 5, 1, 600, NULL, CAST('2024-05-27T16:28:19.383' AS TIMESTAMP(3)), 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1454, 3, 52, 1, CAST('2024-05-27' AS Date), 42, 8, 2, 600, NULL, CAST('2024-05-27T16:28:19.393' AS TIMESTAMP(3)), 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1455, 2, NULL, 327, CAST('2024-05-27' AS Date), 384, 3, 2, 320, NULL, CAST('2024-05-27T16:29:39.570' AS TIMESTAMP(3)), 'LIMPIEZA OFICINA - MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1456, 3, 52, 2, CAST('2024-05-27' AS Date), 384, 3, 1, 320, NULL, CAST('2024-05-27T16:29:39.650' AS TIMESTAMP(3)), 'LIMPIEZA OFICINA - MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1457, 3, 52, 2, CAST('2024-05-27' AS Date), 384, 5, 2, 320, NULL, CAST('2024-05-27T16:29:39.657' AS TIMESTAMP(3)), 'LIMPIEZA OFICINA - MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1458, 2, NULL, 328, CAST('2024-05-27' AS Date), 385, 3, 2, 12, NULL, CAST('2024-05-27T16:37:20.257' AS TIMESTAMP(3)), 'ALMUERZO ADMINISTRADORA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1459, 3, 52, 3, CAST('2024-05-27' AS Date), 385, 3, 1, 12, NULL, CAST('2024-05-27T16:37:20.343' AS TIMESTAMP(3)), 'ALMUERZO ADMINISTRADORA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1460, 3, 52, 3, CAST('2024-05-27' AS Date), 385, 5, 2, 12, NULL, CAST('2024-05-27T16:37:20.357' AS TIMESTAMP(3)), 'ALMUERZO ADMINISTRADORA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1461, 2, NULL, 329, CAST('2024-05-25' AS Date), 387, 3, 2, 42.7, NULL, CAST('2024-05-28T11:30:05.380' AS TIMESTAMP(3)), 'COPIA LITERAL PARTIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1462, 3, 53, 1, CAST('2024-05-28' AS Date), 387, 3, 1, 42.7, NULL, CAST('2024-05-28T11:30:05.457' AS TIMESTAMP(3)), 'COPIA LITERAL PARTIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1463, 3, 53, 1, CAST('2024-05-28' AS Date), 387, 5, 2, 42.7, NULL, CAST('2024-05-28T11:30:05.467' AS TIMESTAMP(3)), 'COPIA LITERAL PARTIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1464, 2, NULL, 330, CAST('2024-05-23' AS Date), 388, 3, 2, 65.8, NULL, CAST('2024-05-28T11:41:18.647' AS TIMESTAMP(3)), 'PAGO LUZ - MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1465, 3, 53, 2, CAST('2024-05-28' AS Date), 388, 3, 1, 65.8, NULL, CAST('2024-05-28T11:41:18.767' AS TIMESTAMP(3)), 'PAGO LUZ - MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1466, 3, 53, 2, CAST('2024-05-28' AS Date), 388, 5, 2, 65.8, NULL, CAST('2024-05-28T11:41:18.777' AS TIMESTAMP(3)), 'PAGO LUZ - MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1467, 2, NULL, 331, CAST('2024-05-25' AS Date), 389, 3, 2, 72.39, NULL, CAST('2024-05-28T12:02:16.890' AS TIMESTAMP(3)), 'PAGO INTERNET MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1468, 3, 53, 3, CAST('2024-05-28' AS Date), 389, 3, 1, 72.39, NULL, CAST('2024-05-28T12:02:16.977' AS TIMESTAMP(3)), 'PAGO INTERNET MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1469, 3, 53, 3, CAST('2024-05-28' AS Date), 389, 5, 2, 72.39, NULL, CAST('2024-05-28T12:02:16.990' AS TIMESTAMP(3)), 'PAGO INTERNET MAYO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1470, 2, NULL, 332, CAST('2024-05-27' AS Date), 390, 3, 2, 11, NULL, CAST('2024-05-28T13:58:24.747' AS TIMESTAMP(3)), 'ALMUERZO CONTADOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1471, 3, 53, 4, CAST('2024-05-28' AS Date), 390, 3, 1, 11, NULL, CAST('2024-05-28T13:58:24.907' AS TIMESTAMP(3)), 'ALMUERZO CONTADOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1472, 3, 53, 4, CAST('2024-05-28' AS Date), 390, 5, 2, 11, NULL, CAST('2024-05-28T13:58:24.917' AS TIMESTAMP(3)), 'ALMUERZO CONTADOR');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1473, 2, NULL, 333, CAST('2024-05-28' AS Date), 391, 3, 2, 18, NULL, CAST('2024-05-28T17:25:24.510' AS TIMESTAMP(3)), 'PAQUETE HOJAS BOND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1474, 3, 53, 5, CAST('2024-05-28' AS Date), 391, 3, 1, 18, NULL, CAST('2024-05-28T17:25:25.263' AS TIMESTAMP(3)), 'PAQUETE HOJAS BOND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1475, 3, 53, 5, CAST('2024-05-28' AS Date), 391, 5, 2, 18, NULL, CAST('2024-05-28T17:25:25.273' AS TIMESTAMP(3)), 'PAQUETE HOJAS BOND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1476, 2, NULL, 334, CAST('2024-05-30' AS Date), 392, 3, 2, 60, NULL, CAST('2024-05-30T08:31:25.397' AS TIMESTAMP(3)), 'VIATICOS CONTADOR (ABELARDO)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1477, 3, 54, 1, CAST('2024-05-30' AS Date), 392, 3, 1, 60, NULL, CAST('2024-05-30T08:31:25.560' AS TIMESTAMP(3)), 'VIATICOS CONTADOR (ABELARDO)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1478, 3, 54, 1, CAST('2024-05-30' AS Date), 392, 5, 2, 60, NULL, CAST('2024-05-30T08:31:25.587' AS TIMESTAMP(3)), 'VIATICOS CONTADOR (ABELARDO)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1479, 2, NULL, 335, CAST('2024-05-30' AS Date), 393, 3, 2, 6, NULL, CAST('2024-05-30T08:32:17.930' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1480, 3, 54, 2, CAST('2024-05-30' AS Date), 393, 3, 1, 6, NULL, CAST('2024-05-30T08:32:17.997' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1481, 3, 54, 2, CAST('2024-05-30' AS Date), 393, 5, 2, 6, NULL, CAST('2024-05-30T08:32:18.003' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1482, 2, NULL, 336, CAST('2024-05-30' AS Date), 394, 3, 2, 12, NULL, CAST('2024-05-30T14:48:53.610' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1483, 3, 54, 3, CAST('2024-05-30' AS Date), 394, 3, 1, 12, NULL, CAST('2024-05-30T14:48:53.703' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1484, 3, 54, 3, CAST('2024-05-30' AS Date), 394, 5, 2, 12, NULL, CAST('2024-05-30T14:48:53.713' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1485, 2, NULL, 337, CAST('2024-05-30' AS Date), 395, 3, 2, 10, NULL, CAST('2024-05-31T17:30:13.660' AS TIMESTAMP(3)), 'MOVILIDAD MARK  - APOYO CLAVE SOL FLORA MARIA APAZA QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1486, 3, 55, 1, CAST('2024-05-31' AS Date), 395, 3, 1, 10, NULL, CAST('2024-05-31T17:30:13.723' AS TIMESTAMP(3)), 'MOVILIDAD MARK  - APOYO CLAVE SOL FLORA MARIA APAZA QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1487, 3, 55, 1, CAST('2024-05-31' AS Date), 395, 5, 2, 10, NULL, CAST('2024-05-31T17:30:13.767' AS TIMESTAMP(3)), 'MOVILIDAD MARK  - APOYO CLAVE SOL FLORA MARIA APAZA QUISPE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1488, 2, NULL, 338, CAST('2024-05-21' AS Date), 396, 3, 2, 1240, NULL, CAST('2024-06-05T10:54:12.593' AS TIMESTAMP(3)), 'PAGO CORREDOR ISAUL MAMANI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1489, 3, 56, 1, CAST('2024-05-21' AS Date), 396, 15, 1, 1240, NULL, CAST('2024-06-05T10:54:12.650' AS TIMESTAMP(3)), 'PAGO CORREDOR ISAUL MAMANI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1490, 3, 56, 1, CAST('2024-05-21' AS Date), 396, 10, 2, 1240, NULL, CAST('2024-06-05T10:54:12.653' AS TIMESTAMP(3)), 'PAGO CORREDOR ISAUL MAMANI');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1491, 2, NULL, 339, CAST('2024-05-21' AS Date), 397, 3, 2, 1000, NULL, CAST('2024-06-05T10:58:34.500' AS TIMESTAMP(3)), 'PAGO PRESTAMO PORTUGAL-TERRENO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1492, 3, 56, 2, CAST('2024-05-21' AS Date), 397, 3, 1, 1000, NULL, CAST('2024-06-05T10:58:34.547' AS TIMESTAMP(3)), 'PAGO PRESTAMO PORTUGAL-TERRENO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1493, 3, 56, 2, CAST('2024-05-21' AS Date), 397, 10, 2, 1000, NULL, CAST('2024-06-05T10:58:34.550' AS TIMESTAMP(3)), 'PAGO PRESTAMO PORTUGAL-TERRENO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1494, 2, NULL, 340, CAST('2024-05-22' AS Date), 398, 3, 2, 100, NULL, CAST('2024-06-05T11:10:29.663' AS TIMESTAMP(3)), 'COMBSUTBILE IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1495, 3, 57, 1, CAST('2024-05-22' AS Date), 398, 3, 1, 100, NULL, CAST('2024-06-05T11:10:29.780' AS TIMESTAMP(3)), 'COMBSUTBILE IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1496, 3, 57, 1, CAST('2024-05-22' AS Date), 398, 10, 2, 100, NULL, CAST('2024-06-05T11:10:29.783' AS TIMESTAMP(3)), 'COMBSUTBILE IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1497, 2, NULL, 341, CAST('2024-05-22' AS Date), 399, 3, 2, 500, NULL, CAST('2024-06-05T11:11:34.887' AS TIMESTAMP(3)), 'MARIA DEL PILAR-SEGURIDAD PROYECTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1498, 3, 57, 2, CAST('2024-05-22' AS Date), 399, 3, 1, 500, NULL, CAST('2024-06-05T11:11:34.930' AS TIMESTAMP(3)), 'MARIA DEL PILAR-SEGURIDAD PROYECTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1499, 3, 57, 2, CAST('2024-05-22' AS Date), 399, 10, 2, 500, NULL, CAST('2024-06-05T11:11:34.933' AS TIMESTAMP(3)), 'MARIA DEL PILAR-SEGURIDAD PROYECTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1500, 2, NULL, 342, CAST('2024-05-22' AS Date), 400, 3, 2, 300, NULL, CAST('2024-06-05T11:12:28.163' AS TIMESTAMP(3)), 'RETIRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1501, 3, 57, 3, CAST('2024-05-22' AS Date), 400, 3, 1, 300, NULL, CAST('2024-06-05T11:12:28.197' AS TIMESTAMP(3)), 'RETIRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1502, 3, 57, 3, CAST('2024-05-22' AS Date), 400, 10, 2, 300, NULL, CAST('2024-06-05T11:12:28.197' AS TIMESTAMP(3)), 'RETIRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1505, 3, 56, 3, CAST('2024-05-21' AS Date), 401, 12, 1, 5280, NULL, CAST('2024-06-05T11:16:14.330' AS TIMESTAMP(3)), 'ADELANTO-MOLINO SAN ROMAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1506, 3, 56, 3, CAST('2024-05-21' AS Date), 401, 10, 2, 5280, NULL, CAST('2024-06-05T11:16:14.337' AS TIMESTAMP(3)), 'ADELANTO-MOLINO SAN ROMAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1507, 2, NULL, 343, CAST('2024-05-23' AS Date), 402, 3, 2, 1733, NULL, CAST('2024-06-05T11:19:40.510' AS TIMESTAMP(3)), 'PAGO CORREDOR RICHARD');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1508, 3, 58, 1, CAST('2024-05-23' AS Date), 402, 15, 1, 1733, NULL, CAST('2024-06-05T11:19:40.587' AS TIMESTAMP(3)), 'PAGO CORREDOR RICHARD');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1509, 3, 58, 1, CAST('2024-05-23' AS Date), 402, 10, 2, 1733, NULL, CAST('2024-06-05T11:19:40.590' AS TIMESTAMP(3)), 'PAGO CORREDOR RICHARD');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1510, 2, NULL, 344, CAST('2024-05-23' AS Date), 403, 3, 2, 1540, NULL, CAST('2024-06-05T11:29:33.503' AS TIMESTAMP(3)), ' PAGO CORREDOR MILTON');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1511, 3, 58, 2, CAST('2024-05-23' AS Date), 403, 15, 1, 1540, NULL, CAST('2024-06-05T11:29:33.583' AS TIMESTAMP(3)), ' PAGO CORREDOR MILTON');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1512, 3, 58, 2, CAST('2024-05-23' AS Date), 403, 10, 2, 1540, NULL, CAST('2024-06-05T11:29:33.587' AS TIMESTAMP(3)), ' PAGO CORREDOR MILTON');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1513, 3, 58, 3, CAST('2024-05-23' AS Date), 404, 12, 1, 1000, NULL, CAST('2024-06-05T11:33:42.603' AS TIMESTAMP(3)), 'ADELANTO-SUELDO MIRLESS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1514, 3, 58, 3, CAST('2024-05-23' AS Date), 404, 10, 2, 1000, NULL, CAST('2024-06-05T11:33:42.607' AS TIMESTAMP(3)), 'ADELANTO-SUELDO MIRLESS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1515, 2, NULL, 345, CAST('2024-05-24' AS Date), 405, 3, 2, 100, NULL, CAST('2024-06-05T12:00:23.307' AS TIMESTAMP(3)), 'COMBUSTIBLE-IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1516, 3, 59, 1, CAST('2024-05-24' AS Date), 405, 3, 1, 100, NULL, CAST('2024-06-05T12:00:23.357' AS TIMESTAMP(3)), 'COMBUSTIBLE-IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1517, 3, 59, 1, CAST('2024-05-24' AS Date), 405, 10, 2, 100, NULL, CAST('2024-06-05T12:00:23.360' AS TIMESTAMP(3)), 'COMBUSTIBLE-IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1518, 2, NULL, 346, CAST('2024-05-24' AS Date), 406, 3, 2, 300, NULL, CAST('2024-06-05T12:00:54.133' AS TIMESTAMP(3)), 'RETIRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1519, 3, 59, 2, CAST('2024-05-24' AS Date), 406, 3, 1, 300, NULL, CAST('2024-06-05T12:00:54.140' AS TIMESTAMP(3)), 'RETIRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1520, 3, 59, 2, CAST('2024-05-24' AS Date), 406, 10, 2, 300, NULL, CAST('2024-06-05T12:00:54.140' AS TIMESTAMP(3)), 'RETIRO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1521, 2, NULL, 347, CAST('2024-05-25' AS Date), 407, 3, 2, 150, NULL, CAST('2024-06-05T12:05:57.113' AS TIMESTAMP(3)), 'COMBUSTIBLE-IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1522, 3, 60, 1, CAST('2024-05-25' AS Date), 407, 3, 1, 150, NULL, CAST('2024-06-05T12:05:57.150' AS TIMESTAMP(3)), 'COMBUSTIBLE-IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1523, 3, 60, 1, CAST('2024-05-25' AS Date), 407, 10, 2, 150, NULL, CAST('2024-06-05T12:05:57.150' AS TIMESTAMP(3)), 'COMBUSTIBLE-IBERIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1524, 2, NULL, 348, CAST('2024-05-23' AS Date), 408, 3, 2, 2600, NULL, CAST('2024-06-05T12:08:15.833' AS TIMESTAMP(3)), 'PAGO CORREDOR-RUFINO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1525, 3, 62, 1, CAST('2024-05-27' AS Date), 408, 15, 1, 2600, NULL, CAST('2024-06-05T12:08:15.850' AS TIMESTAMP(3)), 'PAGO CORREDOR-RUFINO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1526, 3, 62, 1, CAST('2024-05-27' AS Date), 408, 10, 2, 2600, NULL, CAST('2024-06-05T12:08:15.870' AS TIMESTAMP(3)), 'PAGO CORREDOR-RUFINO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1527, 2, NULL, 349, CAST('2024-05-22' AS Date), 409, 3, 2, 2800, NULL, CAST('2024-06-05T12:12:46.277' AS TIMESTAMP(3)), 'PAGO CORREDOR-JESUS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1528, 3, 62, 2, CAST('2024-05-27' AS Date), 409, 15, 1, 2800, NULL, CAST('2024-06-05T12:12:46.313' AS TIMESTAMP(3)), 'PAGO CORREDOR-JESUS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1529, 3, 62, 2, CAST('2024-05-27' AS Date), 409, 10, 2, 2800, NULL, CAST('2024-06-05T12:12:46.313' AS TIMESTAMP(3)), 'PAGO CORREDOR-JESUS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1530, 2, NULL, 350, CAST('2024-05-27' AS Date), 410, 3, 2, 230, NULL, CAST('2024-06-05T12:13:24.707' AS TIMESTAMP(3)), 'CONSUMO-COMIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1531, 3, 62, 3, CAST('2024-05-27' AS Date), 410, 3, 1, 230, NULL, CAST('2024-06-05T12:13:24.920' AS TIMESTAMP(3)), 'CONSUMO-COMIDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1532, 3, 62, 3, CAST('2024-05-27' AS Date), 410, 10, 2, 230, NULL, CAST('2024-06-05T12:13:24.923' AS TIMESTAMP(3)), 'CONSUMO-COMIDA');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1533, 2, NULL, 351, CAST('2024-06-01' AS Date), 411, 3, 2, 150.99, NULL, CAST('2024-06-05T12:33:00.017' AS TIMESTAMP(3)), 'PENSION LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1534, 3, 63, 1, CAST('2024-06-01' AS Date), 411, 3, 1, 150.99, NULL, CAST('2024-06-05T12:33:00.127' AS TIMESTAMP(3)), 'PENSION LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1535, 3, 63, 1, CAST('2024-06-01' AS Date), 411, 5, 2, 150.99, NULL, CAST('2024-06-05T12:33:00.137' AS TIMESTAMP(3)), 'PENSION LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1536, 2, NULL, 352, CAST('2024-06-03' AS Date), 412, 3, 2, 14, NULL, CAST('2024-06-05T12:37:59.953' AS TIMESTAMP(3)), 'PAPEL HIGIENICO - AZUCAR OFICINAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1537, 3, 64, 1, CAST('2024-06-03' AS Date), 412, 3, 1, 14, NULL, CAST('2024-06-05T12:38:00.130' AS TIMESTAMP(3)), 'PAPEL HIGIENICO - AZUCAR OFICINAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1538, 3, 64, 1, CAST('2024-06-03' AS Date), 412, 5, 2, 14, NULL, CAST('2024-06-05T12:38:00.137' AS TIMESTAMP(3)), 'PAPEL HIGIENICO - AZUCAR OFICINAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1539, 2, NULL, 353, CAST('2024-06-04' AS Date), 413, 3, 2, 45, NULL, CAST('2024-06-05T13:11:02.203' AS TIMESTAMP(3)), 'ARCHIVADORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1540, 3, 65, 1, CAST('2024-06-04' AS Date), 413, 3, 1, 45, NULL, CAST('2024-06-05T13:11:02.353' AS TIMESTAMP(3)), 'ARCHIVADORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1541, 3, 65, 1, CAST('2024-06-04' AS Date), 413, 5, 2, 45, NULL, CAST('2024-06-05T13:11:02.360' AS TIMESTAMP(3)), 'ARCHIVADORES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1542, 2, NULL, 354, CAST('2024-06-05' AS Date), 414, 3, 2, 30.9, NULL, CAST('2024-06-05T13:12:42.877' AS TIMESTAMP(3)), 'VIGENCIA PODER - SUNARP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1543, 3, 66, 1, CAST('2024-06-05' AS Date), 414, 3, 1, 30.9, NULL, CAST('2024-06-05T13:12:43.010' AS TIMESTAMP(3)), 'VIGENCIA PODER - SUNARP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1544, 3, 66, 1, CAST('2024-06-05' AS Date), 414, 5, 2, 30.9, NULL, CAST('2024-06-05T13:12:43.020' AS TIMESTAMP(3)), 'VIGENCIA PODER - SUNARP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1545, 1, NULL, 2, CAST('2024-05-03' AS Date), 43, 1, 1, 350, NULL, CAST('2024-06-06T15:49:03.040' AS TIMESTAMP(3)), 'MIS TRES TESOROS-DISCO-');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1546, 1, NULL, 3, CAST('2024-05-03' AS Date), 44, 1, 1, 350, NULL, CAST('2024-06-10T09:21:39.107' AS TIMESTAMP(3)), 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-2.3 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1547, 1, NULL, 4, CAST('2024-05-14' AS Date), 45, 1, 1, 630, NULL, CAST('2024-06-10T09:22:14.920' AS TIMESTAMP(3)), 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-4.3 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1548, 1, NULL, 5, CAST('2024-05-14' AS Date), 46, 1, 1, 210, NULL, CAST('2024-06-10T09:22:46.287' AS TIMESTAMP(3)), 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-1.4 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1549, 1, NULL, 6, CAST('2024-05-14' AS Date), 47, 1, 1, 203, NULL, CAST('2024-06-10T09:23:58.003' AS TIMESTAMP(3)), 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-1.45 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1550, 1, NULL, 7, CAST('2024-05-15' AS Date), 48, 1, 1, 1050, NULL, CAST('2024-06-10T09:24:31.570' AS TIMESTAMP(3)), 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-7.3 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1551, 1, NULL, 8, CAST('2024-05-16' AS Date), 49, 1, 1, 540, NULL, CAST('2024-06-10T09:25:53.147' AS TIMESTAMP(3)), 'TRACTOR 135-ALBERTO NINA-RASTRA-4.3 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1552, 1, NULL, 9, CAST('2024-05-17' AS Date), 50, 1, 1, 350, NULL, CAST('2024-06-10T09:26:33.320' AS TIMESTAMP(3)), 'TRACTOR 135-ALBERTO NINA-ARADO-2.3 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1553, 1, NULL, 10, CAST('2024-05-17' AS Date), 51, 1, 1, 480, NULL, CAST('2024-06-10T09:27:09.423' AS TIMESTAMP(3)), 'TRACTOR 135-GALLEGOS-DISCO-4 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1554, 1, NULL, 11, CAST('2024-05-17' AS Date), 52, 1, 1, 280, NULL, CAST('2024-06-10T09:27:59.370' AS TIMESTAMP(3)), 'TRACTOR 135-NN-ROSADORA-2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1558, 3, 67, 2, CAST('2024-06-06' AS Date), 53, 5, 1, 1000, NULL, CAST('2024-06-10T09:53:51.577' AS TIMESTAMP(3)), 'INGRESO CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1559, 3, 67, 2, CAST('2024-06-06' AS Date), 53, 8, 2, 1000, NULL, CAST('2024-06-10T09:53:51.587' AS TIMESTAMP(3)), 'INGRESO CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1560, 2, NULL, 415, CAST('2024-06-05' AS Date), 415, 3, 2, 306, NULL, CAST('2024-06-10T09:54:30.900' AS TIMESTAMP(3)), 'ELABORACION BROCHURE EMPRESA - PAGO SEGUNDA PARTE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1561, 3, 67, 1, CAST('2024-06-06' AS Date), 415, 3, 1, 306, NULL, CAST('2024-06-10T09:54:31.780' AS TIMESTAMP(3)), 'ELABORACION BROCHURE EMPRESA - PAGO SEGUNDA PARTE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1562, 3, 67, 1, CAST('2024-06-06' AS Date), 415, 5, 2, 306, NULL, CAST('2024-06-10T09:54:31.790' AS TIMESTAMP(3)), 'ELABORACION BROCHURE EMPRESA - PAGO SEGUNDA PARTE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1563, 1, NULL, 12, CAST('2024-05-18' AS Date), 54, 1, 1, 315, NULL, CAST('2024-06-10T10:09:54.100' AS TIMESTAMP(3)), 'TRACTOR 135-JOSE MARIA-LAMPON-2.25 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1564, 1, NULL, 13, CAST('2024-05-18' AS Date), 55, 1, 1, 455, NULL, CAST('2024-06-10T10:10:35.800' AS TIMESTAMP(3)), 'TRACTOR 135-ESTEBAN-ROSADORA-3.25 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1565, 1, NULL, 14, CAST('2024-05-18' AS Date), 56, 1, 1, 120, NULL, CAST('2024-06-10T10:11:00.790' AS TIMESTAMP(3)), 'TRACTOR 135-PEDRO TORREBLANCA-DISCO-1 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1566, 1, NULL, 15, CAST('2024-05-19' AS Date), 57, 1, 1, 416, NULL, CAST('2024-06-10T10:11:42.350' AS TIMESTAMP(3)), 'TRACTOR 135-ELVIS GALLEGOS-DISCO-3.2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1567, 1, NULL, 16, CAST('2024-05-19' AS Date), 58, 1, 1, 420, NULL, CAST('2024-06-10T10:12:52.710' AS TIMESTAMP(3)), 'TRACTOR 135-RAFO CALIZAYA-RASTRA-3.4 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1568, 1, NULL, 17, CAST('2024-05-20' AS Date), 59, 1, 1, 600, NULL, CAST('2024-06-10T10:13:37.723' AS TIMESTAMP(3)), 'TRACTOR 135-RAFO CALIZAYA-RASTRA-5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1569, 1, NULL, 18, CAST('2024-05-20' AS Date), 60, 1, 1, 611, NULL, CAST('2024-06-10T10:14:03.903' AS TIMESTAMP(3)), 'TRACTOR 135-RUTH TOTORA-RASTRA-4.7 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1570, 1, NULL, 19, CAST('2024-05-21' AS Date), 61, 1, 1, 240, NULL, CAST('2024-06-10T10:16:53.993' AS TIMESTAMP(3)), 'TRACTOR 135-ESTEBAN-ROSADORA-2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1571, 1, NULL, 20, CAST('2024-05-21' AS Date), 62, 1, 1, 715, NULL, CAST('2024-06-10T10:22:59.510' AS TIMESTAMP(3)), 'TRACTOR 135-FELIX FERNANDEZ-ROSADORA-5.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1572, 1, NULL, 21, CAST('2024-05-21' AS Date), 63, 1, 1, 65, NULL, CAST('2024-06-10T10:23:37.037' AS TIMESTAMP(3)), 'TRACTOR 135-MACHACA-ROSADORA-0.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1573, 1, NULL, 22, CAST('2024-05-22' AS Date), 64, 1, 1, 280, NULL, CAST('2024-06-10T10:24:00.040' AS TIMESTAMP(3)), 'TRACTOR 135-RUTH TOTORA-ROSADORA-2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1574, 1, NULL, 23, CAST('2024-05-22' AS Date), 65, 1, 1, 210, NULL, CAST('2024-06-10T10:24:32.003' AS TIMESTAMP(3)), 'TRACTOR 135-ELVIS GALLEGOZ SEVILLANO-DISCO-1.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1575, 1, NULL, 24, CAST('2024-05-22' AS Date), 66, 1, 1, 140, NULL, CAST('2024-06-10T10:25:06.253' AS TIMESTAMP(3)), 'TRACTOR 135-RAFAEL-RASTRA-1 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1576, 1, NULL, 25, CAST('2024-05-22' AS Date), 67, 1, 1, 227.5, NULL, CAST('2024-06-10T10:25:37.940' AS TIMESTAMP(3)), 'TRACTOR 135-RAFAEL-RASTRA-1.75 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1577, 1, NULL, 26, CAST('2024-05-23' AS Date), 68, 1, 1, 260, NULL, CAST('2024-06-10T10:26:03.697' AS TIMESTAMP(3)), 'TRACTOR 135-TOMAS-ROSADORA-2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1578, 1, NULL, 27, CAST('2024-05-23' AS Date), 69, 1, 1, 260, NULL, CAST('2024-06-10T10:26:47.037' AS TIMESTAMP(3)), 'TRACTOR 135-VALENCIA-ROSADORA-2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1579, 1, NULL, 28, CAST('2024-05-24' AS Date), 70, 1, 1, 845, NULL, CAST('2024-06-10T10:27:12.073' AS TIMESTAMP(3)), 'TRACTOR 135-NESTOR-RASTRA-6.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1580, 1, NULL, 29, CAST('2024-05-24' AS Date), 71, 1, 1, 416, NULL, CAST('2024-06-10T10:27:40.437' AS TIMESTAMP(3)), 'TRACTOR 135-LORENZO-RASTRA-3.2 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1581, 1, NULL, 30, CAST('2024-05-26' AS Date), 72, 1, 1, 481, NULL, CAST('2024-06-10T10:28:17.583' AS TIMESTAMP(3)), 'TRACTOR 135-SUNISO-DISCO-3.7 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1582, 1, NULL, 31, CAST('2024-05-28' AS Date), 73, 1, 1, 1190, NULL, CAST('2024-06-10T10:28:47.230' AS TIMESTAMP(3)), 'TRACTOR 135-ELVIS GALLEGOS HIJO-DISCO-8.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1583, 1, NULL, 32, CAST('2024-05-29' AS Date), 74, 1, 1, 585, NULL, CAST('2024-06-10T10:29:18.980' AS TIMESTAMP(3)), 'TRACTOR 135-JOSE JULIAN MAMANI-DISCO-4.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1584, 1, NULL, 33, CAST('2024-06-02' AS Date), 75, 1, 1, 1470, NULL, CAST('2024-06-10T10:29:48.710' AS TIMESTAMP(3)), 'TRACTOR 135-ROBERTO-RASTRA-10.5 HORAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1585, 2, NULL, 416, CAST('2024-06-10' AS Date), 416, 3, 2, 300, NULL, CAST('2024-06-10T12:26:48.967' AS TIMESTAMP(3)), 'PASAJES MILUSKA - MAYO (25 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1586, 3, 68, 1, CAST('2024-06-10' AS Date), 416, 3, 1, 300, NULL, CAST('2024-06-10T12:26:49.087' AS TIMESTAMP(3)), 'PASAJES MILUSKA - MAYO (25 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1587, 3, 68, 1, CAST('2024-06-10' AS Date), 416, 5, 2, 300, NULL, CAST('2024-06-10T12:26:49.097' AS TIMESTAMP(3)), 'PASAJES MILUSKA - MAYO (25 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1588, 2, NULL, 417, CAST('2024-06-10' AS Date), 417, 3, 2, 8, NULL, CAST('2024-06-10T14:07:21.833' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1589, 3, 68, 2, CAST('2024-06-10' AS Date), 417, 3, 1, 8, NULL, CAST('2024-06-10T14:07:21.883' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1590, 3, 68, 2, CAST('2024-06-10' AS Date), 417, 5, 2, 8, NULL, CAST('2024-06-10T14:07:21.890' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1591, 2, NULL, 418, CAST('1024-06-10' AS Date), 418, 3, 2, 8, NULL, CAST('2024-06-10T14:09:05.740' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1592, 3, 68, 3, CAST('2024-06-10' AS Date), 418, 3, 1, 8, NULL, CAST('2024-06-10T14:09:05.797' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1593, 3, 68, 3, CAST('2024-06-10' AS Date), 418, 5, 2, 8, NULL, CAST('2024-06-10T14:09:05.803' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1597, 2, NULL, 419, CAST('2024-06-10' AS Date), 419, 3, 2, 147, NULL, CAST('2024-06-10T14:25:09.787' AS TIMESTAMP(3)), 'PAGO DESAYUNO LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1598, 3, 68, 4, CAST('2024-06-10' AS Date), 419, 3, 1, 147, NULL, CAST('2024-06-10T14:25:09.810' AS TIMESTAMP(3)), 'PAGO DESAYUNO LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1599, 3, 68, 4, CAST('2024-06-10' AS Date), 419, 5, 2, 147, NULL, CAST('2024-06-10T14:25:09.820' AS TIMESTAMP(3)), 'PAGO DESAYUNO LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1600, 2, NULL, 420, CAST('2024-06-10' AS Date), 420, 3, 2, 147, NULL, CAST('2024-06-10T14:26:18.083' AS TIMESTAMP(3)), 'PAGO CENA LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1601, 3, 68, 5, CAST('2024-06-10' AS Date), 420, 3, 1, 147, NULL, CAST('2024-06-10T14:26:18.170' AS TIMESTAMP(3)), 'PAGO CENA LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1602, 3, 68, 5, CAST('2024-06-10' AS Date), 420, 5, 2, 147, NULL, CAST('2024-06-10T14:26:18.180' AS TIMESTAMP(3)), 'PAGO CENA LEYDA 14/05/2024 - 06/06/2024 (21 DIAS)');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1603, 2, NULL, 421, CAST('2024-05-31' AS Date), 421, 3, 2, 12, NULL, CAST('2024-06-10T14:30:39.267' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1604, 3, 55, 2, CAST('2024-05-31' AS Date), 421, 3, 1, 12, NULL, CAST('2024-06-10T14:30:39.307' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1605, 3, 55, 2, CAST('2024-05-31' AS Date), 421, 5, 2, 12, NULL, CAST('2024-06-10T14:30:39.313' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1606, 1, NULL, 34, CAST('2024-03-08' AS Date), 76, 1, 1, 1132.8, NULL, CAST('2024-06-10T16:51:28.870' AS TIMESTAMP(3)), 'RETROEXCAVADORA-MIS TRES TESOROS EIRL-DISCO-8 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1607, 1, NULL, 35, CAST('2024-03-09' AS Date), 77, 1, 1, 1203.6, NULL, CAST('2024-06-10T16:52:03.250' AS TIMESTAMP(3)), 'RETROEXCAVADORA-MIS TRES TESOROS EIRL-DISCO-8.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1608, 1, NULL, 36, CAST('2024-03-13' AS Date), 78, 1, 1, 260, NULL, CAST('2024-06-10T16:52:29.730' AS TIMESTAMP(3)), 'RETROEXCAVADORA-NN-ROSADORA-2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1609, 1, NULL, 37, CAST('2024-03-14' AS Date), 79, 1, 1, 195, NULL, CAST('2024-06-10T16:52:55.363' AS TIMESTAMP(3)), 'RETROEXCAVADORA-PEDRO TORREBLANCA-DISCO-1.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1610, 1, NULL, 38, CAST('2024-04-26' AS Date), 80, 1, 1, 65, NULL, CAST('2024-06-10T16:53:18.103' AS TIMESTAMP(3)), 'RETROEXCAVADORA-ELVIS GALLEGOZ SEVILLANO-DISCO-0.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1611, 1, NULL, 39, CAST('2024-04-27' AS Date), 81, 1, 1, 845, NULL, CAST('2024-06-10T16:53:45.833' AS TIMESTAMP(3)), 'RETROEXCAVADORA-RAFAEL-RASTRA-6.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1612, 1, NULL, 40, CAST('2024-05-03' AS Date), 82, 1, 1, 227.5, NULL, CAST('2024-06-10T17:00:10.123' AS TIMESTAMP(3)), 'RETROEXCAVADORA-LORENZO-RASTRA-2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1613, 1, NULL, 41, CAST('2024-05-06' AS Date), 83, 1, 1, 585, NULL, CAST('2024-06-10T17:00:38.753' AS TIMESTAMP(3)), 'RETROEXCAVADORA-SUNISO-DISCO-4.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1614, 1, NULL, 42, CAST('2024-05-13' AS Date), 84, 1, 1, 25, NULL, CAST('2024-06-10T17:01:01.500' AS TIMESTAMP(3)), 'RETROEXCAVADORA-RAFAEL-RASTRA-2.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1615, 1, NULL, 43, CAST('2024-05-18' AS Date), 85, 1, 1, 130, NULL, CAST('2024-06-10T17:01:22.560' AS TIMESTAMP(3)), 'RETROEXCAVADORA-NESTOR-RASTRA-1 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1616, 1, NULL, 44, CAST('2024-05-20' AS Date), 86, 1, 1, 520, NULL, CAST('2024-06-10T17:01:46.213' AS TIMESTAMP(3)), 'RETROEXCAVADORA-VALENCIA-DISCO-4 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1617, 1, NULL, 45, CAST('2024-05-23' AS Date), 87, 1, 1, 170, NULL, CAST('2024-06-10T17:03:24.777' AS TIMESTAMP(3)), 'RETROEXCAVADORA-VALENCIA-DISCO-1.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1618, 1, NULL, 46, CAST('2024-05-23' AS Date), 88, 1, 1, 195, NULL, CAST('2024-06-10T17:03:55.137' AS TIMESTAMP(3)), 'RETROEXCAVADORA-JOSE PAREDES-RASTRA-1.3 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1619, 1, NULL, 47, CAST('2024-05-24' AS Date), 89, 1, 1, 280, NULL, CAST('2024-06-10T17:04:59.557' AS TIMESTAMP(3)), 'RETROEXCAVADORA-VIDAL VARGAS-DISCO-2.1 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1620, 1, NULL, 48, CAST('2024-05-24' AS Date), 90, 1, 1, 169, NULL, CAST('2024-06-10T17:05:22.860' AS TIMESTAMP(3)), 'RETROEXCAVADORA-VIDAL VARGAS-DISCO-1.3 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1621, 1, NULL, 49, CAST('2024-05-28' AS Date), 91, 1, 1, 1222, NULL, CAST('2024-06-10T17:07:38.357' AS TIMESTAMP(3)), 'RETROEXCAVADORA-RAFAEL-DISCO-9.4 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1622, 1, NULL, 50, CAST('2024-05-29' AS Date), 92, 1, 1, 325, NULL, CAST('2024-06-10T17:14:43.370' AS TIMESTAMP(3)), 'RETROEXCAVADORA-RAFAEL-DISCO-2.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1623, 1, NULL, 51, CAST('2024-05-30' AS Date), 93, 1, 1, 260, NULL, CAST('2024-06-10T17:15:08.720' AS TIMESTAMP(3)), 'RETROEXCAVADORA-MARUJA-RASTRA-2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1624, 1, NULL, 52, CAST('2024-06-03' AS Date), 94, 1, 1, 455, NULL, CAST('2024-06-10T17:15:34.457' AS TIMESTAMP(3)), 'RETROEXCAVADORA-JOSE PAREDES-RASTRA-3.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1625, 1, NULL, 53, CAST('2024-06-03' AS Date), 95, 1, 1, 585, NULL, CAST('2024-06-10T17:16:12.560' AS TIMESTAMP(3)), 'RETROEXCAVADORA-ADAN-RASTRA-4.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1626, 1, NULL, 54, CAST('2024-03-05' AS Date), 96, 1, 1, 660, NULL, CAST('2024-06-10T17:31:53.493' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE MARIA-RASTRA-8 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1627, 1, NULL, 55, CAST('2024-03-13' AS Date), 97, 1, 1, 340, NULL, CAST('2024-06-10T17:32:25.167' AS TIMESTAMP(3)), 'TRACTOR 110-MIS TRES TESOROS EIRL-DISCO-11 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1628, 1, NULL, 56, CAST('2024-03-14' AS Date), 98, 1, 1, 660, NULL, CAST('2024-06-10T17:32:59.623' AS TIMESTAMP(3)), 'TRACTOR 110-MIS TRES TESOROS EIRL-DISCO-5.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1629, 1, NULL, 57, CAST('2024-04-22' AS Date), 99, 1, 1, 240, NULL, CAST('2024-06-10T17:33:24.290' AS TIMESTAMP(3)), 'TRACTOR 110-ALBERTO NINA-RASTRA-2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1630, 1, NULL, 58, CAST('2024-04-28' AS Date), 100, 1, 1, 120, NULL, CAST('2024-06-10T17:34:22.147' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE MARIA-LAMPON-1 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1631, 1, NULL, 59, CAST('2024-04-30' AS Date), 101, 1, 1, 520, NULL, CAST('2024-06-10T17:34:48.453' AS TIMESTAMP(3)), 'TRACTOR 110-ELVIS GALLEGOS-DISCO-4 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1632, 1, NULL, 60, CAST('2024-05-01' AS Date), 102, 1, 1, 845, NULL, CAST('2024-06-10T17:35:11.657' AS TIMESTAMP(3)), 'TRACTOR 110-ELVIS GALLEGOS-DISCO-6.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1633, 1, NULL, 61, CAST('2024-05-01' AS Date), 103, 1, 1, 60, NULL, CAST('2024-06-10T17:35:41.470' AS TIMESTAMP(3)), 'TRACTOR 110-RUTH TOTORA-RASTRA-0.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1634, 1, NULL, 62, CAST('2024-05-02' AS Date), 104, 1, 1, 240, NULL, CAST('2024-06-10T17:36:07.403' AS TIMESTAMP(3)), 'TRACTOR 110-FELIX FERNANDEZ-ROSADORA- HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1635, 1, NULL, 63, CAST('2024-05-03' AS Date), 105, 1, 1, 412.5, NULL, CAST('2024-06-10T17:37:58.513' AS TIMESTAMP(3)), 'TRACTOR 110-RUTH TOTORA-ROSADORA- HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1636, 1, NULL, 64, CAST('2024-05-06' AS Date), 106, 1, 1, 300, NULL, CAST('2024-06-10T17:38:36.840' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-RASTRA-2.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1637, 1, NULL, 65, CAST('2024-05-06' AS Date), 107, 1, 1, 180, NULL, CAST('2024-06-10T17:39:06.720' AS TIMESTAMP(3)), 'TRACTOR 110-LORENZO-RASTRA-1.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1638, 1, NULL, 66, CAST('2024-05-10' AS Date), 108, 1, 1, 715, NULL, CAST('2024-06-10T17:39:34.957' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-DISCO-5.5 HORAS MAQUINA');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1639, 1, NULL, 67, CAST('2024-05-10' AS Date), 109, 1, 1, 715, NULL, CAST('2024-06-10T17:39:59.160' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-DISCO-5.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1640, 1, NULL, 68, CAST('2024-05-11' AS Date), 110, 1, 1, 180, NULL, CAST('2024-06-10T17:42:23.983' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-RASTRA-1.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1641, 1, NULL, 69, CAST('2024-05-11' AS Date), 111, 1, 1, 180, NULL, CAST('2024-06-10T17:42:52.520' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-RASTRA-1.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1642, 1, NULL, 70, CAST('2024-05-14' AS Date), 112, 1, 1, 120, NULL, CAST('2024-06-10T17:43:15.743' AS TIMESTAMP(3)), 'TRACTOR 110-VIDAL VARGAS-LAMPON-1 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1643, 1, NULL, 71, CAST('2024-05-17' AS Date), 113, 1, 1, 150, NULL, CAST('2024-06-10T17:43:37.257' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-RASTRA-1.25 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1644, 1, NULL, 72, CAST('2024-05-17' AS Date), 114, 1, 1, 420, NULL, CAST('2024-06-10T17:44:07.470' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE PAREDES-RASTRA-3.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1645, 1, NULL, 73, CAST('2024-05-17' AS Date), 115, 1, 1, 487.5, NULL, CAST('2024-06-10T17:44:44.567' AS TIMESTAMP(3)), 'TRACTOR 110-VIDAL VARGAS-ARADO-3.75 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1646, 1, NULL, 74, CAST('2024-05-18' AS Date), 116, 1, 1, 1550, NULL, CAST('2024-06-10T17:45:11.760' AS TIMESTAMP(3)), 'TRACTOR 110-VIDAL VARGAS-DISCO-12 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1647, 1, NULL, 75, CAST('2024-05-19' AS Date), 117, 1, 1, 650, NULL, CAST('2024-06-10T17:45:34.267' AS TIMESTAMP(3)), 'TRACTOR 110-VIDAL VARGAS-DISCO-5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1648, 1, NULL, 76, CAST('2024-05-19' AS Date), 118, 1, 1, 975, NULL, CAST('2024-06-10T17:46:04.123' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE PAREDES-DISCO-8.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1649, 1, NULL, 77, CAST('2024-05-20' AS Date), 119, 1, 1, 1430, NULL, CAST('2024-06-10T17:46:25.847' AS TIMESTAMP(3)), '20/05/2024	1430.00	TRACTOR 110-RAFAEL-DISCO-11 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1650, 1, NULL, 78, CAST('2024-05-21' AS Date), 120, 1, 1, 520, NULL, CAST('2024-06-10T18:00:12.490' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-DISCO-4 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1651, 1, NULL, 79, CAST('2024-05-21' AS Date), 121, 1, 1, 540, NULL, CAST('2024-06-10T18:02:14.027' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE SEHUIN-ROSADORA-4.45 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1652, 1, NULL, 80, CAST('2024-05-21' AS Date), 122, 1, 1, 409, NULL, CAST('2024-06-10T18:02:40.760' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-DISCO-3.25 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1653, 1, NULL, 81, CAST('2024-05-22' AS Date), 123, 1, 1, 1820, NULL, CAST('2024-06-10T18:03:04.697' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-DISCO-14 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1654, 1, NULL, 82, CAST('2024-05-23' AS Date), 124, 1, 1, 455, NULL, CAST('2024-06-10T18:03:28.897' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-DISCO-3.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1655, 1, NULL, 83, CAST('2024-05-23' AS Date), 125, 1, 1, 980, NULL, CAST('2024-06-10T18:03:55.603' AS TIMESTAMP(3)), 'TRACTOR 110-VIDAL VARGAS-DISCO-7.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1656, 1, NULL, 84, CAST('2024-05-24' AS Date), 126, 1, 1, 650, NULL, CAST('2024-06-10T18:04:18.620' AS TIMESTAMP(3)), 'TRACTOR 110-VIDAL VARGAS-DISCO-5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1657, 1, NULL, 85, CAST('2024-05-24' AS Date), 127, 1, 1, 540, NULL, CAST('2024-06-10T18:04:47.740' AS TIMESTAMP(3)), 'TRACTOR 110-MARUJA-RASTRA-4.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1658, 1, NULL, 86, CAST('2024-05-24' AS Date), 128, 1, 1, 330, NULL, CAST('2024-06-10T18:05:12.723' AS TIMESTAMP(3)), 'TRACTOR 110-NELY SONCCO-RASTRA-2.75 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1659, 1, NULL, 87, CAST('2024-05-25' AS Date), 129, 1, 1, 480, NULL, CAST('2024-06-10T18:05:38.703' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-RASTRA-4 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1660, 1, NULL, 88, CAST('2024-05-25' AS Date), 130, 1, 1, 564, NULL, CAST('2024-06-10T18:06:01.190' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE SEHUIN-RASTRA-4.7 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1661, 1, NULL, 89, CAST('2024-05-26' AS Date), 131, 1, 1, 360, NULL, CAST('2024-06-10T18:06:27.077' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE PAREDES-RASTRA-3 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1662, 1, NULL, 90, CAST('2024-05-26' AS Date), 132, 1, 1, 390, NULL, CAST('2024-06-10T18:06:51.960' AS TIMESTAMP(3)), 'TRACTOR 110-ADAN-RASTRA-3.25 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1663, 1, NULL, 91, CAST('2024-05-27' AS Date), 133, 1, 1, 720, NULL, CAST('2024-06-10T18:07:14.617' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-RASTRA-6 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1664, 1, NULL, 92, CAST('2024-05-28' AS Date), 134, 1, 1, 617.5, NULL, CAST('2024-06-10T18:07:41.980' AS TIMESTAMP(3)), 'TRACTOR 110-ELVIS HIJO-DISCO-4.75 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1665, 1, NULL, 93, CAST('2024-05-29' AS Date), 135, 1, 1, 806, NULL, CAST('2024-06-10T18:08:25.393' AS TIMESTAMP(3)), 'TRACTOR 110-ÑATO CONDORI-DISCO-6.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1666, 1, NULL, 94, CAST('2024-05-29' AS Date), 136, 1, 1, 416, NULL, CAST('2024-06-10T18:09:15.903' AS TIMESTAMP(3)), 'TRACTOR 110-ÑATO CONDORI-DISCO-3.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1667, 1, NULL, 95, CAST('2024-05-30' AS Date), 137, 1, 1, 780, NULL, CAST('2024-06-10T18:09:40.937' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE PAREDES-DISCO-6 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1668, 1, NULL, 96, CAST('2024-05-30' AS Date), 138, 1, 1, 245, NULL, CAST('2024-06-10T18:10:05.270' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-DISCO-1.75 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1669, 1, NULL, 97, CAST('2024-05-30' AS Date), 139, 1, 1, 180, NULL, CAST('2024-06-10T18:10:28.630' AS TIMESTAMP(3)), 'TRACTOR 110-NESTOR-RASTRA-1.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1670, 1, NULL, 98, CAST('2024-05-31' AS Date), 140, 1, 1, 1140, NULL, CAST('2024-06-10T18:10:55.610' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-RASTRA-9.5 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1671, 1, NULL, 99, CAST('2024-05-31' AS Date), 141, 1, 1, 240, NULL, CAST('2024-06-10T18:11:21.337' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL-RASTRA-2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1672, 1, NULL, 100, CAST('2024-06-01' AS Date), 142, 1, 1, 504, NULL, CAST('2024-06-10T18:12:39.427' AS TIMESTAMP(3)), 'TRACTOR 110-MARUJA-RASTRA-4.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1673, 1, NULL, 101, CAST('2024-06-01' AS Date), 143, 1, 1, 360, NULL, CAST('2024-06-10T18:13:02.253' AS TIMESTAMP(3)), 'TRACTOR 110-NELY SONCCO-RASTRA-3 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1674, 1, NULL, 102, CAST('2024-06-01' AS Date), 144, 1, 1, 240, NULL, CAST('2024-06-10T18:13:28.867' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE SEHUIN-RASTRA-2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1675, 1, NULL, 103, CAST('2024-06-02' AS Date), 145, 1, 1, 611, NULL, CAST('2024-06-10T18:14:05.597' AS TIMESTAMP(3)), 'TRACTOR 110--DISCO-4.7 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1676, 1, NULL, 104, CAST('2024-06-02' AS Date), 146, 1, 1, 351, NULL, CAST('2024-06-10T18:14:30.570' AS TIMESTAMP(3)), 'TRACTOR 110-AUGUSTO HUAYAPA-DISCO-2.7 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1677, 1, NULL, 105, CAST('2024-06-03' AS Date), 147, 1, 1, 286, NULL, CAST('2024-06-10T18:15:49.013' AS TIMESTAMP(3)), 'TRACTOR 110-RAFAEL--2.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1678, 1, NULL, 106, CAST('2024-06-03' AS Date), 148, 1, 1, 264, NULL, CAST('2024-06-10T18:16:15.580' AS TIMESTAMP(3)), 'TRACTOR 110-JOSE SEHUIN-LAMPON-2.2 HORAS MAQUINA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1679, 2, NULL, 422, CAST('2024-06-10' AS Date), 422, 3, 2, 6, NULL, CAST('2024-06-11T10:27:25.800' AS TIMESTAMP(3)), 'PAGO MOVILIDAD COCACHACRA - MARK DETRACCIONES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1680, 3, 69, 1, CAST('2024-06-11' AS Date), 422, 3, 1, 6, NULL, CAST('2024-06-11T10:27:25.880' AS TIMESTAMP(3)), 'PAGO MOVILIDAD COCACHACRA - MARK DETRACCIONES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1681, 3, 69, 1, CAST('2024-06-11' AS Date), 422, 5, 2, 6, NULL, CAST('2024-06-11T10:27:25.887' AS TIMESTAMP(3)), 'PAGO MOVILIDAD COCACHACRA - MARK DETRACCIONES');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1682, 2, NULL, 423, CAST('2024-06-11' AS Date), 423, 3, 2, 8, NULL, CAST('2024-06-11T10:28:37.520' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1683, 3, 69, 2, CAST('2024-06-11' AS Date), 423, 3, 1, 8, NULL, CAST('2024-06-11T10:28:37.603' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1684, 3, 69, 2, CAST('2024-06-11' AS Date), 423, 5, 2, 8, NULL, CAST('2024-06-11T10:28:37.610' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1685, 3, 68, 6, CAST('2024-06-10' AS Date), 149, 5, 1, 500, NULL, CAST('2024-06-11T10:31:30.127' AS TIMESTAMP(3)), 'INGRESO CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1686, 3, 68, 6, CAST('2024-06-10' AS Date), 149, 8, 2, 500, NULL, CAST('2024-06-11T10:31:30.133' AS TIMESTAMP(3)), 'INGRESO CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1687, 2, NULL, 424, CAST('2024-06-11' AS Date), 424, 3, 2, 8, NULL, CAST('2024-06-12T09:33:08.520' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1688, 3, 69, 3, CAST('2024-06-11' AS Date), 424, 3, 1, 8, NULL, CAST('2024-06-12T09:33:08.610' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1689, 3, 69, 3, CAST('2024-06-11' AS Date), 424, 5, 2, 8, NULL, CAST('2024-06-12T09:33:08.617' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1690, 2, NULL, 425, CAST('2024-06-10' AS Date), 425, 3, 2, 8, NULL, CAST('2024-06-12T09:34:22.393' AS TIMESTAMP(3)), 'BOLSAS DE BASURA X100 UND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1691, 3, 68, 7, CAST('2024-06-10' AS Date), 425, 3, 1, 8, NULL, CAST('2024-06-12T09:34:22.447' AS TIMESTAMP(3)), 'BOLSAS DE BASURA X100 UND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1692, 3, 68, 7, CAST('2024-06-10' AS Date), 425, 5, 2, 8, NULL, CAST('2024-06-12T09:34:22.453' AS TIMESTAMP(3)), 'BOLSAS DE BASURA X100 UND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1693, 2, NULL, 426, CAST('2024-06-10' AS Date), 426, 3, 2, 7.1, NULL, CAST('2024-06-12T09:35:44.833' AS TIMESTAMP(3)), 'LEJIA + LIMPIATODO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1694, 3, 68, 8, CAST('2024-06-10' AS Date), 426, 3, 1, 7.1, NULL, CAST('2024-06-12T09:35:44.913' AS TIMESTAMP(3)), 'LEJIA + LIMPIATODO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1695, 3, 68, 8, CAST('2024-06-10' AS Date), 426, 5, 2, 7.1, NULL, CAST('2024-06-12T09:35:44.923' AS TIMESTAMP(3)), 'LEJIA + LIMPIATODO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1696, 2, NULL, 427, CAST('2024-06-12' AS Date), 427, 3, 2, 8, NULL, CAST('2024-06-12T09:36:58.880' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1697, 3, 70, 1, CAST('2024-06-12' AS Date), 427, 3, 1, 8, NULL, CAST('2024-06-12T09:36:58.937' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1698, 3, 70, 1, CAST('2024-06-12' AS Date), 427, 5, 2, 8, NULL, CAST('2024-06-12T09:36:58.960' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1705, 2, NULL, 429, CAST('2024-06-12' AS Date), 429, 3, 2, 7.5, NULL, CAST('2024-06-12T09:40:08.313' AS TIMESTAMP(3)), 'PAPEL HIGIENICO X6 UND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1706, 3, 70, 2, CAST('2024-06-12' AS Date), 429, 3, 1, 7.5, NULL, CAST('2024-06-12T09:40:08.390' AS TIMESTAMP(3)), 'PAPEL HIGIENICO X6 UND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1707, 3, 70, 2, CAST('2024-06-12' AS Date), 429, 5, 2, 7.5, NULL, CAST('2024-06-12T09:40:08.400' AS TIMESTAMP(3)), 'PAPEL HIGIENICO X6 UND');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1708, 2, NULL, 428, CAST('2024-06-11' AS Date), 428, 3, 2, 12, NULL, CAST('2024-06-12T11:30:36.463' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1709, 3, 69, 4, CAST('2024-06-11' AS Date), 428, 3, 1, 12, NULL, CAST('2024-06-12T11:30:36.500' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1710, 3, 69, 4, CAST('2024-06-11' AS Date), 428, 5, 2, 12, NULL, CAST('2024-06-12T11:30:36.510' AS TIMESTAMP(3)), 'MENU LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1717, 2, NULL, 430, CAST('2024-06-12' AS Date), 430, 3, 2, 50, NULL, CAST('2024-06-12T15:53:45.080' AS TIMESTAMP(3)), 'COMBUSTIBLE MIRLESS - FIRMA DDJJ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1718, 3, 70, 3, CAST('2024-06-12' AS Date), 430, 3, 1, 50, NULL, CAST('2024-06-12T15:53:45.113' AS TIMESTAMP(3)), 'COMBUSTIBLE MIRLESS - FIRMA DDJJ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1719, 3, 70, 3, CAST('2024-06-12' AS Date), 430, 5, 2, 50, NULL, CAST('2024-06-12T15:53:45.120' AS TIMESTAMP(3)), 'COMBUSTIBLE MIRLESS - FIRMA DDJJ');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1720, 2, NULL, 431, CAST('2024-04-03' AS Date), 431, 3, 2, 12.3, NULL, CAST('2024-06-13T08:37:51.667' AS TIMESTAMP(3)), 'PAGO SERVICIO DE AGUA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1721, 3, 70, 4, CAST('2024-06-12' AS Date), 431, 3, 1, 12.3, NULL, CAST('2024-06-13T08:37:51.733' AS TIMESTAMP(3)), 'PAGO SERVICIO DE AGUA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1722, 3, 70, 4, CAST('2024-06-12' AS Date), 431, 5, 2, 12.3, NULL, CAST('2024-06-13T08:37:51.760' AS TIMESTAMP(3)), 'PAGO SERVICIO DE AGUA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1723, 2, NULL, 432, CAST('2024-06-12' AS Date), 432, 3, 2, 10, NULL, CAST('2024-06-13T08:38:32.707' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1724, 3, 70, 5, CAST('2024-06-12' AS Date), 432, 3, 1, 10, NULL, CAST('2024-06-13T08:38:32.793' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1725, 3, 70, 5, CAST('2024-06-12' AS Date), 432, 5, 2, 10, NULL, CAST('2024-06-13T08:38:32.817' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1726, 2, NULL, 433, CAST('2024-06-13' AS Date), 433, 3, 2, 8, NULL, CAST('2024-06-13T08:39:44.770' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1727, 3, 71, 1, CAST('2024-06-13' AS Date), 433, 3, 1, 8, NULL, CAST('2024-06-13T08:39:44.873' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1728, 3, 71, 1, CAST('2024-06-13' AS Date), 433, 5, 2, 8, NULL, CAST('2024-06-13T08:39:44.883' AS TIMESTAMP(3)), 'DESAYUNO LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1729, 2, NULL, 434, CAST('2024-05-01' AS Date), 434, 3, 2, 0.3, NULL, CAST('2024-06-13T15:06:45.463' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1730, 3, 32, 3, CAST('2024-05-01' AS Date), 434, 3, 1, 0.3, NULL, CAST('2024-06-13T15:06:45.503' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1731, 3, 32, 3, CAST('2024-05-01' AS Date), 434, 10, 2, 0.3, NULL, CAST('2024-06-13T15:06:45.503' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1732, 2, NULL, 435, CAST('2024-05-01' AS Date), 435, 3, 2, 37.8, NULL, CAST('2024-06-13T15:11:32.647' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1733, 3, 32, 4, CAST('2024-05-01' AS Date), 435, 3, 1, 37.8, NULL, CAST('2024-06-13T15:11:32.707' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1734, 3, 32, 4, CAST('2024-05-01' AS Date), 435, 10, 2, 37.8, NULL, CAST('2024-06-13T15:11:32.710' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1735, 2, NULL, 436, CAST('2024-05-02' AS Date), 436, 3, 2, 24, NULL, CAST('2024-06-13T15:13:18.497' AS TIMESTAMP(3)), 'COMISION EXCESO DE MOVIMIENTOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1736, 3, 33, 8, CAST('2024-05-02' AS Date), 436, 3, 1, 24, NULL, CAST('2024-06-13T15:13:18.570' AS TIMESTAMP(3)), 'COMISION EXCESO DE MOVIMIENTOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1737, 3, 33, 8, CAST('2024-05-02' AS Date), 436, 10, 2, 24, NULL, CAST('2024-06-13T15:13:18.573' AS TIMESTAMP(3)), 'COMISION EXCESO DE MOVIMIENTOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1738, 2, NULL, 437, CAST('2024-05-02' AS Date), 437, 3, 2, 43.9, NULL, CAST('2024-06-13T15:13:54.010' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1739, 3, 33, 9, CAST('2024-05-02' AS Date), 437, 3, 1, 43.9, NULL, CAST('2024-06-13T15:13:54.080' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1740, 3, 33, 9, CAST('2024-05-02' AS Date), 437, 10, 2, 43.9, NULL, CAST('2024-06-13T15:13:54.083' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1741, 2, NULL, 438, CAST('2024-05-02' AS Date), 438, 3, 2, 0.65, NULL, CAST('2024-06-13T15:14:27.600' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1742, 3, 33, 10, CAST('2024-05-02' AS Date), 438, 3, 1, 0.65, NULL, CAST('2024-06-13T15:14:27.613' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1743, 3, 33, 10, CAST('2024-05-02' AS Date), 438, 10, 2, 0.65, NULL, CAST('2024-06-13T15:14:27.613' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1744, 2, NULL, 439, CAST('2024-05-03' AS Date), 439, 3, 2, 35.38, NULL, CAST('2024-06-13T15:16:20.247' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1745, 3, 34, 8, CAST('2024-05-03' AS Date), 439, 3, 1, 35.38, NULL, CAST('2024-06-13T15:16:20.293' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1746, 3, 34, 8, CAST('2024-05-03' AS Date), 439, 10, 2, 35.38, NULL, CAST('2024-06-13T15:16:20.293' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1747, 2, NULL, 440, CAST('2024-05-03' AS Date), 440, 3, 2, 0.2, NULL, CAST('2024-06-13T15:16:54.447' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1748, 3, 34, 9, CAST('2024-05-03' AS Date), 440, 3, 1, 0.2, NULL, CAST('2024-06-13T15:16:54.460' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1749, 3, 34, 9, CAST('2024-05-03' AS Date), 440, 10, 2, 0.2, NULL, CAST('2024-06-13T15:16:54.460' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1750, 2, NULL, 441, CAST('2024-05-04' AS Date), 441, 3, 2, 29, NULL, CAST('2024-06-13T15:22:46.410' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1751, 3, 35, 5, CAST('2024-05-04' AS Date), 441, 3, 1, 29, NULL, CAST('2024-06-13T15:22:46.470' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1752, 3, 35, 5, CAST('2024-05-04' AS Date), 441, 10, 2, 29, NULL, CAST('2024-06-13T15:22:46.470' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1753, 2, NULL, 442, CAST('2024-05-04' AS Date), 442, 3, 2, 0.4, NULL, CAST('2024-06-13T15:23:17.960' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1754, 3, 35, 6, CAST('2024-05-04' AS Date), 442, 3, 1, 0.4, NULL, CAST('2024-06-13T15:23:17.977' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1755, 3, 35, 6, CAST('2024-05-04' AS Date), 442, 10, 2, 0.4, NULL, CAST('2024-06-13T15:23:17.977' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1756, 2, NULL, 443, CAST('2024-05-05' AS Date), 443, 3, 2, 38.8, NULL, CAST('2024-06-13T15:31:21.420' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1757, 3, 36, 6, CAST('2024-05-05' AS Date), 443, 3, 1, 38.8, NULL, CAST('2024-06-13T15:31:21.470' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1758, 3, 36, 6, CAST('2024-05-05' AS Date), 443, 10, 2, 38.8, NULL, CAST('2024-06-13T15:31:21.470' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1759, 2, NULL, 444, CAST('2024-05-05' AS Date), 444, 3, 2, 0.15, NULL, CAST('2024-06-13T15:31:52.360' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1760, 3, 36, 7, CAST('2024-05-05' AS Date), 444, 3, 1, 0.15, NULL, CAST('2024-06-13T15:31:52.407' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1761, 3, 36, 7, CAST('2024-05-05' AS Date), 444, 10, 2, 0.15, NULL, CAST('2024-06-13T15:31:52.407' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1762, 3, 37, 7, CAST('2024-05-06' AS Date), 330, 12, 1, 500, NULL, CAST('2024-06-13T15:38:41.467' AS TIMESTAMP(3)), 'YEMIRA QUISPE HUAMAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1763, 3, 37, 7, CAST('2024-05-06' AS Date), 330, 10, 2, 500, NULL, CAST('2024-06-13T15:38:41.473' AS TIMESTAMP(3)), 'YEMIRA QUISPE HUAMAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1764, 2, NULL, 445, CAST('2024-05-06' AS Date), 445, 3, 2, 28.05, NULL, CAST('2024-06-13T15:41:02.573' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1765, 3, 37, 8, CAST('2024-05-06' AS Date), 445, 3, 1, 28.05, NULL, CAST('2024-06-13T15:41:02.610' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1766, 3, 37, 8, CAST('2024-05-06' AS Date), 445, 10, 2, 28.05, NULL, CAST('2024-06-13T15:41:02.627' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1767, 2, NULL, 446, CAST('2024-05-06' AS Date), 446, 3, 2, 1.2, NULL, CAST('2024-06-13T15:41:31.383' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1768, 3, 37, 9, CAST('2024-05-06' AS Date), 446, 3, 1, 1.2, NULL, CAST('2024-06-13T15:41:31.433' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1769, 3, 37, 9, CAST('2024-05-06' AS Date), 446, 10, 2, 1.2, NULL, CAST('2024-06-13T15:41:31.433' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1770, 1, NULL, 107, CAST('2024-05-07' AS Date), 150, 1, 1, 10777, NULL, CAST('2024-06-13T16:22:47.790' AS TIMESTAMP(3)), 'DEPOSITO DETRACCION');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1771, 3, 38, 7, CAST('2024-05-07' AS Date), 150, 10, 1, 10777, NULL, CAST('2024-06-13T16:22:47.843' AS TIMESTAMP(3)), 'DEPOSITO DETRACCION');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1772, 3, 38, 7, CAST('2024-05-07' AS Date), 150, 1, 2, 10777, NULL, CAST('2024-06-13T16:22:47.847' AS TIMESTAMP(3)), 'DEPOSITO DETRACCION');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1773, 2, NULL, 447, CAST('2024-05-07' AS Date), 447, 3, 2, 59.77, NULL, CAST('2024-06-13T16:30:38.563' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1774, 3, 38, 8, CAST('2024-05-07' AS Date), 447, 3, 1, 59.77, NULL, CAST('2024-06-13T16:30:38.610' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1775, 3, 38, 8, CAST('2024-05-07' AS Date), 447, 10, 2, 59.77, NULL, CAST('2024-06-13T16:30:38.610' AS TIMESTAMP(3)), 'COMISION DE TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1776, 2, NULL, 448, CAST('2024-05-07' AS Date), 448, 3, 2, 12, NULL, CAST('2024-06-13T16:31:21.667' AS TIMESTAMP(3)), 'NOTA DE ABONO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1777, 3, 38, 9, CAST('2024-05-07' AS Date), 448, 3, 1, 12, NULL, CAST('2024-06-13T16:31:21.673' AS TIMESTAMP(3)), 'NOTA DE ABONO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1778, 3, 38, 9, CAST('2024-05-07' AS Date), 448, 10, 2, 12, NULL, CAST('2024-06-13T16:31:21.673' AS TIMESTAMP(3)), 'NOTA DE ABONO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1779, 2, NULL, 449, CAST('2024-05-07' AS Date), 449, 3, 2, 1, NULL, CAST('2024-06-13T16:31:48.250' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1780, 3, 38, 10, CAST('2024-05-07' AS Date), 449, 3, 1, 1, NULL, CAST('2024-06-13T16:31:48.280' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1781, 3, 38, 10, CAST('2024-05-07' AS Date), 449, 10, 2, 1, NULL, CAST('2024-06-13T16:31:48.283' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1782, 2, NULL, 450, CAST('2024-05-07' AS Date), 450, 3, 2, 200, NULL, CAST('2024-06-13T16:32:20.190' AS TIMESTAMP(3)), 'RETIRO SR JOSE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1783, 3, 38, 11, CAST('2024-05-07' AS Date), 450, 3, 1, 200, NULL, CAST('2024-06-13T16:32:20.217' AS TIMESTAMP(3)), 'RETIRO SR JOSE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1784, 3, 38, 11, CAST('2024-05-07' AS Date), 450, 10, 2, 200, NULL, CAST('2024-06-13T16:32:20.217' AS TIMESTAMP(3)), 'RETIRO SR JOSE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1785, 2, NULL, 451, CAST('2024-05-09' AS Date), 451, 3, 2, 650, NULL, CAST('2024-06-13T16:40:27.987' AS TIMESTAMP(3)), 'VARIOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1786, 3, 39, 8, CAST('2024-05-08' AS Date), 451, 3, 1, 650, NULL, CAST('2024-06-13T16:40:28.020' AS TIMESTAMP(3)), 'VARIOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1787, 3, 39, 8, CAST('2024-05-08' AS Date), 451, 10, 2, 650, NULL, CAST('2024-06-13T16:40:28.020' AS TIMESTAMP(3)), 'VARIOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1788, 2, NULL, 452, CAST('2024-05-08' AS Date), 452, 3, 2, 55.88, NULL, CAST('2024-06-13T16:41:01.550' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1789, 3, 39, 9, CAST('2024-05-08' AS Date), 452, 3, 1, 55.88, NULL, CAST('2024-06-13T16:41:01.553' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1790, 3, 39, 9, CAST('2024-05-08' AS Date), 452, 10, 2, 55.88, NULL, CAST('2024-06-13T16:41:01.557' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1791, 2, NULL, 453, CAST('2024-05-08' AS Date), 453, 3, 2, 100, NULL, CAST('2024-06-13T16:41:31.257' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1792, 3, 39, 10, CAST('2024-05-08' AS Date), 453, 3, 1, 100, NULL, CAST('2024-06-13T16:41:31.323' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1793, 3, 39, 10, CAST('2024-05-08' AS Date), 453, 10, 2, 100, NULL, CAST('2024-06-13T16:41:31.327' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1794, 2, NULL, 454, CAST('2024-05-08' AS Date), 454, 3, 2, 300, NULL, CAST('2024-06-13T16:42:14.373' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1795, 3, 39, 11, CAST('2024-05-08' AS Date), 454, 3, 1, 300, NULL, CAST('2024-06-13T16:42:14.380' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1796, 3, 39, 11, CAST('2024-05-08' AS Date), 454, 10, 2, 300, NULL, CAST('2024-06-13T16:42:14.380' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1797, 2, NULL, 455, CAST('2024-05-08' AS Date), 455, 3, 2, 51.7, NULL, CAST('2024-06-13T16:42:54.213' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1798, 3, 39, 12, CAST('2024-05-08' AS Date), 455, 3, 1, 51.7, NULL, CAST('2024-06-13T16:42:54.247' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1799, 3, 39, 12, CAST('2024-05-08' AS Date), 455, 10, 2, 51.7, NULL, CAST('2024-06-13T16:42:54.250' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1800, 2, NULL, 456, CAST('2024-05-08' AS Date), 456, 3, 2, 0.9, NULL, CAST('2024-06-13T16:43:26.040' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1801, 3, 39, 13, CAST('2024-05-08' AS Date), 456, 3, 1, 0.9, NULL, CAST('2024-06-13T16:43:26.050' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1802, 3, 39, 13, CAST('2024-05-08' AS Date), 456, 10, 2, 0.9, NULL, CAST('2024-06-13T16:43:26.053' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1803, 2, NULL, 457, CAST('2024-05-09' AS Date), 457, 3, 2, 97.82, NULL, CAST('2024-06-14T08:34:53.040' AS TIMESTAMP(3)), 'PASAJE AVION LATAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1804, 3, 40, 5, CAST('2024-05-09' AS Date), 457, 3, 1, 97.82, NULL, CAST('2024-06-14T08:34:53.100' AS TIMESTAMP(3)), 'PASAJE AVION LATAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1805, 3, 40, 5, CAST('2024-05-09' AS Date), 457, 10, 2, 97.82, NULL, CAST('2024-06-14T08:34:53.107' AS TIMESTAMP(3)), 'PASAJE AVION LATAN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1806, 2, NULL, 458, CAST('2024-05-09' AS Date), 458, 3, 2, 22.1, NULL, CAST('2024-06-14T08:35:23.540' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1807, 3, 40, 6, CAST('2024-05-09' AS Date), 458, 3, 1, 22.1, NULL, CAST('2024-06-14T08:35:23.560' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1808, 3, 40, 6, CAST('2024-05-09' AS Date), 458, 10, 2, 22.1, NULL, CAST('2024-06-14T08:35:23.563' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1809, 2, NULL, 459, CAST('2024-05-09' AS Date), 459, 3, 2, 0.4, NULL, CAST('2024-06-14T08:35:52.063' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1810, 3, 40, 7, CAST('2024-05-09' AS Date), 459, 3, 1, 0.4, NULL, CAST('2024-06-14T08:35:52.070' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1811, 3, 40, 7, CAST('2024-05-09' AS Date), 459, 10, 2, 0.4, NULL, CAST('2024-06-14T08:35:52.070' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1812, 2, NULL, 460, CAST('2024-05-10' AS Date), 460, 3, 2, 400, NULL, CAST('2024-06-14T08:37:07.320' AS TIMESTAMP(3)), 'VARIOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1813, 3, 41, 4, CAST('2024-05-10' AS Date), 460, 3, 1, 400, NULL, CAST('2024-06-14T08:37:07.333' AS TIMESTAMP(3)), 'VARIOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1814, 3, 41, 4, CAST('2024-05-10' AS Date), 460, 10, 2, 400, NULL, CAST('2024-06-14T08:37:07.337' AS TIMESTAMP(3)), 'VARIOS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1815, 2, NULL, 461, CAST('2024-05-10' AS Date), 461, 3, 2, 74.95, NULL, CAST('2024-06-14T08:37:41.970' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1816, 3, 41, 5, CAST('2024-05-10' AS Date), 461, 3, 1, 74.95, NULL, CAST('2024-06-14T08:37:41.990' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1817, 3, 41, 5, CAST('2024-05-10' AS Date), 461, 10, 2, 74.95, NULL, CAST('2024-06-14T08:37:41.993' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1818, 2, NULL, 462, CAST('2024-05-10' AS Date), 462, 3, 2, 0.6, NULL, CAST('2024-06-14T08:38:04.060' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1819, 3, 41, 6, CAST('2024-05-10' AS Date), 462, 3, 1, 0.6, NULL, CAST('2024-06-14T08:38:04.070' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1820, 3, 41, 6, CAST('2024-05-10' AS Date), 462, 10, 2, 0.6, NULL, CAST('2024-06-14T08:38:04.070' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1824, 2, NULL, 463, CAST('2024-05-11' AS Date), 463, 3, 2, 1500, NULL, CAST('2024-06-14T08:43:16.357' AS TIMESTAMP(3)), 'JAVIER SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1825, 3, 42, 10, CAST('2024-05-11' AS Date), 463, 3, 1, 1500, NULL, CAST('2024-06-14T08:43:16.377' AS TIMESTAMP(3)), 'JAVIER SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1826, 3, 42, 10, CAST('2024-05-11' AS Date), 463, 10, 2, 1500, NULL, CAST('2024-06-14T08:43:16.377' AS TIMESTAMP(3)), 'JAVIER SEHUIN');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1830, 2, NULL, 465, CAST('2024-05-11' AS Date), 465, 3, 2, 22.1, NULL, CAST('2024-06-14T08:44:18.967' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1831, 3, 42, 12, CAST('2024-05-11' AS Date), 465, 3, 1, 22.1, NULL, CAST('2024-06-14T08:44:19.000' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1832, 3, 42, 12, CAST('2024-05-11' AS Date), 465, 10, 2, 22.1, NULL, CAST('2024-06-14T08:44:19.003' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1833, 2, NULL, 466, CAST('2024-05-11' AS Date), 466, 3, 2, 0.2, NULL, CAST('2024-06-14T08:44:40.507' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1834, 3, 42, 13, CAST('2024-05-11' AS Date), 466, 3, 1, 0.2, NULL, CAST('2024-06-14T08:44:40.513' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1835, 3, 42, 13, CAST('2024-05-11' AS Date), 466, 10, 2, 0.2, NULL, CAST('2024-06-14T08:44:40.523' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1839, 1, NULL, 151, CAST('2024-05-11' AS Date), 151, 1, 1, 150, NULL, CAST('2024-06-14T09:01:36.250' AS TIMESTAMP(3)), 'DEVOLUCION COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1840, 3, 42, 9, CAST('2024-05-11' AS Date), 151, 10, 1, 150, NULL, CAST('2024-06-14T09:01:36.277' AS TIMESTAMP(3)), 'DEVOLUCION COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1841, 3, 42, 9, CAST('2024-05-11' AS Date), 151, 1, 2, 150, NULL, CAST('2024-06-14T09:01:36.280' AS TIMESTAMP(3)), 'DEVOLUCION COMBUSTIBLE');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1842, 1, NULL, 152, CAST('2024-05-12' AS Date), 152, 1, 1, 50, NULL, CAST('2024-06-14T09:06:03.103' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1843, 3, 43, 2, CAST('2024-05-12' AS Date), 152, 10, 1, 50, NULL, CAST('2024-06-14T09:06:03.123' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1844, 3, 43, 2, CAST('2024-05-12' AS Date), 152, 1, 2, 50, NULL, CAST('2024-06-14T09:06:03.127' AS TIMESTAMP(3)), 'DEVOLUCION DE TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1845, 2, NULL, 467, CAST('2024-05-12' AS Date), 467, 3, 2, 4.3, NULL, CAST('2024-06-14T09:24:53.100' AS TIMESTAMP(3)), 'OMISION TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1846, 3, 43, 3, CAST('2024-05-12' AS Date), 467, 3, 1, 4.3, NULL, CAST('2024-06-14T09:24:53.180' AS TIMESTAMP(3)), 'OMISION TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1847, 3, 43, 3, CAST('2024-05-12' AS Date), 467, 10, 2, 4.3, NULL, CAST('2024-06-14T09:24:53.180' AS TIMESTAMP(3)), 'OMISION TRANSFERENCIA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1848, 2, NULL, 468, CAST('2024-05-13' AS Date), 468, 3, 2, 215, NULL, CAST('2024-06-14T09:29:51.047' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1849, 3, 44, 6, CAST('2024-05-13' AS Date), 468, 3, 1, 215, NULL, CAST('2024-06-14T09:29:51.063' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1850, 3, 44, 6, CAST('2024-05-13' AS Date), 468, 10, 2, 215, NULL, CAST('2024-06-14T09:29:51.067' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1851, 2, NULL, 469, CAST('2024-05-13' AS Date), 469, 3, 2, 315, NULL, CAST('2024-06-14T09:30:26.343' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1852, 3, 44, 7, CAST('2024-05-13' AS Date), 469, 3, 1, 315, NULL, CAST('2024-06-14T09:30:26.383' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1853, 3, 44, 7, CAST('2024-05-13' AS Date), 469, 10, 2, 315, NULL, CAST('2024-06-14T09:30:26.387' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1854, 2, NULL, 470, CAST('2024-05-13' AS Date), 470, 3, 2, 555, NULL, CAST('2024-06-14T09:30:58.997' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1855, 3, 44, 8, CAST('2024-05-13' AS Date), 470, 3, 1, 555, NULL, CAST('2024-06-14T09:30:59.060' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1856, 3, 44, 8, CAST('2024-05-13' AS Date), 470, 10, 2, 555, NULL, CAST('2024-06-14T09:30:59.060' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1857, 2, NULL, 471, CAST('2024-05-13' AS Date), 471, 3, 2, 29.6, NULL, CAST('2024-06-14T09:31:43.767' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1858, 3, 44, 9, CAST('2024-05-13' AS Date), 471, 3, 1, 29.6, NULL, CAST('2024-06-14T09:31:43.797' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1859, 3, 44, 9, CAST('2024-05-13' AS Date), 471, 10, 2, 29.6, NULL, CAST('2024-06-14T09:31:43.800' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1860, 2, NULL, 472, CAST('2024-05-13' AS Date), 472, 3, 2, 0.05, NULL, CAST('2024-06-14T09:32:19.393' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1861, 3, 44, 10, CAST('2024-05-13' AS Date), 472, 3, 1, 0.05, NULL, CAST('2024-06-14T09:32:19.427' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1862, 3, 44, 10, CAST('2024-05-13' AS Date), 472, 10, 2, 0.05, NULL, CAST('2024-06-14T09:32:19.430' AS TIMESTAMP(3)), 'ITF');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1863, 2, NULL, 473, CAST('2024-05-14' AS Date), 473, 3, 2, 165, NULL, CAST('2024-06-14T09:45:42.817' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1864, 3, 45, 3, CAST('2024-05-14' AS Date), 473, 3, 1, 165, NULL, CAST('2024-06-14T09:45:42.833' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1865, 3, 45, 3, CAST('2024-05-14' AS Date), 473, 10, 2, 165, NULL, CAST('2024-06-14T09:45:42.837' AS TIMESTAMP(3)), 'CONSUMO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1866, 2, NULL, 474, CAST('2024-06-14' AS Date), 474, 3, 2, 8, NULL, CAST('2024-06-14T09:47:02.700' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1867, 3, 71, 2, CAST('2024-06-13' AS Date), 474, 3, 1, 8, NULL, CAST('2024-06-14T09:47:02.773' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1868, 3, 71, 2, CAST('2024-06-13' AS Date), 474, 5, 2, 8, NULL, CAST('2024-06-14T09:47:02.780' AS TIMESTAMP(3)), 'CENA LEYDA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1869, 2, NULL, 475, CAST('2024-05-14' AS Date), 475, 3, 2, 14.8, NULL, CAST('2024-06-14T09:48:50.297' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1870, 3, 45, 4, CAST('2024-05-14' AS Date), 475, 3, 1, 14.8, NULL, CAST('2024-06-14T09:48:50.317' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.movimientosdecaja (id, id_libro, id_apertura, mov, fec, id_documentos, id_cuentas, id_dh, monto, montodo, fecha_registro, glosa) VALUES (1871, 3, 45, 4, CAST('2024-05-14' AS Date), 475, 10, 2, 14.8, NULL, CAST('2024-06-14T09:48:50.320' AS TIMESTAMP(3)), 'COMISION TRANSFERENCIAS');
/* SET IDENTITY_INSERT [Tesoreria].[movimientosdecaja] OFF */
 
/* SET IDENTITY_INSERT [Tesoreria].[tipoDeCaja] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipoDeCaja (id, descripcion) VALUES (1, 'CAJA CHICA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipoDeCaja (id, descripcion) VALUES (2, 'CAJA BANCOS');
/* SET IDENTITY_INSERT [Tesoreria].[tipoDeCaja] OFF */
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipodecuenta (id, descripcion) VALUES (1, 'CAJA');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipodecuenta (id, descripcion) VALUES (2, 'CXC');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipodecuenta (id, descripcion) VALUES (3, 'CXP');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipodecuenta (id, descripcion) VALUES (4, 'INGRESO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipodecuenta (id, descripcion) VALUES (5, 'GASTO');
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Tesoreria.tipodecuenta (id, descripcion) VALUES (6, 'TRANSFERENCIA');
 
/* SET IDENTITY_INSERT [Ventas].[documentos] ON */ 

-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (37, '002000001', CAST('2024-04-18' AS Date), CAST('2024-05-18' AS Date), '01', '6', '20100147514', 'PEN', 1, 'E001', '166', NULL, NULL, NULL, 154883.75, 27879.08, NULL, NULL, NULL, 0, 0, 182762.83, 18276, 164486.83, NULL, NULL, NULL, 'SERVICIOS DE ALGUILER VOLQUETE Y RETRO', 1, CAST('2024-05-17T12:32:29.337' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (38, '103000001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '00', '1', '46694933', 'PEN', 0, '0000', '2', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 20000, 0, 20000, NULL, NULL, NULL, NULL, NULL, 'DEPOSITO JUAN MACHACA', 1, CAST('2024-05-27T15:06:01.217' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (39, '001000001', CAST('2024-05-08' AS Date), CAST('2024-05-08' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '4', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 6160.7, 0, 6160.7, NULL, NULL, NULL, NULL, NULL, 'DEVOLUCION DE TRANSFERENCIA', 1, CAST('2024-05-27T15:06:32.013' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (40, '001000001', CAST('2024-05-15' AS Date), CAST('2024-05-15' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '8', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 100, 0, 100, NULL, NULL, NULL, NULL, NULL, 'DEVOLUCION DE TRANSFERENCIA', 1, CAST('2024-05-27T15:06:32.157' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (41, '001000001', CAST('2024-05-16' AS Date), CAST('2024-05-16' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '10', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 700, 0, 700, NULL, NULL, NULL, NULL, NULL, 'DEVOLUCION DE TRANSFERENCIA', 1, CAST('2024-05-27T15:06:32.167' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (42, '001000001', CAST('2024-05-27' AS Date), CAST('2024-05-27' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '12', NULL, NULL, NULL, 600, 0, NULL, NULL, NULL, 0, 0, 600, NULL, NULL, NULL, NULL, NULL, 'CAJA CHICA', 1, CAST('2024-05-27T16:28:19.247' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (43, '2001002', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '43', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 350, 0, 350, NULL, NULL, NULL, NULL, NULL, 'MIS TRES TESOROS-DISCO-', 1, CAST('2024-06-06T15:49:02.963' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (44, '2001002', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '44', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 350, 0, 350, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-2.3 HORAS', 1, CAST('2024-06-10T09:21:39.067' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (45, '2001002', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '45', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 630, 0, 630, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-4.3 HORAS', 1, CAST('2024-06-10T09:22:14.910' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (46, '2001002', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '46', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 210, 0, 210, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-1.4 HORAS', 1, CAST('2024-06-10T09:22:46.277' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (47, '2001002', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '47', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 203, 0, 203, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-1.45 HORAS', 1, CAST('2024-06-10T09:23:57.980' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (48, '2001002', CAST('2024-05-15' AS Date), CAST('2024-05-15' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '48', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1050, 0, 1050, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-MIS TRES TESOROS EIRL-DISCO-7.3 HORAS', 1, CAST('2024-06-10T09:24:31.510' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (49, '2001002', CAST('2024-05-16' AS Date), CAST('2024-05-16' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '49', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 540, 0, 540, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ALBERTO NINA-RASTRA-4.3 HORAS', 1, CAST('2024-06-10T09:25:53.140' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (50, '2001002', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '50', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 350, 0, 350, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ALBERTO NINA-ARADO-2.3 HORAS', 1, CAST('2024-06-10T09:26:33.310' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (51, '2001002', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '51', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 480, 0, 480, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-GALLEGOS-DISCO-4 HORAS', 1, CAST('2024-06-10T09:27:09.413' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (52, '2001002', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '52', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 280, 0, 280, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-NN-ROSADORA-2 HORAS', 1, CAST('2024-06-10T09:27:59.353' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (53, '001000001', CAST('2024-06-06' AS Date), CAST('2024-06-06' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '13', NULL, NULL, NULL, 1000, 0, NULL, NULL, NULL, 0, 0, 1000, NULL, NULL, NULL, NULL, NULL, 'INGRESO CAJA CHICA', 1, CAST('2024-06-10T09:53:51.400' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (54, '2001002', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '53', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 315, 0, 315, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-JOSE MARIA-LAMPON-2.25 HORAS', 1, CAST('2024-06-10T10:09:54.050' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (55, '2001002', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '54', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 455, 0, 455, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ESTEBAN-ROSADORA-3.25 HORAS', 1, CAST('2024-06-10T10:10:35.793' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (56, '2001002', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '55', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 120, 0, 120, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-PEDRO TORREBLANCA-DISCO-1 HORAS', 1, CAST('2024-06-10T10:11:00.773' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (57, '2001002', CAST('2024-05-19' AS Date), CAST('2024-05-19' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '56', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 416, 0, 416, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ELVIS GALLEGOS-DISCO-3.2 HORAS', 1, CAST('2024-06-10T10:11:42.343' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (58, '2001002', CAST('2024-05-19' AS Date), CAST('2024-05-19' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '57', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 420, 0, 420, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-RAFO CALIZAYA-RASTRA-3.4 HORAS', 1, CAST('2024-06-10T10:12:52.690' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (59, '2001002', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '58', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 600, 0, 600, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-RAFO CALIZAYA-RASTRA-5 HORAS', 1, CAST('2024-06-10T10:13:37.713' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (60, '2001002', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '59', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 611, 0, 611, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-RUTH TOTORA-RASTRA-4.7 HORAS', 1, CAST('2024-06-10T10:14:03.900' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (61, '2001002', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '60', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 240, 0, 240, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ESTEBAN-ROSADORA-2 HORAS', 1, CAST('2024-06-10T10:16:53.983' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (62, '2001002', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '61', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 715, 0, 715, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-FELIX FERNANDEZ-ROSADORA-5.5 HORAS', 1, CAST('2024-06-10T10:22:59.493' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (63, '2001002', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '62', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 65, 0, 65, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-MACHACA-ROSADORA-0.5 HORAS', 1, CAST('2024-06-10T10:23:37.027' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (64, '2001002', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '63', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 280, 0, 280, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-RUTH TOTORA-ROSADORA-2 HORAS', 1, CAST('2024-06-10T10:24:00.033' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (65, '2001002', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '64', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 210, 0, 210, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ELVIS GALLEGOZ SEVILLANO-DISCO-1.5 HORAS', 1, CAST('2024-06-10T10:24:31.987' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (66, '2001002', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '65', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 140, 0, 140, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-RAFAEL-RASTRA-1 HORAS', 1, CAST('2024-06-10T10:25:06.250' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (67, '2001002', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '66', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 227.5, 0, 227.5, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-RAFAEL-RASTRA-1.75 HORAS', 1, CAST('2024-06-10T10:25:37.717' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (68, '2001002', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '67', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 260, 0, 260, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-TOMAS-ROSADORA-2 HORAS', 1, CAST('2024-06-10T10:26:03.693' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (69, '2001002', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '68', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 260, 0, 260, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-VALENCIA-ROSADORA-2 HORAS', 1, CAST('2024-06-10T10:26:47.027' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (70, '2001002', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '69', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 845, 0, 845, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-NESTOR-RASTRA-6.5 HORAS', 1, CAST('2024-06-10T10:27:12.070' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (71, '2001002', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '70', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 416, 0, 416, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-LORENZO-RASTRA-3.2 HORAS', 1, CAST('2024-06-10T10:27:40.230' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (72, '2001002', CAST('2024-05-26' AS Date), CAST('2024-05-26' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '71', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 481, 0, 481, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-SUNISO-DISCO-3.7 HORAS', 1, CAST('2024-06-10T10:28:17.580' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (73, '2001002', CAST('2024-05-28' AS Date), CAST('2024-05-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '72', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1190, 0, 1190, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ELVIS GALLEGOS HIJO-DISCO-8.5 HORAS', 1, CAST('2024-06-10T10:28:47.153' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (74, '2001002', CAST('2024-05-29' AS Date), CAST('2024-05-29' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '73', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 585, 0, 585, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-JOSE JULIAN MAMANI-DISCO-4.5 HORAS', 1, CAST('2024-06-10T10:29:18.977' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (75, '2001002', CAST('2024-06-02' AS Date), CAST('2024-06-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '74', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1470, 0, 1470, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 135-ROBERTO-RASTRA-10.5 HORAS', 1, CAST('2024-06-10T10:29:48.683' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (76, '002001001', CAST('2024-03-08' AS Date), CAST('2024-03-08' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '81', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1132.8, 0, 1132.8, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-MIS TRES TESOROS EIRL-DISCO-8 HORAS MAQUINA', 1, CAST('2024-06-10T16:51:28.853' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (77, '002001001', CAST('2024-03-09' AS Date), CAST('2024-03-09' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '82', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1203.6, 0, 1203.6, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-MIS TRES TESOROS EIRL-DISCO-8.5 HORAS MAQUINA', 1, CAST('2024-06-10T16:52:03.213' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (78, '002001001', CAST('2024-03-13' AS Date), CAST('2024-03-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '83', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 260, 0, 260, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-NN-ROSADORA-2 HORAS MAQUINA', 1, CAST('2024-06-10T16:52:29.703' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (79, '002001001', CAST('2024-03-14' AS Date), CAST('2024-03-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '84', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 195, 0, 195, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-PEDRO TORREBLANCA-DISCO-1.5 HORAS MAQUINA', 1, CAST('2024-06-10T16:52:55.357' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (80, '002001001', CAST('2024-04-26' AS Date), CAST('2024-04-26' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '85', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 65, 0, 65, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-ELVIS GALLEGOZ SEVILLANO-DISCO-0.5 HORAS MAQUINA', 1, CAST('2024-06-10T16:53:18.070' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (81, '002001001', CAST('2024-04-27' AS Date), CAST('2024-04-27' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '86', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 845, 0, 845, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-RAFAEL-RASTRA-6.5 HORAS MAQUINA', 1, CAST('2024-06-10T16:53:45.803' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (82, '002001001', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '87', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 227.5, 0, 227.5, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-LORENZO-RASTRA-2 HORAS MAQUINA', 1, CAST('2024-06-10T17:00:10.107' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (83, '002001001', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '88', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 585, 0, 585, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-SUNISO-DISCO-4.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:00:38.747' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (84, '002001001', CAST('2024-05-13' AS Date), CAST('2024-05-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '89', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 25, 0, 25, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-RAFAEL-RASTRA-2.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:01:01.480' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (85, '002001001', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '90', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 130, 0, 130, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-NESTOR-RASTRA-1 HORAS MAQUINA', 1, CAST('2024-06-10T17:01:22.530' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (86, '002001001', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '91', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 520, 0, 520, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-VALENCIA-DISCO-4 HORAS MAQUINA', 1, CAST('2024-06-10T17:01:46.210' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (87, '002001001', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '92', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 170, 0, 170, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-VALENCIA-DISCO-1.2 HORAS MAQUINA', 1, CAST('2024-06-10T17:03:24.740' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (88, '002001001', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '93', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 195, 0, 195, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-JOSE PAREDES-RASTRA-1.3 HORAS MAQUINA', 1, CAST('2024-06-10T17:03:55.130' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (89, '002001001', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '94', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 280, 0, 280, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-VIDAL VARGAS-DISCO-2.1 HORAS MAQUINA', 1, CAST('2024-06-10T17:04:59.453' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (90, '002001001', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '95', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 169, 0, 169, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-VIDAL VARGAS-DISCO-1.3 HORAS MAQUINA', 1, CAST('2024-06-10T17:05:22.850' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (91, '002001001', CAST('2024-05-28' AS Date), CAST('2024-05-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '96', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1222, 0, 1222, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-RAFAEL-DISCO-9.4 HORAS MAQUINA', 1, CAST('2024-06-10T17:07:38.343' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (92, '002001001', CAST('2024-05-29' AS Date), CAST('2024-05-29' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '97', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 325, 0, 325, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-RAFAEL-DISCO-2.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:14:43.340' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (93, '002001001', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '98', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 260, 0, 260, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-MARUJA-RASTRA-2 HORAS MAQUINA', 1, CAST('2024-06-10T17:15:08.697' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (94, '002001001', CAST('2024-06-03' AS Date), CAST('2024-06-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '99', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 455, 0, 455, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-JOSE PAREDES-RASTRA-3.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:15:34.450' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (95, '002001001', CAST('2024-06-03' AS Date), CAST('2024-06-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '100', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 585, 0, 585, NULL, NULL, NULL, NULL, NULL, 'RETROEXCAVADORA-ADAN-RASTRA-4.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:16:12.527' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (96, '2001003', CAST('2024-03-05' AS Date), CAST('2024-03-05' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '101', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 660, 0, 660, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE MARIA-RASTRA-8 HORAS MAQUINA', 1, CAST('2024-06-10T17:31:53.477' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (97, '2001003', CAST('2024-03-13' AS Date), CAST('2024-03-13' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '102', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 340, 0, 340, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-MIS TRES TESOROS EIRL-DISCO-11 HORAS MAQUINA', 1, CAST('2024-06-10T17:32:25.160' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (98, '2001003', CAST('2024-03-14' AS Date), CAST('2024-03-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '103', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 660, 0, 660, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-MIS TRES TESOROS EIRL-DISCO-5.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:32:59.540' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (99, '2001003', CAST('2024-04-22' AS Date), CAST('2024-04-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '104', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 240, 0, 240, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ALBERTO NINA-RASTRA-2 HORAS MAQUINA', 1, CAST('2024-06-10T17:33:24.283' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (100, '2001003', CAST('2024-04-28' AS Date), CAST('2024-04-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '105', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 120, 0, 120, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE MARIA-LAMPON-1 HORAS MAQUINA', 1, CAST('2024-06-10T17:34:22.130' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (101, '2001003', CAST('2024-04-30' AS Date), CAST('2024-04-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '106', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 520, 0, 520, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ELVIS GALLEGOS-DISCO-4 HORAS MAQUINA', 1, CAST('2024-06-10T17:34:48.417' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (102, '2001003', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '107', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 845, 0, 845, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ELVIS GALLEGOS-DISCO-6.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:35:11.650' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (103, '2001003', CAST('2024-05-01' AS Date), CAST('2024-05-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '108', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 60, 0, 60, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RUTH TOTORA-RASTRA-0.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:35:41.450' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (104, '2001003', CAST('2024-05-02' AS Date), CAST('2024-05-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '109', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 240, 0, 240, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-FELIX FERNANDEZ-ROSADORA- HORAS MAQUINA', 1, CAST('2024-06-10T17:36:07.397' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (105, '2001003', CAST('2024-05-03' AS Date), CAST('2024-05-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '110', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 412.5, 0, 412.5, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RUTH TOTORA-ROSADORA- HORAS MAQUINA', 1, CAST('2024-06-10T17:37:58.507' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (106, '2001003', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '111', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 300, 0, 300, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-RASTRA-2.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:38:36.817' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (107, '2001003', CAST('2024-05-06' AS Date), CAST('2024-05-06' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '112', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 180, 0, 180, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-LORENZO-RASTRA-1.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:39:06.710' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (108, '2001003', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '113', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 715, 0, 715, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-DISCO-5.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:39:34.950' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (109, '2001003', CAST('2024-05-10' AS Date), CAST('2024-05-10' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '114', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 715, 0, 715, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-DISCO-5.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:39:59.150' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (110, '2001003', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '115', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 180, 0, 180, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-RASTRA-1.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:42:23.957' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (111, '2001003', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '116', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 180, 0, 180, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-RASTRA-1.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:42:52.217' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (112, '2001003', CAST('2024-05-14' AS Date), CAST('2024-05-14' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '117', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 120, 0, 120, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-VIDAL VARGAS-LAMPON-1 HORAS MAQUINA', 1, CAST('2024-06-10T17:43:15.720' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (113, '2001003', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '118', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 150, 0, 150, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-RASTRA-1.25 HORAS MAQUINA', 1, CAST('2024-06-10T17:43:37.247' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (114, '2001003', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '119', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 420, 0, 420, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE PAREDES-RASTRA-3.5 HORAS MAQUINA', 1, CAST('2024-06-10T17:44:07.467' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (115, '2001003', CAST('2024-05-17' AS Date), CAST('2024-05-17' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '120', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 487.5, 0, 487.5, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-VIDAL VARGAS-ARADO-3.75 HORAS MAQUINA', 1, CAST('2024-06-10T17:44:44.560' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (116, '2001003', CAST('2024-05-18' AS Date), CAST('2024-05-18' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '121', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1550, 0, 1550, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-VIDAL VARGAS-DISCO-12 HORAS MAQUINA', 1, CAST('2024-06-10T17:45:11.757' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (117, '2001003', CAST('2024-05-19' AS Date), CAST('2024-05-19' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '122', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 650, 0, 650, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-VIDAL VARGAS-DISCO-5 HORAS MAQUINA', 1, CAST('2024-06-10T17:45:34.260' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (118, '2001003', CAST('2024-05-19' AS Date), CAST('2024-05-19' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '123', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 975, 0, 975, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE PAREDES-DISCO-8.2 HORAS MAQUINA', 1, CAST('2024-06-10T17:46:04.117' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (119, '2001003', CAST('2024-05-20' AS Date), CAST('2024-05-20' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '124', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1430, 0, 1430, NULL, NULL, NULL, NULL, NULL, '20/05/2024	1430.00	TRACTOR 110-RAFAEL-DISCO-11 HORAS MAQUINA', 1, CAST('2024-06-10T17:46:25.840' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (120, '2001003', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '125', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 520, 0, 520, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-DISCO-4 HORAS MAQUINA', 1, CAST('2024-06-10T18:00:12.470' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (121, '2001003', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '126', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 540, 0, 540, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE SEHUIN-ROSADORA-4.45 HORAS MAQUINA', 1, CAST('2024-06-10T18:02:13.997' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (122, '2001003', CAST('2024-05-21' AS Date), CAST('2024-05-21' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '127', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 409, 0, 409, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-DISCO-3.25 HORAS MAQUINA', 1, CAST('2024-06-10T18:02:40.740' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (123, '2001003', CAST('2024-05-22' AS Date), CAST('2024-05-22' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '128', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1820, 0, 1820, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-DISCO-14 HORAS MAQUINA', 1, CAST('2024-06-10T18:03:04.667' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (124, '2001003', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '129', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 455, 0, 455, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-DISCO-3.5 HORAS MAQUINA', 1, CAST('2024-06-10T18:03:28.870' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (125, '2001003', CAST('2024-05-23' AS Date), CAST('2024-05-23' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '130', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 980, 0, 980, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-VIDAL VARGAS-DISCO-7.5 HORAS MAQUINA', 1, CAST('2024-06-10T18:03:55.597' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (126, '2001003', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '131', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 650, 0, 650, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-VIDAL VARGAS-DISCO-5 HORAS MAQUINA', 1, CAST('2024-06-10T18:04:18.613' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (127, '2001003', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '132', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 540, 0, 540, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-MARUJA-RASTRA-4.5 HORAS MAQUINA', 1, CAST('2024-06-10T18:04:47.683' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (128, '2001003', CAST('2024-05-24' AS Date), CAST('2024-05-24' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '133', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 330, 0, 330, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NELY SONCCO-RASTRA-2.75 HORAS MAQUINA', 1, CAST('2024-06-10T18:05:12.690' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (129, '2001003', CAST('2024-05-25' AS Date), CAST('2024-05-25' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '134', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 480, 0, 480, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-RASTRA-4 HORAS MAQUINA', 1, CAST('2024-06-10T18:05:38.663' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (130, '2001003', CAST('2024-05-25' AS Date), CAST('2024-05-25' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '135', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 564, 0, 564, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE SEHUIN-RASTRA-4.7 HORAS MAQUINA', 1, CAST('2024-06-10T18:06:01.140' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (131, '2001003', CAST('2024-05-26' AS Date), CAST('2024-05-26' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '136', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 360, 0, 360, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE PAREDES-RASTRA-3 HORAS MAQUINA', 1, CAST('2024-06-10T18:06:27.070' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (132, '2001003', CAST('2024-05-26' AS Date), CAST('2024-05-26' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '137', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 390, 0, 390, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ADAN-RASTRA-3.25 HORAS MAQUINA', 1, CAST('2024-06-10T18:06:51.953' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (133, '2001003', CAST('2024-05-27' AS Date), CAST('2024-05-27' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '138', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 720, 0, 720, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-RASTRA-6 HORAS MAQUINA', 1, CAST('2024-06-10T18:07:14.573' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (134, '2001003', CAST('2024-05-28' AS Date), CAST('2024-05-28' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '139', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 617.5, 0, 617.5, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ELVIS HIJO-DISCO-4.75 HORAS MAQUINA', 1, CAST('2024-06-10T18:07:41.930' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (135, '2001003', CAST('2024-05-29' AS Date), CAST('2024-05-29' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '140', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 806, 0, 806, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ÑATO CONDORI-DISCO-6.2 HORAS MAQUINA', 1, CAST('2024-06-10T18:08:24.200' AS TIMESTAMP(3)), NULL);
 
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (136, '2001003', CAST('2024-05-29' AS Date), CAST('2024-05-29' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '141', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 416, 0, 416, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-ÑATO CONDORI-DISCO-3.2 HORAS MAQUINA', 1, CAST('2024-06-10T18:09:15.850' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (137, '2001003', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '142', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 780, 0, 780, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE PAREDES-DISCO-6 HORAS MAQUINA', 1, CAST('2024-06-10T18:09:40.903' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (138, '2001003', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '143', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 245, 0, 245, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-DISCO-1.75 HORAS MAQUINA', 1, CAST('2024-06-10T18:10:05.163' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (139, '2001003', CAST('2024-05-30' AS Date), CAST('2024-05-30' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '144', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 180, 0, 180, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NESTOR-RASTRA-1.5 HORAS MAQUINA', 1, CAST('2024-06-10T18:10:28.623' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (140, '2001003', CAST('2024-05-31' AS Date), CAST('2024-05-31' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '145', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 1140, 0, 1140, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-RASTRA-9.5 HORAS MAQUINA', 1, CAST('2024-06-10T18:10:55.543' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (141, '2001003', CAST('2024-05-31' AS Date), CAST('2024-05-31' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '146', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 240, 0, 240, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL-RASTRA-2 HORAS MAQUINA', 1, CAST('2024-06-10T18:11:21.310' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (142, '2001003', CAST('2024-06-01' AS Date), CAST('2024-06-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '147', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 504, 0, 504, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-MARUJA-RASTRA-4.2 HORAS MAQUINA', 1, CAST('2024-06-10T18:12:39.407' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (143, '2001003', CAST('2024-06-01' AS Date), CAST('2024-06-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '148', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 360, 0, 360, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-NELY SONCCO-RASTRA-3 HORAS MAQUINA', 1, CAST('2024-06-10T18:13:02.233' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (144, '2001003', CAST('2024-06-01' AS Date), CAST('2024-06-01' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '149', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 240, 0, 240, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE SEHUIN-RASTRA-2 HORAS MAQUINA', 1, CAST('2024-06-10T18:13:28.840' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (145, '2001003', CAST('2024-06-02' AS Date), CAST('2024-06-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '150', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 611, 0, 611, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110--DISCO-4.7 HORAS MAQUINA', 1, CAST('2024-06-10T18:14:05.570' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (146, '2001003', CAST('2024-06-02' AS Date), CAST('2024-06-02' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '151', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 351, 0, 351, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-AUGUSTO HUAYAPA-DISCO-2.7 HORAS MAQUINA', 1, CAST('2024-06-10T18:14:30.563' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (147, '2001003', CAST('2024-06-03' AS Date), CAST('2024-06-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '152', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 286, 0, 286, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-RAFAEL--2.2 HORAS MAQUINA', 1, CAST('2024-06-10T18:15:49.007' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (148, '2001003', CAST('2024-06-03' AS Date), CAST('2024-06-03' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '153', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 264, 0, 264, NULL, NULL, NULL, NULL, NULL, 'TRACTOR 110-JOSE SEHUIN-LAMPON-2.2 HORAS MAQUINA', 1, CAST('2024-06-10T18:16:15.570' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (149, '001000001', CAST('2024-06-10' AS Date), CAST('2024-06-10' AS Date), '74', '6', '20606566558', 'PEN', 0, '0000', '14', NULL, NULL, NULL, 500, 0, NULL, NULL, NULL, 0, 0, 500, NULL, NULL, NULL, NULL, NULL, 'INGRESO CAJA CHICA', 1, CAST('2024-06-11T10:31:29.943' AS TIMESTAMP(3)), 1);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (150, '002000002', CAST('2024-05-07' AS Date), CAST('2024-05-07' AS Date), '75', '1', '10000001', 'PEN', 0, '0000', '177', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 10777, 0, 10777, NULL, NULL, NULL, NULL, NULL, 'DEPOSITO DETRACCION', 1, CAST('2024-06-13T16:22:47.743' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (151, '002000002', CAST('2024-05-11' AS Date), CAST('2024-05-11' AS Date), '01', '6', '20100075858', 'PEN', 1, 'FJ04', '118204', NULL, NULL, NULL, 127.12, 22.88, NULL, NULL, NULL, 0, 0, 150, NULL, NULL, NULL, NULL, NULL, 'DEVOLUCION COMBUSTIBLE', 1, CAST('2024-06-14T08:40:14.040' AS TIMESTAMP(3)), NULL);
-- SQLINES LICENSE FOR EVALUATION USE ONLY
INSERT INTO Ventas.documentos (id, id_detalle, fechaEmi, fechaVen, id_t10tdoc, id_t02tcom, id_entidades, id_t04tipmon, id_tasasIgv, serie, numero, totalBi, descuentoBi, recargoBi, basImp, IGV, totalNg, descuentoNg, recargoNg, noGravadas, otroTributo, precio, detraccion, montoNeto, id_t10tdocMod, serieMon, numeroMod, observaciones, id_Usuario, fecha_Registro, id_dest_tipcaja) VALUES (152, '002000002', CAST('2024-05-12' AS Date), CAST('2024-05-12' AS Date), '01', '6', '20539654153', 'PEN', 1, 'F001', '19020', NULL, NULL, NULL, 42.37, 7.63, NULL, NULL, NULL, 0, 0, 50, NULL, NULL, NULL, NULL, NULL, 'DEVOLUCION DE TRANSFERENCIA', 1, CAST('2024-06-14T09:06:03.077' AS TIMESTAMP(3)), NULL);
/* SET IDENTITY_INSERT [Ventas].[documentos] OFF */
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_entidades_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.entidades ADD  CONSTRAINT UQ_entidades_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_estados_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.estados ADD  CONSTRAINT UQ_estados_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_formasdepago_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.formasdepago ADD  CONSTRAINT UQ_formasdepago_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_sino_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.sino ADD  CONSTRAINT UQ_sino_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_tipcamsunat_Fecha]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.tipcamsunat ADD  CONSTRAINT UQ_tipcamsunat_Fecha UNIQUE 
(
	fecha
) ;
 
/* SQLINES DEMO *** ex [UQ_tipcamsunat_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.tipcamsunat ADD  CONSTRAINT UQ_tipcamsunat_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_usuarios_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.usuarios ADD  CONSTRAINT UQ_usuarios_id UNIQUE 
(
	id
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_usuarios_usuarios]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE General.usuarios ADD  CONSTRAINT UQ_usuarios_usuarios UNIQUE 
(
	usuario
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_familias_descripcion]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Logistica.familias ADD  CONSTRAINT UQ_familias_descripcion UNIQUE 
(
	descripcion
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_familias_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Logistica.familias ADD  CONSTRAINT UQ_familias_id UNIQUE 
(
	id
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_tabla02_tipodedocumentodeidentidad_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Sunat.tabla02_tipodedocumentodeidentidad ADD  CONSTRAINT UQ_tabla02_tipodedocumentodeidentidad_id UNIQUE 
(
	id
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_tabla04_tipodemoneda_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Sunat.tabla04_tipodemoneda ADD  CONSTRAINT UQ_tabla04_tipodemoneda_id UNIQUE 
(
	Id
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_s_tabla10_tipodecomprobantedepagoodocumento_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Sunat.tabla10_tipodecomprobantedepagoodocumento ADD  CONSTRAINT UQ_s_tabla10_tipodecomprobantedepagoodocumento_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_tasas_igv_id]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Sunat.tasas_igv ADD  CONSTRAINT UQ_tasas_igv_id UNIQUE 
(
	id
) ;
 
/* SQLINES DEMO *** ex [UQ_tasas_igv_numero]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Sunat.tasas_igv ADD  CONSTRAINT UQ_tasas_igv_numero UNIQUE 
(
	numero
) ;
 
/* SET ANSI_PADDING ON */
 
/* SQLINES DEMO *** ex [UQ_tasas_igv_tasa]    Script Date: 8/07/2024 12:35:26 ******/
ALTER TABLE Sunat.tasas_igv ADD  CONSTRAINT UQ_tasas_igv_tasa UNIQUE 
(
	tasa
) ;
 
ALTER TABLE Compras.documentos ALTER COLUMN fecha_Registro  SET DEFAULT (now()) ;
 
ALTER TABLE Tesoreria.movimientosdecaja ALTER COLUMN fecha_registro  SET DEFAULT (now()) ;
 
ALTER TABLE Ventas.documentos ALTER COLUMN fecha_Registro  SET DEFAULT (now()) ;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_documentos_detalle FOREIGN KEY(id_detalle)
REFERENCES Logistica.detalle (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_documentos_detalle;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_Documentos_entidades FOREIGN KEY(id_entidades)
REFERENCES General.entidades (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_Documentos_entidades;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_Documentos_tabla02_tipodedocumentodeidentidad FOREIGN KEY(id_t02tcom)
REFERENCES Sunat.tabla02_tipodedocumentodeidentidad (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_Documentos_tabla02_tipodedocumentodeidentidad;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_Documentos_tabla04_tipodemoneda FOREIGN KEY(id_t04tipmon)
REFERENCES Sunat.tabla04_tipodemoneda (Id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_Documentos_tabla04_tipodemoneda;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_Documentos_tabla10_tipodecomprobantedepagoodocumento FOREIGN KEY(id_t10tdoc)
REFERENCES Sunat.tabla10_tipodecomprobantedepagoodocumento (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_Documentos_tabla10_tipodecomprobantedepagoodocumento;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_documentos_tabla10_tipodecomprobantedepagoodocumento1 FOREIGN KEY(id_t10tdocMod)
REFERENCES Sunat.tabla10_tipodecomprobantedepagoodocumento (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_documentos_tabla10_tipodecomprobantedepagoodocumento1;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_Documentos_tasas_igv FOREIGN KEY(id_tasasIgv)
REFERENCES Sunat.tasas_igv (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_Documentos_tasas_igv;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_documentos_tipoDeCaja FOREIGN KEY(id_dest_tipcaja)
REFERENCES Tesoreria.tipoDeCaja (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_documentos_tipoDeCaja;
 
ALTER TABLE Compras.documentos ADD  CONSTRAINT FK_Documentos_usuarios FOREIGN KEY(id_Usuario)
REFERENCES General.usuarios (id);
 
ALTER TABLE Compras.documentos VALIDATE CONSTRAINT FK_Documentos_usuarios;
 
ALTER TABLE General.entidades ADD  CONSTRAINT FK_entidades_tabla02_tipodedocumentodeidentidad FOREIGN KEY(idt02doc)
REFERENCES Sunat.tabla02_tipodedocumentodeidentidad (id)
ON UPDATE CASCADE
ON DELETE CASCADE;
 
ALTER TABLE General.entidades VALIDATE CONSTRAINT FK_entidades_tabla02_tipodedocumentodeidentidad;
 
ALTER TABLE Logistica.detalle ADD  CONSTRAINT FK_detalle_cuentas FOREIGN KEY(id_cuenta)
REFERENCES Tesoreria.cuentas (id);
 
ALTER TABLE Logistica.detalle VALIDATE CONSTRAINT FK_detalle_cuentas;
 
ALTER TABLE Logistica.detalle ADD  CONSTRAINT FK_detalle_familias FOREIGN KEY(id_familias)
REFERENCES Logistica.familias (id);
 
ALTER TABLE Logistica.detalle VALIDATE CONSTRAINT FK_detalle_familias;
 
ALTER TABLE Logistica.subfamilias ADD  CONSTRAINT FK_subfamilias_familias FOREIGN KEY(id_familias)
REFERENCES Logistica.familias (id);
 
ALTER TABLE Logistica.subfamilias VALIDATE CONSTRAINT FK_subfamilias_familias;
 
ALTER TABLE Tesoreria.aperturas ADD  CONSTRAINT FK_aperturas_meses FOREIGN KEY(id_mes)
REFERENCES General.meses (id);
 
ALTER TABLE Tesoreria.aperturas VALIDATE CONSTRAINT FK_aperturas_meses;
 
ALTER TABLE Tesoreria.aperturas ADD  CONSTRAINT FK_aperturas_tipoDeCaja FOREIGN KEY(id_tipo)
REFERENCES Tesoreria.tipoDeCaja (id);
 
ALTER TABLE Tesoreria.aperturas VALIDATE CONSTRAINT FK_aperturas_tipoDeCaja;
 
ALTER TABLE Tesoreria.cuentas ADD  CONSTRAINT FK_cuentas_tipodecuenta FOREIGN KEY(id_tCuenta)
REFERENCES Tesoreria.tipodecuenta (id);
 
ALTER TABLE Tesoreria.cuentas VALIDATE CONSTRAINT FK_cuentas_tipodecuenta;
 
ALTER TABLE Tesoreria.movimientosdecaja ADD  CONSTRAINT FK_movimientosdecaja_cuentas FOREIGN KEY(id_cuentas)
REFERENCES Tesoreria.cuentas (id);
 
ALTER TABLE Tesoreria.movimientosdecaja VALIDATE CONSTRAINT FK_movimientosdecaja_cuentas;
 
ALTER TABLE Tesoreria.movimientosdecaja ADD  CONSTRAINT FK_movimientosdecaja_debehaber FOREIGN KEY(id_dh)
REFERENCES Tesoreria.debehaber (id);
 
ALTER TABLE Tesoreria.movimientosdecaja VALIDATE CONSTRAINT FK_movimientosdecaja_debehaber;
 
ALTER TABLE Tesoreria.movimientosdecaja ADD  CONSTRAINT FK_movimientosdecaja_libros FOREIGN KEY(id_libro)
REFERENCES Tesoreria.libros (id);
 
ALTER TABLE Tesoreria.movimientosdecaja VALIDATE CONSTRAINT FK_movimientosdecaja_libros;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_detalle FOREIGN KEY(id_detalle)
REFERENCES Logistica.detalle (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_detalle;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_entidades FOREIGN KEY(id_entidades)
REFERENCES General.entidades (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_entidades;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_tabla02_tipodedocumentodeidentidad FOREIGN KEY(id_t02tcom)
REFERENCES Sunat.tabla02_tipodedocumentodeidentidad (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_tabla02_tipodedocumentodeidentidad;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_tabla04_tipodemoneda FOREIGN KEY(id_t04tipmon)
REFERENCES Sunat.tabla04_tipodemoneda (Id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_tabla04_tipodemoneda;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_tabla10_tipodecomprobantedepagoodocumento FOREIGN KEY(id_t10tdoc)
REFERENCES Sunat.tabla10_tipodecomprobantedepagoodocumento (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_tabla10_tipodecomprobantedepagoodocumento;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_tabla10_tipodecomprobantedepagoodocumento1 FOREIGN KEY(id_t10tdocMod)
REFERENCES Sunat.tabla10_tipodecomprobantedepagoodocumento (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_tabla10_tipodecomprobantedepagoodocumento1;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_tasas_igv FOREIGN KEY(id_tasasIgv)
REFERENCES Sunat.tasas_igv (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_tasas_igv;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_tipoDeCaja FOREIGN KEY(id_dest_tipcaja)
REFERENCES Tesoreria.tipoDeCaja (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_tipoDeCaja;
 
ALTER TABLE Ventas.documentos ADD  CONSTRAINT FK_documentos_usuarios FOREIGN KEY(id_Usuario)
REFERENCES General.usuarios (id);
 
ALTER TABLE Ventas.documentos VALIDATE CONSTRAINT FK_documentos_usuarios;
 
 
 
/* ALTER DATABASE METAMSUR_CAJA_DATA SET  READ_WRITE */ 
 
