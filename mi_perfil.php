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
    
    // se saca la foto del usuario de la base de datos
    $id_usuario = $_SESSION['id_usuario'];
    $query_foto = "SELECT Foto FROM Usuarios WHERE IdUsuario = ?"; // se prepara la consulta del select para sacar la foto
    $stmt_foto = $db->prepare($query_foto);
    $stmt_foto->bind_param('i', $id_usuario); // se vincula el parametro
    $stmt_foto->execute(); // y se hace la consulta
    $resultado_foto = $stmt_foto->get_result(); // se guarda el resultado
    $usuario_foto = $resultado_foto->fetch_array(MYSQLI_ASSOC); // se convierte el resultado en array asociativo
    $foto_perfil = $usuario_foto['Foto']; // se guarda la foto actual
    $stmt_foto->close();
?>
        <h1>Mi perfil</h1>
        
        <section style="display: flex; align-items: flex-start; gap: 20px;">
            <nav style="flex: 1;">
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
                        <a href="mis_datos.php" id="mis_datos"><i class="fa-solid fa-address-card"></i>Mis datos</a>
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
                    <li>
                        <a href="exportar_datos.php"><i class="fa-solid fa-download"></i>Exportar mis datos</a>
                    </li>
                    <li>
                        <button onclick="abrirModalBaja()" class="boton"><i class="fa-solid fa-user-minus"></i>Darme de baja</button> <!-- le anyado lo de la ventana modal para confirmar si el usuario de verdad quiere borrar la cuenta -->
                    </li>
                </ul>
            </nav>
            
            <!-- se muestra la foto de perfil del usuario -->
            <section style="flex-shrink: 0; text-align: center; padding: 20px;">
                <?php if(!empty($foto_perfil) && file_exists($foto_perfil)) { ?> <!-- se comprueba que la foto existe y no esta vacia -->
                    <img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de perfil" style="max-width: 200px; height: auto; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"> <!-- se muestra la foto del usuario si hay -->
                <?php } else { ?>
                    <img src="./img/foto1.jpg" alt="Foto de perfil por defecto" style="max-width: 200px; height: auto; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"> <!-- si no hay foto se muestra una por defecto de img -->
                <?php } ?>
            </section>
        </section>

        <!-- ventana modal para confirmar la baja -->
        <section id="modalBaja" style="display: none;"> <!-- se pone display none para que no se vea al cargar la pagina -->
            <section id="ventanaModalBaja">
                <h2>Confirmar eliminación de cuenta</h2>
                <p>Esta acción eliminará tu cuenta y todos tus datos asociados:</p>
                
                <h3>Tus anuncios y fotos:</h3>
                <?php
                    // se sacan los anuncios y fotos del usuario para mostrarlos en el resumen
                    require_once 'includes/iniciarDB.php';
                    $id_usuario = $_SESSION['id_usuario']; // se saca el id del usuario logueado
                    
                    $query = "SELECT IdAnuncio, Titulo FROM Anuncios WHERE Usuario = ? ORDER BY FRegistro DESC";
                    $stmt = $db->prepare($query); // se prepara la query del select para sacar los datos para el reusmen
                    $stmt->bind_param('i', $id_usuario);
                    $stmt->execute();
                    $resultado = $stmt->get_result(); // se obtienen los resultados
                    
                    $total_anuncios = 0; // las variables para el resumen
                    $total_fotos = 0;
                    
                    if ($resultado->num_rows > 0) { // si hay resultado es que hay anuncios entonces se muestran en el resumen
                        echo '<ul>';
                        while ($anuncio = $resultado->fetch_array(MYSQLI_ASSOC)) { // se recorren los anuncios
                            $total_anuncios++;
                            $id_anuncio = $anuncio['IdAnuncio'];
                            
                            $query_fotos = "SELECT COUNT(*) as num_fotos FROM Fotos WHERE Anuncio = ?"; // se cuentan las fotos con el count
                            $stmt_fotos = $db->prepare($query_fotos); // se prepara la query para contar las fotos
                            $stmt_fotos->bind_param('i', $id_anuncio);
                            $stmt_fotos->execute();
                            $resultado_fotos = $stmt_fotos->get_result(); // se sacan los resultados
                            $row_fotos = $resultado_fotos->fetch_array(MYSQLI_ASSOC); // se saca la fila
                            $num_fotos = $row_fotos['num_fotos']; // se obtiene el numero de fotos
                            $total_fotos += $num_fotos;
                            
                            echo '<li>' . htmlspecialchars($anuncio['Titulo']) . ' (' . $num_fotos . ' fotos)</li>'; // y se le pone el numero de fotos y el titulo del anuncio solo
                            $stmt_fotos->close();
                        }
                        echo '</ul>';
                    } else { // si no tienen anuncios entonces solo se muestra el mensjae de qu e no hay anuncios
                        echo '<p>No tienes anuncios</p>';
                    }
                    
                    $stmt->close();
                ?>
                
                <h3>Resumen:</h3> <!-- el resumen con el numero total de anuncios y fotos-->
                <p><strong>Total de anuncios:</strong> <?php echo $total_anuncios; ?></p>
                <p><strong>Total de fotos:</strong> <?php echo $total_fotos; ?></p>
                
                <h3>Para confirmar, introduce tu contraseña:</h3> <!-- se le pide la contrasenya al usuario para poder eliminar la cuenta -->
                <form method="POST" action="respuesta_darme_baja.php"> <!-- se hace el post con la contrasenya -->
                    <input type="password" name="clave_confirmacion" placeholder="Contraseña" required class="input_select">
                    <section class="botonesModal">
                        <button type="submit" class="boton">Confirmar</button>
                        <button type="button" onclick="cerrarModalBaja()" class="boton">Cancelar</button>
                    </section>
                </form>
            </section>
        </section>

        <script>
            function abrirModalBaja() { // esto es igual que en lo de confirmar para borrar un anuncio 
                document.getElementById('modalBaja').style.display = 'flex'; // el flex para hacerlo visible
            }
            
            function cerrarModalBaja() {
                document.getElementById('modalBaja').style.display = 'none'; // el none para hacerlo invisile
            }
        </script>

        
<?php
    include 'includes/footer.php';
?>