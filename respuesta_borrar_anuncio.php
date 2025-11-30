<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $title = "Respuesta borrado";
    $acceder = "Mi perfil";
    $css = "css/respuesta_borrar_anuncio.css";
    include 'includes/header.php';
    
    $anuncio_borrado = false; // para saber si se ha borrado el anuncio bien o no
    $error_mensaje = ''; // para mandar el mensaje de error si hay algun problema
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // se comprueba si ha habido un post y se le sacan todos los datos
        $idAnuncio = isset($_POST['idAnuncio']) ? (int)$_POST['idAnuncio'] : 0;
        $idUsuario = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0;
        
        if ($idAnuncio <= 0 || $idUsuario <= 0) { // si no se existe el id de anuncio o de usuario se manda error
            $error_mensaje = 'Error: Faltan datos necesarios';
        } else { // si no hay ningun error entonces se borra el anuncio
            $stmt_verificar = $db->prepare(
                "SELECT IdAnuncio FROM Anuncios WHERE IdAnuncio = ? AND Usuario = ?"
            ); // se prepara la query 
            
            if (!$stmt_verificar) { // si hay algun error se manda el error
                $error_mensaje = 'Error: ' . $db->error;
            } else { // si no entonces se verifica todo ys e borra
                $stmt_verificar->bind_param('ii', $idAnuncio, $idUsuario); // se le vinculan los parametros que son dos ints
                $stmt_verificar->execute();
                $resultado = $stmt_verificar->get_result(); // y se le sacan los resultados
                
                if ($resultado->num_rows === 0) { // si no hay resultados es porque el anuncio no es del usuario que quiere borrarlo  (que no deberia pasar pero por si acaso lo compruebo)
                    $error_mensaje = 'Error: No tienes permiso para borrar este anuncio';
                } else { // si si que es un anuncio del usuario entonces se borra
                    $stmt_fotos = $db->prepare("DELETE FROM Fotos WHERE Anuncio = ?"); // se prepara el delete
                    if ($stmt_fotos) {  // se borran las fotos relacionadas con el anuncio
                        $stmt_fotos->bind_param('i', $idAnuncio);
                        $stmt_fotos->execute();
                        $stmt_fotos->close();
                    }
                    
                    $stmt_mensajes = $db->prepare("DELETE FROM Mensajes WHERE Anuncio = ?"); // se prepara el delete de mensajes
                    if ($stmt_mensajes) {
                        $stmt_mensajes->bind_param('i', $idAnuncio);
                        $stmt_mensajes->execute();
                        $stmt_mensajes->close();
                    }
            
                    $stmt_delete = $db->prepare("DELETE FROM Anuncios WHERE IdAnuncio = ?"); // y se prepara el delete del anuncio
                    
                    if (!$stmt_delete) { // si por lo que sea falla se manda el error
                        $error_mensaje = 'Error en la preparación: ' . $db->error;
                    } else { // si no falla entonces se vincula y se borra
                        $stmt_delete->bind_param('i', $idAnuncio);
                        
                        if (!$stmt_delete->execute()) { // si no funciona el borrado se manda un error tambien
                            $error_mensaje = 'Error al borrar el anuncio: ' . $stmt_delete->error;
                        } else { // si si que va el delete entonces se comprueba si se ha borrado de verdad con las filas afectadas
                            $filas_afectadas = $stmt_delete->affected_rows; // se le sacan las filas afectadas
                            if ($filas_afectadas > 0) { // si hay alguna entonces es que si que se ha borrado
                                $anuncio_borrado = true;
                            } else { // si no es que entonces ha habiado algun problema y se manda el error
                                $error_mensaje = 'No se encontró el anuncio a borrar';
                            }
                        }
                        
                        $stmt_delete->close(); // se cierra el delete
                    }
                }
                
                $stmt_verificar->close(); // se cierra el select
            }
        }
    }
?>
        <h1>Respuesta borrado</h1>
        
        <?php
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section>';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '<a href="mis_anuncios.php" class="boton">Volver a mis anuncios</a>';
                echo '</section>';
            } elseif ($anuncio_borrado) { // si no hay error entonces se muestra el mensaje de que se ha borrado con exito y se anyade un boton para volver a mis anuncios
                echo '<section>';
                echo '<h2>Anuncio borrado con éxito</h2>';
                echo '<p>El anuncio ha sido eliminado de la base de datos correctamente.</p>';
                echo '<p>Todas las fotos y mensajes relacionados también han sido eliminados.</p>';
                echo '<a href="mis_anuncios.php" class="boton">Volver a mis anuncios</a>';
                echo '</section>';
            } else { // si no hay ni error ni se ha borrado entonces no hay nada que mostrar (vamos, esto solo se ve si se mete a esta pagina por url sin hacer un post)
                echo '<p>No hay datos para procesar.</p>';
                echo '<a href="mis_anuncios.php" class="boton">Volver a mis anuncios</a>';
            }
        ?>

<?php
    include 'includes/footer.php';
?>
