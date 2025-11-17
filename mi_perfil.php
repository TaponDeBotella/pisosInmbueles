<?php
    session_start();
    
    // primero se verifica si el usuario esta logueado ya
    if(!isset($_SESSION['logueado'])) {
        header('Location: login.php?error=no_logueado');
        exit;
    }
    
    // Incluir la función de mensajes personalizados
    require_once 'includes/mensajes.php';
    
    $title="Mi perfil";
    $acceder = "Mi perfil";
    $css="css/mi_perfil.css";
    include 'includes/header.php';
?>
        <h1>Mi perfil</h1>        
        <section>
            <nav>
                <ul>
                    <li>
                        <p>
                            <?php 
                                // el mensaje de bienvenida con el nombre del usuario y la fecha y hora de la ultima visita
                                if(isset($_SESSION['nombre']) && isset($_COOKIE['recordarme_ultima_visita'])) { // se comprueba si hay un nombre en el session y una fecha
                                    // si es autologin desde cookies, se muestra la fecha de la ultima visita
                                    if(isset($_SESSION['ultima_visita_anterior'])){ // depende si hay una sesion recordada se pone una fecha u otra, para que si el usuario se ha logueado el dia 5 a las 13 salga esa fecha en la siguiente visita y luego salga la nueva fecha en la siguiente
                                        $fecha_mostrar = $_SESSION['ultima_visita_anterior']; // si hace autologin
                                    }else {
                                        $fecha_mostrar = $_COOKIE['recordarme_ultima_visita']; // si no hace autologin
                                    }
                                    $saludo = obtenerMensajeBienvenida($_SESSION['nombre']); // se llama a la funcion de los mensajes personalizados dependiendo de la hroa del dia
                                    echo '<h2>' . $saludo . ', tu última visita fue el ' . htmlspecialchars($fecha_mostrar) .'!</h2>'; // y se pone el mensaje en un h2 para que se vea bien y grande
                                }
                            ?>
                        </p>
                    </li>
                    <li id="primer_li"> <!-- se le pasa el nombre del usuario de la session --> 
                        <a href="registro.php" id="mis_datos"><i class="fa-solid fa-address-card"></i>Mis datos</a>
                    </li>
                    <!--<li> esto lo comento porque ya tenemos la opcion de salir, luego se puede usar para eliminar usuarios de la base de datos si las practicas futuras lo piden
                        <a href="index.php"><i class="fa-solid fa-user-minus"></i>Darse de baja</a>
                    </li>-->
                    <li>
                        <a href="mis_anuncios.php"><i class="fa-solid fa-table-cells-large"></i>Mis anuncios</a>
                    </li>
                    <li>
                        <a href="crear_anuncio.php"><i class="fa-solid fa-file-circle-plus"></i>Crear nuevo anuncio</a>
                    </li>
                    <li>
                        <a href="configurar.php"><i class="fa-solid fa-sliders"></i>Configurar</a> <!-- enlace para lo de configurar los estilos -->
                    </li>
                    <li>
                        <a href="mis_mensajes.php"><i class="fa-solid fa-envelope"></i>Mis mensajes</a>
                    </li>
                    <li>
                        <a href="solicitar_folleto.php"><i class="fa-solid fa-table-list"></i>Solicitar folleto</a>
                    </li>
                    <li>
                        <a href="nueva_foto.php"><i class="fa-solid fa-square-plus"></i>Añadir una foto a un anuncio</a>
                    </li>
                    <li>
                        <a href="includes/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Salir</a>
                    </li>
                </ul>
            </nav>
        </section>

        
<?php
    include 'includes/footer.php';
?>