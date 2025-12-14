<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $title="Respuesta borrar foto";
    $acceder = "Mi perfil";
    $css="css/respuesta_borrar_anuncio.css"; // como es lo mismo uso el mismo css 

    $foto_borrada = false; // para saber si se ha borrado bien o no
    $error_mensaje = ''; // para almacenar mensajes de error
    $id_usuario = $_SESSION['id_usuario']; // id del usuario logueado

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // se comprueba si ha habido un post

        if (empty($_POST['idFoto']) || empty($_POST['idAnuncio'])){ // si falta alguno de los campos obligatorios se manda error
         
            header('Location: ' . $_SERVER['HTTP_REFERER']."?error");
            exit; 
        } else {
       
            $idFoto = intval($_POST['idFoto']); // se saca el id de la foto a borrar
            $idAnuncio = intval($_POST['idAnuncio']); // se saca el id del anuncio tambien

            // se verifica que la foto pertenece a un anuncio del usuario porque si no no tiene sentido que pueda borrarla
            $stmt_verificar = $db->prepare( 
                "SELECT f.IdFoto FROM Fotos f 
                 INNER JOIN Anuncios a ON f.Anuncio = a.IdAnuncio 
                 WHERE f.IdFoto = ? AND a.Usuario = ?"
            ); // se prepara el select de las fotos que pertenecen a anuncios del usuario
            
            if (!$stmt_verificar) { // si hay algun error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else { // si no hay error se continua para borrar la foto
                $stmt_verificar->bind_param('ii', $idFoto, $id_usuario); // se vinculan los parametros
                
                if (!$stmt_verificar->execute()) {
                    $error_mensaje = 'Error al verificar la foto: ' . $stmt_verificar->error;
                } else {
                    $resultado = $stmt_verificar->get_result(); // y se le sacan los resultados
                    
                    if ($resultado->num_rows > 0) { // si hay resultados significa que la foto pertenece al usuario
                        // primero se saca la ruta de la foto para eliminar el archivo fisico
                        $stmt_get_foto = $db->prepare("SELECT Foto FROM Fotos WHERE IdFoto = ?");
                        if ($stmt_get_foto) {
                            $stmt_get_foto->bind_param('i', $idFoto);
                            $stmt_get_foto->execute();
                            $resultado_foto = $stmt_get_foto->get_result();
                            
                            if ($fila_foto = $resultado_foto->fetch_assoc()) {
                                $rutaFoto = $fila_foto['Foto'];
                                // Solo eliminar si la foto está en fotosSubidas y el archivo existe
                                if (!empty($rutaFoto) && strpos($rutaFoto, 'fotosSubidas/') === 0 && file_exists($rutaFoto)) {
                                    unlink($rutaFoto);
                                }
                            }
                            $stmt_get_foto->close();
                        }
                        
                        // Ahora eliminar la foto de la base de datos
                        $stmt_delete = $db->prepare("DELETE FROM Fotos WHERE IdFoto = ?"); // se prepara el delete
                        
                        if (!$stmt_delete) {
                            $error_mensaje = 'Error en la preparación: ' . $db->error;
                        } else {
                            $stmt_delete->bind_param('i', $idFoto); 
                            
                            if (!$stmt_delete->execute()) {
                                $error_mensaje = 'Error al eliminar la foto: ' . $stmt_delete->error;
                            } else {
                                $foto_borrada = true; 
                            }
                            
                            $stmt_delete->close();
                        }
                    } else { // si no es su anuncio entonces no le deja borrar la foto
                        $error_mensaje = 'No tienes permiso para borrar esta foto';
                    }
                }
                
                $stmt_verificar->close();
            }
        }
    }

    include 'includes/header.php';
?>

        <h1>Respuesta eliminación de foto</h1>

        <?php
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section class="error">';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '</section>';
            } elseif ($foto_borrada) { // si no entonces se muestra la respuesta de que el usuario ha borrado la foto bien
                echo '<section class="exito">';
                echo '<p>La foto ha sido eliminada correctamente.</p>';
                echo '<nav>';
                echo '<a href="ver_fotos.php?idAnuncio=' . htmlspecialchars($_POST['idAnuncio']) . '" class="boton">Volver</a>';
                echo '</nav>';
                echo '</section>';
            } else { // esto es si se accede por url sin post
                echo '<p>No hay datos para procesar.</p>';
            }
        ?>
   
<?php
    include 'includes/footer.php';
?>
