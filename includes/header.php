<?php // esto tiene que ir en todos los php, es para poder guardar las sesiones de los usuarios y que se puedan recordar
    
    include __DIR__ . '/iniciarDB.php'; // se abre la conexion con la base de datos
    
    // verificar si hay cookies y si no hay sesion activa
    if(!isset($_SESSION['logueado']) && isset($_COOKIE['recordarme_email']) && isset($_COOKIE['recordarme_password'])) {
        $email_cookie = $_COOKIE['recordarme_email'];
        $password_cookie = $_COOKIE['recordarme_password'];
        
        // busca el usuario en la base de datos
        $query = "SELECT IdUsuario, NomUsuario, Email, Clave, Estilo FROM Usuarios WHERE Email = '" . $email_cookie . "' AND Clave = '" . $password_cookie . "'";
        $resultado = $db->query($query);
        
        if ($resultado && $resultado->num_rows > 0) { // si encuentra el usuario se guarda sus datos
            $usuario_encontrado = $resultado->fetch_array(MYSQLI_ASSOC);
            
            // si existe el usuario se inicia sesion sin que el usuario tenga que hacerlo
            $_SESSION['id_usuario'] = $usuario_encontrado['IdUsuario'];
            $_SESSION['usuario'] = $usuario_encontrado['Email'];
            $_SESSION['nombre'] = $usuario_encontrado['NomUsuario'];
            $_SESSION['estilo'] = $usuario_encontrado['Estilo'];
            $_SESSION['logueado'] = true;
            $_SESSION['es_recordado'] = true;
            
            // se guardar la fecha ANTERIOR IMPORTANTE en la session antes de actualizar la cookie para hacer lo de las multiples visitas recordadno la fecha de la ultima
            if(isset($_COOKIE['recordarme_ultima_visita'])) {
                $_SESSION['ultima_visita_anterior'] = $_COOKIE['recordarme_ultima_visita'];
            }
            
            // se actualiza la hora de la ultima visita
            $duracion_cookie = time() + (90 * 24 * 60 * 60);
            setcookie('recordarme_ultima_visita', date('d/m/Y H:i'), $duracion_cookie, '/', '', false, true);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>">
        <link rel="stylesheet" href="css/header_footer.css">
        <link rel="stylesheet" href="css/errores.css">
        
        <?php
            // primero hago un array con todos los css de los estilos para poder recorrerlos mejor
            $estilos_alternativos = array(
                'predeterminado.css' => 'Estilo predeterminado',
                'letraGrande.css' => 'Estilo de tamaño grande',
                'contraste.css' => 'Estilo de alto contraste',
                'oscuro.css' => 'Modo noche',
                'letraGrandeContraste.css' => 'Estilo de tamaño grande y alto contraste',
                'imprenta.css' => 'Estilo de imprenta'
            );
            
            if(isset($_SESSION['estilo'])) { // si el usuario tiene un estilo guardado entonces se mete a la variable
                $estilo_usuario = $_SESSION['estilo']; // se guarda el estilo del usuario
            } else {
                $estilo_usuario = 'predeterminado.css'; // si no hay se deja el predeterminado
            }
            
            foreach($estilos_alternativos as $archivo => $titulo) { // ahora se recorren todos los estilos para ver cual de todos es el que tiene el usuario
                if($estilo_usuario === $archivo) { // si el estilo coincide con uno de los que tenemos, se le aplica
                    echo '<link rel="stylesheet" type="text/css" href="css/' . $archivo . '" title="' . $titulo . '"/>' . "\n";
                } else { // si no, se le pone como alternativo
                    echo '<link rel="alternate stylesheet" type="text/css" href="css/' . $archivo . '" title="' . $titulo . '"/>' . "\n";
                }
            }
        ?>
        
        <script src="https://kit.fontawesome.com/7cae898421.js" crossorigin="anonymous"></script>  <!-- ESTO EN TODAS LAS PAGINAS PARA QUE VAYAN LOS ICONOS -->
        <script src="<?php echo $js; ?>" defer></script> 
    </head>

    <header>   
        <nav>
            <ul>
                <li class="menu">
                    <nav id="nav_nav">
                        <label for="burger_menu"><p id="menu_busqueda"><i class="fa-solid fa-bars"></i></p></label>
                        <input type="checkbox" id="burger_menu">
                        <ul class="menu_head" >
                            <li><a href="index.php">Inicio</a></li>
                            <li><a href="anuncio.php">Anuncio</a></li>
                            <li><a href="log_registro.php">Menú</a></li>
                            <li><a href="alerta.php">Alerta</a></li>
                            <li><a href="registro.php">Registro</a></li>
                            <li><a href="busqueda.php">Busqueda</a></li>
                            <li><a href="enviar_mensaje.php">Enviar Mensaje</a></li>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="mi_perfil.php">Mi perfil</a></li>
                            <li><a href="mis_mensajes.php">Mis mensajes</a></li>
                            <li><a href="respuesta_folleto.php">Respuesta Folleto</a></li>
                            <li><a href="respuesta_mensaje.php">Respuesta Mensaje</a></li>
                            <li><a href="accesibilidad.php">Accesibilidad</a></li>
                            <li><a href="solicitar_folleto.php">Solicitar Folleto</a></li>
                        </ul>
                    </nav>
                    
                </li>
                <!-- <li id="li_estilos" class="menu">
                    <section id="section_estilo">
                        <label for="menu_estilo"><p><i class="fa-solid fa-circle-half-stroke"></i></p></label>
                        <input type="checkbox" id="menu_estilo">
                        <ul class="menu_head">
                            <li class="estilo_radio"><label for="Pred"><input id="Pred" name="estilo" checked type="radio">Predeterminado</label></li>
                            <li class="estilo_radio"><label for="Osc"><input id="Osc" name="estilo" type="radio">Oscuro</label></li>
                            <li class="estilo_radio"><label for="Imp"><input id="Imp" name="estilo" type="radio">Imprenta</label></li>
                            <li class="estilo_radio"><label for="Acc"><input id="Acc" name="estilo" type="radio">Accesibilidad</label></li>
                        </ul>
                    </section>
                </li> -->
    
                <li id="index">
                    <a href="index.php">Pisos & Inmuebles</a>
                </li>

                <li id="acceder">
                    <?php
                        if(isset($_SESSION['logueado']) && $_SESSION['logueado'] === true){
                            echo '<a href="mi_perfil.php"><i class="fa-solid fa-user-plus"></i>Mi perfil</a>';
                        } else {
                            echo '<a href="log_registro.php"><i class="fa-solid fa-user"></i>Acceder</a>';
                        }
                    ?>
                    
                </li>
            </ul>
        </nav>
    </header>
    
    <body>