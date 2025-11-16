<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Mis mensajes";
    $acceder = "Mi perfil";
    $css="css/mis_mensajes.css";
    include 'includes/header.php';
    
    // primero se obtiene el id del usuario que esta logueado
    $usuarioID = $_SESSION['id_usuario'];
    
    // se hace la query para sacar todos los mensajes enviados por el usuario
    $queryEnviados = "SELECT m.IdMensaje, m.Texto, m.FRegistro, tm.NomTMensaje, u.NomUsuario 
                      FROM Mensajes m 
                      JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje 
                      JOIN Usuarios u ON m.UsuDestino = u.IdUsuario 
                      WHERE m.UsuOrigen = " . intval($usuarioID) . " 
                      ORDER BY m.FRegistro DESC";
    $resultadoEnviados = $db->query($queryEnviados); // se hace la query 
    if (!$resultadoEnviados) { // si no hay se lanza error
        die('Error: ' . $db->error);
    }
    $mensajesEnviados = [];
    while ($fila = $resultadoEnviados->fetch_array(MYSQLI_ASSOC)) { // si si que hay se almacenan en un array
        $mensajesEnviados[] = $fila;
    }
    
    // ahora se consultan los mensajes recibidos por el usuario, lo mismo que en lo de antes pero cambiando usuOrigen por usuDestino
    $queryRecibidos = "SELECT m.IdMensaje, m.Texto, m.FRegistro, tm.NomTMensaje, u.NomUsuario 
                       FROM Mensajes m 
                       JOIN TiposMensajes tm ON m.TMensaje = tm.IdTMensaje 
                       JOIN Usuarios u ON m.UsuOrigen = u.IdUsuario 
                       WHERE m.UsuDestino = " . intval($usuarioID) . " 
                       ORDER BY m.FRegistro DESC";
    $resultadoRecibidos = $db->query($queryRecibidos); // se hace la query
    if (!$resultadoRecibidos) { // si no hay se lanza error
        die('Error: ' . $db->error);
    }
    $mensajesRecibidos = [];
    while ($fila = $resultadoRecibidos->fetch_array(MYSQLI_ASSOC)) { // se guardan tambien en una array
        $mensajesRecibidos[] = $fila;
    }
    
    // se cuentan los mensajes totales
    $totalEnviados = count($mensajesEnviados);
    $totalRecibidos = count($mensajesRecibidos);
?>
        <h1>Mis mensajes</h1>
        <section>
            <h2>ENVIADOS (<?php echo $totalEnviados; ?> mensajes)</h2> <!--aqui se muestran los mensajes enviados  -->
            <?php if (!empty($mensajesEnviados)): ?>
                <?php foreach ($mensajesEnviados as $mensaje): ?> <!-- se recorre todo el array y se muestrna -->
                    <article class="todo_el_mensaje">
                        <h3><?php echo htmlspecialchars($mensaje['NomTMensaje']); ?></h3> <!--se muestra el titulo del mensaje -->
                        <article class="user_time">
                            <p class="nombre"><strong><?php echo htmlspecialchars($mensaje['NomUsuario']); ?></strong></p> <!-- se muestra el nombre del usuario al que se le envio el mensaje -->
                            <p class="fecha"><time datetime="<?php echo date('Y-m-d H:i', strtotime($mensaje['FRegistro'])); ?>"><?php echo date('d-m-Y H:i', strtotime($mensaje['FRegistro'])); ?></time></p> <!-- y la fecha -->
                        </article>
                        <p class="cont_mensaje">
                            <?php echo htmlspecialchars($mensaje['Texto']); ?> <!--se muestra el mensaje como tal -->
                        </p>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tienes mensajes enviados.</p> <!-- si no hay se muestra esto -->
            <?php endif; ?>
        </section>
          
        <section>
            <h2>RECIBIDOS (<?php echo $totalRecibidos; ?> mensajes)</h2> <!-- lo mismo que con los enviados pero con los recibidos -->
            <?php if (!empty($mensajesRecibidos)): ?>
                <?php foreach ($mensajesRecibidos as $mensaje): ?>
                    <article class="todo_el_mensaje">
                        <h3><?php echo htmlspecialchars($mensaje['NomTMensaje']); ?></h3>
                        <article class="user_time">
                            <p class="nombre"><strong><?php echo htmlspecialchars($mensaje['NomUsuario']); ?></strong></p>
                            <p class="fecha"><time datetime="<?php echo date('Y-m-d H:i', strtotime($mensaje['FRegistro'])); ?>">
                                <?php echo date('d-m-Y H:i', strtotime($mensaje['FRegistro'])); ?></time></p>
                        </article>
                        <p class="cont_mensaje">
                            <?php echo htmlspecialchars($mensaje['Texto']); ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tienes mensajes recibidos.</p>
            <?php endif; ?>
        </section>
        
<?php
    include 'includes/footer.php';
?>