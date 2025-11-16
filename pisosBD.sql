CREATE DATABASE IF NOT EXISTS pisosBD;
USE pisosBD;

-- tabla paises 
CREATE TABLE IF NOT EXISTS Paises (
    IdPais INT AUTO_INCREMENT PRIMARY KEY,
    NomPais VARCHAR(100)
);

-- tabla estilos
CREATE TABLE IF NOT EXISTS Estilos (
    IdEstilo INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100),
    Descripcion TEXT,
    Fichero VARCHAR(255)
);

-- tabla usuarios
CREATE TABLE IF NOT EXISTS Usuarios (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    NomUsuario VARCHAR(15) UNIQUE,
    Clave VARCHAR(15),
    Email VARCHAR(254),
    Sexo TINYINT,
    FNacimiento DATE,
    Ciudad VARCHAR(100),
    Pais INT,
    Foto VARCHAR(255),
    FRegistro TIMESTAMP,
    Estilo INT,
    FOREIGN KEY (Pais) REFERENCES Paises(IdPais),
    FOREIGN KEY (Estilo) REFERENCES Estilos(IdEstilo)
);

-- tabla tipos anuncios
CREATE TABLE IF NOT EXISTS TiposAnuncios (
    IdTAnuncio TINYINT AUTO_INCREMENT PRIMARY KEY,
    NomTAnuncio VARCHAR(100)
);

-- tablas tipos viviendas
CREATE TABLE IF NOT EXISTS TiposViviendas (
    IdTVivienda TINYINT AUTO_INCREMENT PRIMARY KEY,
    NomTVivienda VARCHAR(50)
);

-- tabla anuncios
CREATE TABLE IF NOT EXISTS Anuncios (
    IdAnuncio INT AUTO_INCREMENT PRIMARY KEY,
    TAnuncio TINYINT,
    TVivienda TINYINT,
    FPrincipal VARCHAR(255),
    Alternativo VARCHAR(255),
    Titulo VARCHAR(255),
    Precio DECIMAL(10, 2), -- creo que esto es lo mejor porque asi guarda un numero de hasta 10 cifras y con solo 2 decimales ya que en las practicas nos han ido pidiendo solo 2 decimales siempre
    Texto TEXT,
    Ciudad VARCHAR(100),
    Pais INT,
    Superficie DECIMAL(10, 2), -- lo mismo que el precio (preguntar si 10 cifras es mucho o poco porque ni idea la verdad)
    NHabitaciones INT,
    NBanyos INT,
    Planta INT,
    Anyo INT,
    FRegistro TIMESTAMP,
    Usuario INT,
    FOREIGN KEY (TAnuncio) REFERENCES TiposAnuncios(IdTAnuncio),
    FOREIGN KEY (TVivienda) REFERENCES TiposViviendas(IdTVivienda),
    FOREIGN KEY (Pais) REFERENCES Paises(IdPais),
    FOREIGN KEY (Usuario) REFERENCES Usuarios(IdUsuario)
);

-- tabla fotos
CREATE TABLE IF NOT EXISTS Fotos (
    IdFoto INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(255),
    Foto VARCHAR(255),
    Alternativo VARCHAR(255),
    Anuncio INT,
    FOREIGN KEY (Anuncio) REFERENCES Anuncios(IdAnuncio)
);

-- tabla tipo mensajes
CREATE TABLE IF NOT EXISTS TiposMensajes (
    IdTMensaje TINYINT AUTO_INCREMENT PRIMARY KEY,
    NomTMensaje VARCHAR(50) NOT NULL
);

-- tabla mensajes
CREATE TABLE IF NOT EXISTS Mensajes (
    IdMensaje INT AUTO_INCREMENT PRIMARY KEY,
    TMensaje TINYINT,
    Texto VARCHAR(400),
    Anuncio INT,
    UsuOrigen INT,
    UsuDestino INT,
    FRegistro TIMESTAMP,
    FOREIGN KEY (TMensaje) REFERENCES TiposMensajes(IdTMensaje),
    FOREIGN KEY (Anuncio) REFERENCES Anuncios(IdAnuncio),
    FOREIGN KEY (UsuOrigen) REFERENCES Usuarios(IdUsuario),
    FOREIGN KEY (UsuDestino) REFERENCES Usuarios(IdUsuario)
);

-- tabla solicitudes 
CREATE TABLE IF NOT EXISTS Solicitudes (
    IdSolicitud INT AUTO_INCREMENT PRIMARY KEY,
    Anuncio INT,
    Texto VARCHAR(400),
    Nombre VARCHAR(200),
    Email VARCHAR(254),
    Direccion TEXT,
    Telefono VARCHAR(20),
    Color VARCHAR(100),
    Copias INT,
    Resolucion INT,
    Fecha DATE,
    IColor BOOLEAN,
    IPrecio BOOLEAN,
    FRegistro TIMESTAMP,
    Coste DECIMAL(10, 2),
    FOREIGN KEY (Anuncio) REFERENCES Anuncios(IdAnuncio)
);

-- aDATOS DE PRUEBA---------------------------------------------------------

-- paises (preguntar cuantos hay que meter)
INSERT INTO Paises (NomPais) VALUES
('España'),
('Francia'),
('Italia'),
('Portugal'),
('Alemania'),
('Suecia'),
('Lituania'),
('Austria');

-- estilos (los que tenemos ya)
INSERT INTO Estilos (Nombre, Descripcion, Fichero) VALUES
('Predeterminado', 'Estilo por defecto', 'predeterminado.css'),
('Oscuro', 'Tema oscuro', 'oscuro.css'),
('Contraste', 'Mayor contraste', 'contraste.css'),
('Letra Grande', 'Letra más grande', 'letraGrande.css'),
('Letra Grande Contraste', 'Letra grande con contraste', 'letraGrandeContraste.css');

-- tipo de anuncio (los que hay)
INSERT INTO TiposAnuncios (NomTAnuncio) VALUES
('Venta'),
('Alquiler');

-- tipos de vivienda (los que hay tambien)
INSERT INTO TiposViviendas (NomTVivienda) VALUES
('Obra nueva'),
('Vivienda'),
('Oficina'),
('Local'),
('Garaje');

-- tipos de mensajes (los que hay tambien)
INSERT INTO TiposMensajes (NomTMensaje) VALUES
('Más información'),
('Solicitar una cita'),
('Comunicar una oferta');

-- usuarios
INSERT INTO Usuarios (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, Estilo) VALUES
('usuario1', 'usu1', 'usuario1@gmail.com', 1, '2000-01-15', 'Alicante', 1, 'foto1.jpg', 1),
('usuario2', 'usu2', 'usuario2@gmail.com', 2, '2001-02-16', 'Lisboa', 3, 'foto2.jpg', 2),
('usuario3', 'usu3', 'usuario3@gmail.com', 1, '2002-03-17', 'Viean', 8, 'foto3.jpg', 3),
('usuario4', 'usu4', 'usuario4@gmail.com', 2, '2003-04-18', 'Lituania city', 7, 'foto4.jpg', 4),
('usuario5', 'usu5', 'usuario5@gmail.com', 1, '2004-05-19', 'Berlín', 5, 'foto4.jpg', 5);

-- anuncios
INSERT INTO Anuncios (TAnuncio, TVivienda, FPrincipal, Alternativo, Titulo, Precio, Texto, Ciudad, Pais, Superficie, NHabitaciones, NBanyos, Planta, Anyo, Usuario) VALUES
(1, 2, 'anuncio1.jpg', 'Casa moderna en Alicante', 'Piso moderno centro Alicante', 250000, 'Bonito piso en el centro de Alicante con buenas vistas.', 'Alicante', 1, 85.50, 3, 2, 2, 2025, 1),
(2, 2, 'anuncio2.jpg', 'Piso en Valencia', 'Apartamento alquiler Valencia', 800, 'Piso en Valencia.', 'Valencia', 1, 65.00, 2, 1, 1, 2015, 2),
(1, 3, 'anuncio3.jpg', 'Oficina en Orihuela', 'Oficina alquilable en Orihuela', 1200, 'Oficina completamente equipada.', 'Orihuela', 1, 120.00, 0, 1, 3, 2018, 3),
(2, 4, 'anuncio4.jpg', 'Local comercial', 'Local en alquiler en Madrid', 1500, 'Local para cualquie cosa.', 'Madrid', 1, 200.00, 0, 1, 0, 2010, 4),
(1, 5, 'anuncio5.jpg', 'Garaje grande', 'Garaje en Málaga', 45000, 'Garaje en las afueras de Málaga.', 'Málaga', 1, 20.00, 0, 0, -1, 2015, 5),
(2, 1, 'anuncio6.jpg', 'Piso acogedor', 'Piso alquiler Alicante centro recién construido', 650, 'Piso acogedor construido en 2025 y con vistas al mar', 'Alicante', 1, 75.00, 2, 1, 0, 2005, 1),
(1, 2, 'anuncio7.jpg', 'Casa con piscina', 'Chalet con piscina Galicia', 450000, 'Hermoso chalet con piscina y cabras.', 'Galicia', 1, 200.00, 4, 3, 0, 2019, 2),
(2, 3, 'anuncio8.jpg', 'Oficina con vistas', 'Oficina totalmente preparada con vistas al río', 1400, 'Oficina amueblada con vistas al río Segura.', 'Murcia', 1, 110.00, 0, 1, 5, 2017, 3);

-- fotos adicionales para anuncios
INSERT INTO Fotos (Titulo, Foto, Alternativo, Anuncio) VALUES
('Foto sala', 'anuncio1_foto2.jpg', 'Sala de estar del piso', 1),
('Foto cocina', 'anuncio1_foto3.jpg', 'Cocina moderna equipada', 1),
('Foto dormitorio', 'anuncio2_foto2.jpg', 'Dormitorio principal', 2),
('Foto exterior', 'anuncio3_foto2.jpg', 'Fachada del edificio', 3),
('Foto mostrador', 'anuncio4_foto2.jpg', 'Área de atención al público', 4),
('Foto jardín', 'anuncio7_foto2.jpg', 'Piscina y zona de jardín', 7),
('Foto terraza', 'anuncio7_foto3.jpg', 'Terraza con vistas', 7);

-- mensajes de prueba
INSERT INTO Mensajes (TMensaje, Texto, Anuncio, UsuOrigen, UsuDestino) VALUES
(1, 'Hola, me interesa saber más detalles sobre este piso.', 1, 2, 1),
(2, 'Me gustaría concertar una cita para verlo.', 1, 3, 1),
(3, 'Tengo una oferta mejor para este inmueble.', 2, 4, 2),
(1, 'El precio es negociable?', 3, 5, 3),
(2, 'Quiero visitarlo mañana a las 10:00', 4, 1, 4);

-- solicitudes de folleto
INSERT INTO Solicitudes (Anuncio, Texto, Nombre, Email, Direccion, Telefono, Color, Copias, Resolucion, Fecha, IColor, IPrecio) VALUES
(1, 'Necesito un folleto a color para presentar la casa', 'Usuario1', 'usuario1@gmail.com', 'Calle NoSeDonde 123', '123456789', 'Color', 5, 300, '2025-11-16', TRUE, TRUE), -- ejemplo con color 5 copias la resolucion a 300 y con el color y precio
(2, 'Folleto en blanco y negro sencillo', 'Usuario2', 'usuario2@gmail.com', 'Avenida DeAlgunSitio 456', '987654321', 'BlancoyNegro', 10, 150, '2025-11-15', FALSE, TRUE), -- blanco y negro 10 copias a 150 siin color y con precio
(3, 'Folletos a color con planos de la casa', 'Usuario3', 'usuario3@gmail.com', 'Plaza Mayor 789', '555666777', 'Color', 3, 900, '2025-11-16', TRUE, TRUE); -- color a 900 3 copias color y preicio
