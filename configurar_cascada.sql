-- Configurar borrado en cascada para todas las foreign keys
-- Ejecutar estos comandos en phpMyAdmin o MySQL Workbench

USE pisosbd;

-- 1. ANUNCIOS: cuando se borra un usuario, borrar sus anuncios
ALTER TABLE anuncios 
DROP FOREIGN KEY anuncios_ibfk_4;

ALTER TABLE anuncios 
ADD CONSTRAINT anuncios_ibfk_4 
FOREIGN KEY (Usuario) REFERENCES usuarios(IdUsuario) 
ON DELETE CASCADE;

-- 2. FOTOS: cuando se borra un anuncio, borrar sus fotos
ALTER TABLE fotos 
DROP FOREIGN KEY fotos_ibfk_1;

ALTER TABLE fotos 
ADD CONSTRAINT fotos_ibfk_1 
FOREIGN KEY (Anuncio) REFERENCES anuncios(IdAnuncio) 
ON DELETE CASCADE;

-- 3. MENSAJES: cuando se borra un usuario, borrar mensajes donde es origen
ALTER TABLE mensajes 
DROP FOREIGN KEY mensajes_ibfk_1;

ALTER TABLE mensajes 
ADD CONSTRAINT mensajes_ibfk_1 
FOREIGN KEY (UsuOrigen) REFERENCES usuarios(IdUsuario) 
ON DELETE CASCADE;

-- 4. MENSAJES: cuando se borra un usuario, borrar mensajes donde es destino
ALTER TABLE mensajes 
DROP FOREIGN KEY mensajes_ibfk_2;

ALTER TABLE mensajes 
ADD CONSTRAINT mensajes_ibfk_2 
FOREIGN KEY (UsuDestino) REFERENCES usuarios(IdUsuario) 
ON DELETE CASCADE;

-- 5. SOLICITUDES: cuando se borra un anuncio, borrar sus solicitudes
ALTER TABLE solicitudes 
DROP FOREIGN KEY solicitudes_ibfk_1;

ALTER TABLE solicitudes 
ADD CONSTRAINT solicitudes_ibfk_1 
FOREIGN KEY (Anuncio) REFERENCES anuncios(IdAnuncio) 
ON DELETE CASCADE;

-- VERIFICAR LA CONFIGURACIÓN
-- Puedes ejecutar estas queries para verificar que se aplicó correctamente:
-- SELECT * FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = 'pisosbd';
-- SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'pisosbd';
