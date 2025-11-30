<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $title="Respuesta configurar";
    $acceder = "Mi perfil";
    $css="css/respuesta_borrar_anuncio.css"; // como es la misma estructura que la de esa pagina lo reutilizo y me ahorro hacer un css

    $configuracion_actualizada = false; // para saber si se ha actualizado bien o no
    $error_mensaje = ''; // para mostrar errores
    $estilo_seleccionado = ''; // para guardar el estilo seleccionado
    $estilo_nombre = ''; // para guardar el nombre del estilo seleccionado

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // se comprueba si se ha llamado al post

        if (empty($_POST['estilo'])){ // si no hay ningun estilo seleccionado se manda error
         
            header('Location: ' . $_SERVER['HTTP_REFERER']."?error");
            exit; 
        } else { 
       
            $estilo_seleccionado = $_POST['estilo']; // se saca el estilo seleccionado
            $id_usuario = $_SESSION['id_usuario']; // y el id del usuario

            // saco los estilos de la base de datos para ver cual ha elegido el usuario
            $stmt_validar = $db->prepare("SELECT IdEstilo, Nombre, Fichero FROM Estilos WHERE Fichero = ?"); // se prepara la query
            
            if (!$stmt_validar) { // si hay error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else {
                $stmt_validar->bind_param('s', $estilo_seleccionado); // se le vincula el parametro
                
                if (!$stmt_validar->execute()) { // si hay error al ejecutar se manda el error
                    $error_mensaje = 'Error al validar el estilo: ' . $stmt_validar->error;
                } else {
                    $resultado = $stmt_validar->get_result(); // se sacan los resultados
                    
                    if ($resultado->num_rows > 0) { // si hay algun resultado es que el estilo es valido
                        $estilo_valido = $resultado->fetch_array(MYSQLI_ASSOC); // se saca la fila 
                        $id_estilo = $estilo_valido['IdEstilo']; // y se le saca el id
                        $estilo_nombre = $estilo_valido['Nombre']; // y el nombre
                        
                        // ahora se prepara el update para cambiarle el estilo al usuario
                        $stmt = $db->prepare("UPDATE Usuarios SET Estilo = ? WHERE IdUsuario = ?");
                        
                        if (!$stmt) { // si hay errrro se manda
                            $error_mensaje = 'Error en la preparación: ' . $db->error;
                        } else {
                            $stmt->bind_param('ii', $id_estilo, $id_usuario); // y se le vinculan los parametros
                            
                            if (!$stmt->execute()) { // si hay error se manda tambien
                                $error_mensaje = 'Error al actualizar el estilo: ' . $stmt->error;
                            } else { // si no entonces se cambia el estilo bien y en el session tambien
                                $configuracion_actualizada = true;
                                $_SESSION['estilo'] = $estilo_seleccionado;
                            }
                            
                            $stmt->close(); // se cierra el update
                        }
                    } else {
                        $error_mensaje = 'El estilo seleccionado no es válido';
                    }
                }
                
                $stmt_validar->close(); // se cierra el select
            }
        }
    }

    include 'includes/header.php';
?>

        <h1>Respuesta configuración</h1>

        <?php
            if (!empty($error_mensaje)) { // si hay error se muestra
                echo '<section>';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '</section>';
            } elseif ($configuracion_actualizada) { // si si que se ha actualizado bien entonces se muestra el mensjae de respuesta
                echo '<section>';
                echo '<p>Configuración actualizada con éxito</p>';
        ?>
                <p>
                    <strong>Estilo seleccionado:</strong> <?php echo htmlspecialchars($estilo_nombre); ?>
                </p>
                <nav>
                    <a href="configurar.php" class="boton">Volver</a>
                </nav>
        <?php
                echo '</section>';
            } else { // por si se accede por url sin post
                echo '<p>No hay datos para procesar.</p>';
            }
        ?>
   
<?php
    include 'includes/footer.php';
?>
