<?php
    $title="Mi perfil";
    $acceder = "Mi perfil";
    $css="css/mi_perfil.css";
    include 'includes/header.php';
    
    // se verifica si el usuario esta logueado
    if(!isset($_SESSION['logueado'])) {
        header('Location: login.php?error=no_logueado');
        exit;
    }
?>
        <h1>Mi perfil</h1>
        <section>
            <nav>
                <ul>
                    <li id="primer_li"> <!-- se le pasa el nombre del usuario de la session --> 
                        <p id="mis_datos"><i class="fa-solid fa-address-card"></i>Mis datos (esto será un enlace cuando se cree la página)</p><p id="nombre_perfil"><strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong></p>
                    </li>
                    <li>
                        <a href="index.php"><i class="fa-solid fa-user-minus"></i>Darse de baja</a>
                    </li>
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