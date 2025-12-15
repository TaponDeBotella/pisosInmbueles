<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $title="Respuesta baja";
    $acceder = "Mi perfil";
    $css="css/respuesta_borrar_anuncio.css";

    $baja_procesada = false;
    $error_mensaje = '';
    $id_usuario = $_SESSION['id_usuario'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST['clave_confirmacion'])){ // si no hay ninguna clave se manda error
         
            header('Location: ' . $_SERVER['HTTP_REFERER']."?error");
            exit; 
        } else {
       
            $clave_confirmacion = $_POST['clave_confirmacion']; // se saca la contrasenya que ha metido el usuario

            // se saca la contrasenya actual del usuario
            $stmt_clave = $db->prepare("SELECT Clave FROM Usuarios WHERE IdUsuario = ?");
            
            if (!$stmt_clave) { // si hay error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else {
                $stmt_clave->bind_param('i', $id_usuario); // se le vincula el parametro
                
                if (!$stmt_clave->execute()) { // si hay error se manda tambein
                    $error_mensaje = 'Error al verificar la contraseña: ' . $stmt_clave->error;
                } else { // si no hay error entonces se obtiene el resultado
                    $resultado = $stmt_clave->get_result(); // se le sacan los resultados
                    
                    if ($resultado->num_rows > 0) { // si hay algun resultado entonces se saca la fila
                        $usuario = $resultado->fetch_array(MYSQLI_ASSOC);
                        
                        // se verifica que la contrasenya introducida es correcta usando password_verify
                        if (password_verify($clave_confirmacion, $usuario['Clave'])) {
                            // se sacan todos los anuncios del usuario para borrar sus fotos primero
                            $stmt_anuncios = $db->prepare("SELECT IdAnuncio FROM Anuncios WHERE Usuario = ?"); // se prepara la query del select
                            $stmt_anuncios->bind_param('i', $id_usuario);
                            $stmt_anuncios->execute();
                            $resultado_anuncios = $stmt_anuncios->get_result(); // y se le sacan los resultados
                            
                            $fotos_borradas = 0;
                            $anuncios_borrados = 0;
                            
                            // primero se saca la foto del usuario para eliminarla de la base de datos
                            $stmt_foto_perfil = $db->prepare("SELECT Foto FROM Usuarios WHERE IdUsuario = ?");
                            if ($stmt_foto_perfil) {
                                $stmt_foto_perfil->bind_param('i', $id_usuario);
                                $stmt_foto_perfil->execute();
                                $resultado_foto_perfil = $stmt_foto_perfil->get_result();
                                
                                if ($fila_foto_perfil = $resultado_foto_perfil->fetch_assoc()) {
                                    $rutaFotoPerfil = $fila_foto_perfil['Foto'];
                                    // se elimina de la carpeta fisica tambien
                                    if (!empty($rutaFotoPerfil) && strpos($rutaFotoPerfil, 'fotosSubidas/') === 0 && file_exists($rutaFotoPerfil)) {
                                        unlink($rutaFotoPerfil);
                                    }
                                }
                                $stmt_foto_perfil->close();
                            }
                            
                            // ahora borra fotos de todos los anuncios
                            while ($anuncio = $resultado_anuncios->fetch_array(MYSQLI_ASSOC)) {
                                $id_anuncio = $anuncio['IdAnuncio'];
                                
                                // se sacan las fotos del anuncio para eliminar los archivos fisicos
                                $stmt_get_fotos = $db->prepare("SELECT Foto FROM Fotos WHERE Anuncio = ?");
                                if ($stmt_get_fotos) {
                                    $stmt_get_fotos->bind_param('i', $id_anuncio);
                                    $stmt_get_fotos->execute();
                                    $resultado_fotos_anuncio = $stmt_get_fotos->get_result();
                                    
                                    // se eliminan los archivos fisicos
                                    while ($fila_foto = $resultado_fotos_anuncio->fetch_assoc()) {
                                        $rutaFoto = $fila_foto['Foto'];
                                        if (!empty($rutaFoto) && strpos($rutaFoto, 'fotosSubidas/') === 0 && file_exists($rutaFoto)) {
                                            unlink($rutaFoto);
                                        }
                                    }
                                    $stmt_get_fotos->close();
                                }
                                
                                // ahora se eliminan las fotos de la base de datos
                                $stmt_fotos = $db->prepare("DELETE FROM Fotos WHERE Anuncio = ?"); // se prepara el delete
                                $stmt_fotos->bind_param('i', $id_anuncio);
                                if ($stmt_fotos->execute()) { 
                                    $fotos_borradas += $stmt_fotos->affected_rows; // se cuentan las fotos borradas
                                }
                                $stmt_fotos->close();
                            }
                            
                            $stmt_anuncios->close();
                            
                            // se le borran los mensajes relacionados con el usuario
                            $stmt_mensajes = $db->prepare("DELETE FROM Mensajes WHERE UsuOrigen = ? OR UsuDestino = ?");
                            $stmt_mensajes->bind_param('ii', $id_usuario, $id_usuario);
                            $stmt_mensajes->execute();
                            $stmt_mensajes->close();
                            
                            // las solicitudes de folelto se borran tambien
                            $stmt_solicitudes = $db->prepare("DELETE FROM Solicitudes WHERE Anuncio IN (SELECT IdAnuncio FROM Anuncios WHERE Usuario = ?)");
                            $stmt_solicitudes->bind_param('i', $id_usuario);
                            $stmt_solicitudes->execute();
                            $stmt_solicitudes->close();
                            
                            // todos los anuncios se le borran
                            $stmt_anuncios_delete = $db->prepare("DELETE FROM Anuncios WHERE Usuario = ?");
                            $stmt_anuncios_delete->bind_param('i', $id_usuario);
                            if ($stmt_anuncios_delete->execute()) {
                                $anuncios_borrados = $stmt_anuncios_delete->affected_rows;
                            }
                            $stmt_anuncios_delete->close();
                            
                            // y por ultimo se borra el usuari como tal
                            $stmt_usuario = $db->prepare("DELETE FROM Usuarios WHERE IdUsuario = ?");
                            $stmt_usuario->bind_param('i', $id_usuario);
                            
                            if (!$stmt_usuario->execute()) {
                                $error_mensaje = 'Error al eliminar la cuenta: ' . $stmt_usuario->error;
                            } else {
                                $baja_procesada = true;
                                // tambien se le hace logout y se le destruye la session
                                session_destroy();
                            }
                            
                            $stmt_usuario->close();
                        } else {
                            $error_mensaje = 'La contraseña introducida no es correcta';
                        }
                    } else {
                        $error_mensaje = 'Usuario no encontrado';
                    }
                }
                
                $stmt_clave->close();
            }
        }
    }

    include 'includes/header.php';
?>

        <h1>Respuesta eliminación de cuenta</h1>

        <?php
            if (!empty($error_mensaje)) {
                echo '<section class="error">';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '<a href="mi_perfil.php" class="boton">Volver</a>';
                echo '</section>';
            } elseif ($baja_procesada) {
                echo '<section class="exito">';
                echo '<p>Tu cuenta ha sido eliminada correctamente.</p>';
                echo '<p>Se han eliminado todos tus datos, anuncios y fotos asociadas.</p>';
                echo '<nav>';
                echo '<a href="index.php" class="boton">Volver al inicio</a>';
                echo '</nav>';
                echo '</section>';
            } else {
                echo '<p>No hay datos para procesar.</p>';
            }
        ?>
   
<?php
    include 'includes/footer.php';
?>
