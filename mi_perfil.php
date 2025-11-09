<?php
    session_start();
    
    // primero se verifica si el usuario esta logueado ya
    if(!isset($_SESSION['logueado'])) {
        header('Location: login.php?error=no_logueado');
        exit;
    }
    
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
                                if(isset($_SESSION['nombre']) && $_COOKIE['recordarme_ultima_visita']) { // se comprueba que hay un nombre de usuario y una fecah
                                    echo '<h2><strong>¡Hola ' . htmlspecialchars($_SESSION['nombre']) . '</strong>, tu última visita fue el ' . htmlspecialchars($_COOKIE['recordarme_ultima_visita']) .'!</h2>';
                                }
                            ?>
                        </p>
                    </li>
                    <li id="primer_li"> <!-- se le pasa el nombre del usuario de la session --> 
                        <p id="mis_datos"><i class="fa-solid fa-address-card"></i>Mis datos (esto será un enlace cuando se cree la página)</p>
                    </li>
                    <!--<li> esto lo comento porque ya tenemos la opcion de salir, luego se puede usar para eliminar usuarios de la base de datos si las practicas futuras lo piden
                        <a href="index.php"><i class="fa-solid fa-user-minus"></i>Darse de baja</a>
                    </li>-->
                    <li>
                        <p><i class="fa-solid fa-table-cells-large"></i>Mis anuncios (esto será un enlace cuando se cree la página)</p>
                    </li>
                    <li>
                        <p><i class="fa-solid fa-file-circle-plus"></i>Crear nuevo anuncio (esto será un enlace cuando se cree la página)</p>
                    </li>
                    <li>
                        <a href="mis_mensajes.php"><i class="fa-solid fa-envelope"></i>Mis mensajes</a>
                    </li>
                    <li>
                        <a href="solicitar_folleto.php"><i class="fa-solid fa-table-list"></i>Solicitar folleto</a>
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