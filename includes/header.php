<?php // esto tiene que ir en todos los php, es para poder guardar las sesiones de los usuarios y que se puedan recordar
    
    // verificar si hay cookies y si no hay sesion activa
    if(!isset($_SESSION['logueado']) && isset($_COOKIE['recordarme_email']) && isset($_COOKIE['recordarme_password'])) {
        require_once __DIR__ . '/usuarios.php';
        
        $email_cookie = $_COOKIE['recordarme_email'];
        $password_cookie = $_COOKIE['recordarme_password'];
        
        // se busca el usuario
        $usuario_encontrado = null;
        foreach($usuarios as $usuario) {
            if($usuario['email'] === $email_cookie && $usuario['password'] === $password_cookie) {
                $usuario_encontrado = $usuario;
                break;
            }
        }
        
        // si existe el usuario se inicia sesion sin que el usuario tenga que hacerlo
        if($usuario_encontrado !== null) {
            $_SESSION['usuario'] = $usuario_encontrado['email'];
            $_SESSION['nombre'] = $usuario_encontrado['nombre'];
            $_SESSION['logueado'] = true;
            $_SESSION['es_recordado'] = true; 
            
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
        <link rel="alternate stylesheet" type="text/css" href="css/predeterminado.css" title="Estilo predeterminado"/>
        <link rel="alternate stylesheet" type="text/css" href="css/letraGrande.css" title="Estilo de tamaño grande"/>
        <link rel="alternate stylesheet" type="text/css" href="css/contraste.css" title="Estilo de alto contraste"/>
        <link rel="alternate stylesheet" type="text/css" href="css/oscuro.css" title="Modo noche"/>
        <link rel="alternate stylesheet" type="text/css" href="css/letraGrandeContraste.css" title="Estilo de tamaño grande y alto contraste"/>
        <link rel="alternate stylesheet" type="text/css" href="css/imprenta.css" title="Estilo de imprenta"/>
        <link rel="alternate stylesheet" type="text/css" href="css/imprenta.css" title="Estilo de imprenta pero con media print" media="print"/>
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
                        if($acceder == 'Acceder')
                            echo '<a href="log_registro.php"><i class="fa-solid fa-user-plus"></i>'.htmlspecialchars($acceder).'</a>';
                        else if($acceder == 'Mi perfil')
                            echo '<a href="log_registro.php"><i class="fa-solid fa-user"></i>'.htmlspecialchars($acceder).'</a>';
                    ?>
                    
                </li>
            </ul>
        </nav>
    </header>
    
    <body>