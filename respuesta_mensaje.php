<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $mensaje_enviado = false; // por si hay que mandar algun error o algo   
    $error_mensaje = ''; // para guardar el tipo de error
    $tipo_mensaje_nombre = ''; // para mostrar el tipo de mensaje enviado
    $texto_mensaje = ''; // para mostrar el texto del mensaje como tal que ha puesto el usuario
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // se comprueba si ha habido un post y se le sacan todos los datos
        $texto_mensaje = isset($_POST['texto_mensaje']) ? trim($_POST['texto_mensaje']) : ''; // se guarda el mensaje
        $tipo_mensaje = isset($_POST['msg_type']) ? (int)$_POST['msg_type'] : 0; // y el tipo de mensaje
        $idAnuncio = isset($_POST['idAnuncio']) ? (int)$_POST['idAnuncio'] : 0; // y id del anuncio
        $idUsuarioDestino = isset($_POST['idUsuarioDestino']) ? (int)$_POST['idUsuarioDestino'] : 0; // id del usuario destino al que se le manda
        $idUsuarioOrigen = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0; // id del usuario origen el que lo envia
        
        // si el mensaje esta vacio se manda el error al enviar mensaje para que lo muestre
        if (empty($texto_mensaje)) {
            header('Location: enviar_mensaje.php?error=texto_vacio&idAnuncio=' . $idAnuncio . '&idUsuarioDestino=' . $idUsuarioDestino);
            exit;
        }
        
        // se comprieba que todos los datos estan bien
        if ($idAnuncio <= 0 || $idUsuarioDestino <= 0 || $tipo_mensaje <= 0 || $idUsuarioOrigen <= 0) { // si alguno de los ids no existe se manda error
            $error_mensaje = 'Error: Faltan datos necesarios para enviar el mensaje';
        } else { 
            // se saca el tipo de mensaje para mostrarlo
            $stmt_tipo = $db->prepare('SELECT NomTMensaje FROM TiposMensajes WHERE IdTMensaje = ?'); // se prepara la query 
            if ($stmt_tipo) {
                $stmt_tipo->bind_param('i', $tipo_mensaje); // se le vincula el parametro y se le pone el i porque es un integer
                $stmt_tipo->execute(); // se hace la llamada
                $resultado_tipo = $stmt_tipo->get_result(); // y se saca el resultado de la llamada
                if ($fila_tipo = $resultado_tipo->fetch_assoc()) { // y se valida para ver si coincide con algun tipo de mensaje el id que se ha pasado
                    $tipo_mensaje_nombre = $fila_tipo['NomTMensaje']; // se guarda el nombre para poder mostrarlo luego
                }
                $stmt_tipo->close(); // y se cierra
            }
            
            // ahora se prepara la query para hacer el post a la base de datos con todo lo relacionado con el mensaje que el usuario acaba de mandar
            $stmt = $db->prepare( // se prepara la query otra vez
                "INSERT INTO Mensajes (TMensaje, Texto, Anuncio, UsuOrigen, UsuDestino, FRegistro) 
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            
            if (!$stmt) { // por si por lo que sea hay algun error se muestra
                $error_mensaje = 'Error en la preparación de la sentencia: ' . $db->error;
            } else {
                $stmt->bind_param('isiii', $tipo_mensaje, $texto_mensaje, $idAnuncio, $idUsuarioOrigen, $idUsuarioDestino); // ahora se le vinculan los tipos de parametros y los parametros que son todos ints menos el mensaje que es un string
                
                // y se ejecuta la sentencia
                if (!$stmt->execute()) { // el error por si acaso
                    $error_mensaje = 'Error al enviar el mensaje: ' . $stmt->error;
                } else {
                    $mensaje_enviado = true; // se pone que se ha enviado el mensaje correctamente
                }
                
                $stmt->close();
            }
        }
    }
    
    $title="Respuesta mensaje";
    $acceder = "Mi perfil";
    $css="css/respuesta_mensaje.css";
    include 'includes/header.php';
?>
        <h1>Respuesta mensaje</h1>
        <?php 
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section class="error">' . htmlspecialchars($error_mensaje) . '</section>';
            } elseif ($mensaje_enviado) { // si no entonces se muestra la respuesta con todo lo relacionado con el mensaje enviado
                echo '<h3>Mensaje enviado con éxito</h3>';
                echo '<article>';
                echo '<p>Tu mensaje ha sido enviado correctamente al propietario del anuncio.</p>';
                echo '<p><strong>Tipo de mensaje:</strong> ' . htmlspecialchars($tipo_mensaje_nombre) . '</p>';
                echo '<p><strong>Texto:</strong> ' . htmlspecialchars($texto_mensaje) . '</p>';
                echo '</article>';
                echo '<nav><a href="mis_mensajes.php">Ver mis mensajes</a></nav>';
            } else {
                echo '<p>No hay datos para mostrar.</p>';
            }
        ?>

<?php
    include 'includes/footer.php';
?>