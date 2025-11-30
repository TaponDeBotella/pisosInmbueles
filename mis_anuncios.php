<?php
    session_start();
    
    $title = "Mis anuncios";
    $acceder = "Acceder";
    $css = "css/mis_anuncios.css";
    include 'includes/header.php';
    
    $anuncios = [];

    $stmt = $db->prepare( // preparo la consulta (para evitar inyeccion sql, se pone ? donde se debe poner un parametro)
        "SELECT a.Superficie, a.NHabitaciones, a.NBanyos, a.Planta, a.Anyo, NomUsuario,
                a.FPrincipal, a.Alternativo, a.Titulo, a.Precio, a.FRegistro,
                p.NomPais, a.Ciudad, a.Texto, ta.NomTAnuncio, a.IdAnuncio, NomTVivienda
        FROM Anuncios a, Usuarios, TiposAnuncios ta, Paises p, TiposViviendas
        WHERE a.Pais = p.IdPais AND a.TAnuncio = ta.IdTAnuncio AND IdUsuario = Usuario AND NomUsuario = ? AND TVivienda = IdTVivienda"
    );    
    
    if (!$stmt) { // comprobacion de si hay statement
        die('Error:  ' . $db->error); // para y da el error
    }
    
    if($_SESSION['nombre'])
        $stmt->bind_param('s', $_SESSION['nombre']); // vinculo el parametro nombre de la sesion como string

    if(!$stmt->execute()) { // ejecuto y miro si hay error
        die('Error: ' . $stmt->error);
    }

    $resultado = $stmt->get_result(); // guardo el resultado

    if ($resultado) {
        // obtener todas las filas como array de arrays asociativos
        $anuncios = $resultado->fetch_all(MYSQLI_ASSOC);
        $resultado->free();
    } else {
        $anuncios = [];
    }

    $stmt->close();

    
?>
        <h1>Mis anuncios</h1>
        <section id="sectionArticulos">
            <ul id="ul_articulos">
            <?php
                for($i=0; $i<sizeof($anuncios); $i++) { // recorro los anuncios con un bucle
                    $anuncio = $anuncios[$i]; // para cada iteracion selecciono un anuncio
                    
            ?> <!-- en php se pueden abrir y cerrar las etiquetas php cuando quiera. Puedo cortar el if y el for y cerrarlos mas abajo para no tener que hacer un echo gigante -->
                <li> <!-- para cada vez que se de la condicion del if inyectara un nuevo elemento a la lista siguiendo la estructura del index pero metiendo los valores que pone en el enunciado -->
                    <article> 
                        <a  href="anuncio.php?idAnuncio=<?php echo $anuncio['IdAnuncio']; ?>">
                            <img class="imagen_articulo" src="img/<?php echo $anuncio['FPrincipal']; ?>" alt="<?php echo $anuncio['Alternativo']; ?>">
                        </a>
                        <a href="anuncio.php?idAnuncio=<?php echo $anuncio['IdAnuncio']; ?>" class="a_tituloPublicacion">
                            <h2><?php echo $anuncio['Titulo']; ?></h2>
                        </a>
                        <?php

                                $raw = $anuncio['FRegistro'];              // ej. "2025-11-09 14:30:00"
                                if (!empty($raw)) {
                                    $dt = new DateTime($raw);
                                    $iso = $dt->format('c');                   // formato ISO 8601 con offset: 2025-11-09T14:30:00+01:00
                                    $visible = $dt->format('d-m-Y');           // formato de visualización: 09-11-2025
                                } else {
                                    $iso = '';
                                    $visible = 'Fecha no disponible';
                                }

                                $tipo_precio = '';
                                
                                if($anuncios[$i]['NomTAnuncio'] === 'Venta')
                                    $tipo_precio = '€';
                                else
                                    $tipo_precio = '€/mes';

                        ?>
                        
                        <p class="fecha">Fecha publicación: <time datetime="<?php echo htmlspecialchars($iso); ?>"><?php echo htmlspecialchars($visible); ?></time></p>
                        <p class="precio">Precio:   <?php 
                                                        echo 'Precio:'.htmlspecialchars(round($anuncio['Precio'], 0)).htmlspecialchars($tipo_precio);
                                                    ?>
                        </p>
                        <p class="pais">País: <?php echo $anuncio['NomPais']; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $anuncio['Ciudad']; ?></p>
                        <p class="p_descripcionA"><?php echo substr($anuncio['Texto'], 0, 100) . '...'; ?></p>  
                        
                        <nav>
                            <a id="ver_anuncio" href="ver_anuncio.php?idAnuncio=<?php echo htmlspecialchars($anuncio['IdAnuncio']) ?>"><i class="fa-solid fa-square-plus"></i>Añadir una foto</a>
                            <a href="editar_anuncio.php?id=<?php echo htmlspecialchars($anuncio['IdAnuncio']) ?>" class="boton" title="Editar anuncio"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="boton" onclick="abrirModalBorrar(<?php echo htmlspecialchars($anuncio['IdAnuncio']); ?>)" title="Borrar anuncio"><i class="fa-solid fa-circle-xmark"></i></button>
                        </nav>
                    </article>
                </li>
            <?php
                } // cierro el for
            ?>
            </ul>
        </section>

        <a id="nuevoAnuncio" href="crear_anuncio.php">¿Crear nuevo anuncio?</a>

        <!-- la ventana modal para que el usuario pueda confirmar lo de borrar el anuncio -->
        <section id="modalBorrar">
            <section id="ventanaModal">
                <h2>¿Borrar este anuncio?</h2>
                <p>Esta acción no se puede deshacer.</p>
                
                <section id="modalBotones"> <!-- se hace un post para borrar el anuncio -->
                    <form id="formBorrarAnuncio" action="respuesta_borrar_anuncio.php" method="POST" style="display:inline;">
                        <input type="hidden" id="idAnuncioInput" name="idAnuncio" value="">
                        <button class="boton" type="submit">Confirmar</button>
                    </form>
                    <button class="boton" onclick="cerrarModalBorrar()">Cancelar</button> <!--si no quiere borrarlo solo se llama a la funcion de borrar la ventana modal y ya -->
                </section>
            </section>
        </section>

        <script> // las funciones para abrir y cerrar la ventana modal
            function abrirModalBorrar(idAnuncio) { // se le pasa el id del anuncio que se quiere borrar
                document.getElementById('idAnuncioInput').value = idAnuncio; 
                document.getElementById('modalBorrar').style.display = 'flex'; // se le pone display flex para mostrar
            }

            function cerrarModalBorrar() { // para cerrar la ventana modal
                document.getElementById('modalBorrar').style.display = 'none'; // se le pone display none para ocultar
            }
        </script>
<?php
    include 'includes/footer.php';
?>

