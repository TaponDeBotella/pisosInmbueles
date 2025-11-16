<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se comprueba si el usuario esta logueado
    
    $title="Enviar mensaje";
    $acceder = "Acceder";
    $css="css/enviar_mensaje.css";
    $css_errores = "css/errores.css";
    include 'includes/header.php';
    
    $texto_error = false;
    
    // primero se comprueba si en la url hay un error
    if(isset($_GET['error'])) {
        if($_GET['error'] == 'texto_vacio') {
            $texto_error = true;
        }
    }
    
    // se obtienen los tipos de mensajes de la base de datos
    $resultadoTiposMensajes = $db->query('SELECT IdTMensaje, NomTMensaje FROM TiposMensajes');
    if (!$resultadoTiposMensajes) {
        die('Error: ' . $db->error);
    }
    $tiposMensajes = [];
    while ($fila = $resultadoTiposMensajes->fetch_array(MYSQLI_ASSOC)) {
        $tiposMensajes[] = $fila;
    }
?>

        <h1>Enviar mensaje</h1>
        <form action="respuesta_mensaje.php" method="POST">
            <label id="texto_mensaje">Texto del mensaje</label>
            <textarea name="texto_mensaje">
                
            </textarea>
            <?php 
                if($texto_error) // si hay un error entonces se le muestra al usuario el mensaje y no se le permite enviar el texto vacio
                    echo '<span class="mensaje-error">El mensaje no puede estar vacío</span>'; 
            ?>
            <label id="tipo_mensaje">Tipo de mensaje</label>
            <select class="boton" id="msg_type" name="msg_type">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($tiposMensajes as $tipo): ?> <!-- se recorre todo el array y se ponen las opciones en el select -->
                    <option value="<?php echo $tipo['IdTMensaje']; ?>">
                        <?php echo $tipo['NomTMensaje']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input class="boton" type="submit" value="Enviar mensaje">            
        </form>

<?php
    include 'includes/footer.php';
?>