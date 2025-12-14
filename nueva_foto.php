    <?php
    require_once 'includes/proteger.php';
    require_once 'includes/iniciarDB.php';
    
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Añadir foto";
    $acceder = "Acceder";
    $css="css/ver_anuncio.css";
    include 'includes/header.php';
    
    // Obtener los anuncios del usuario logueado desde la base de datos
    $usuario_actual = $_SESSION['nombre'];
    $sql = "SELECT IdAnuncio, Titulo FROM Anuncios WHERE Usuario = (SELECT IdUsuario FROM Usuarios WHERE NomUsuario = ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $usuario_actual);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $anuncios_del_usuario = [];
    while($fila = $resultado->fetch_assoc()) {
        $anuncios_del_usuario[] = $fila;
    }
    
    // Preseleccionar el primer anuncio si existe
    $anuncio_seleccionado = !empty($anuncios_del_usuario) ? $anuncios_del_usuario[0]['IdAnuncio'] : null;
        
    ?>
        <script src="js/otras_funciones.js"></script>

        <h1>Añadir foto a anuncio</h1>
        
        <?php
            // mostrar los errores si los hay
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                $mensajes_error = array(
                    'titulo_vacio' => 'El título de la foto no puede estar vacío.',
                    'texto_alternativo_vacio' => 'El texto alternativo no puede estar vacío.',
                    'texto_alternativo_corto' => 'El texto alternativo debe tener al menos 10 caracteres.',
                    'texto_alternativo_redundante' => 'El texto alternativo no puede empezar por palabras como "foto", "imagen", etc.',
                    'anuncio_vacio' => 'Debes seleccionar un anuncio.',
                    'anuncio_no_pertenece' => 'El anuncio no te pertenece.',
                    'foto_requerida' => 'Debes seleccionar una foto para subir.'
                );
                
                $mensaje = isset($mensajes_error[$error]) ? $mensajes_error[$error] : 'Error desconocido.';
                echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($mensaje) . '</p>';
            }
        ?>

        <form method="post" action="respuesta_nueva_foto.php" enctype="multipart/form-data">   
            <section>
                <h3>¿Añadir una nueva foto?</h3>
                <label for="labelAnuncioAElegir">Elige el anuncio al que quieres añadir la foto</label>
                <select class="input_select" name="anuncio" id="anuncio">
                    <?php
                        if(sizeof($anuncios_del_usuario) === 0) {
                            echo '<option disabled selected>No tienes anuncios</option>';
                        } else {
                            for($i=0; $i<sizeof($anuncios_del_usuario); $i++){
                                $cadena = '<option';
                                if($anuncios_del_usuario[$i]['IdAnuncio'] == $anuncio_seleccionado) 
                                    $cadena .= ' selected';

                                $cadena .= ' value="'.htmlspecialchars($anuncios_del_usuario[$i]['IdAnuncio']).'">'.htmlspecialchars($anuncios_del_usuario[$i]['Titulo']).'</option>';

                                echo $cadena;
                            }
                        }
                    ?>
                </select>
                
                <label for="labelTitulo">Título de la foto</label>
                <input class="input_select" required type="text" id="titulo" name="titulo">
                
                <label for="labelTextoAlternativo">Texto alternativo</label>
                <textarea class="input_select" required minlength="10" name="textoAlternativo" id="textoAlternativo"></textarea>
                
                <label for="labelFoto">Archivo de la foto</label>
                <label for="foto" class="boton" id="examinar">Examinar </label>
                <input id="foto" type="file" style="display:none;" name="foto" required accept="image/*">

                <input type="submit" class="boton">
            </section>
        </form>

<?php
    include 'includes/footer.php';
?>
    
    