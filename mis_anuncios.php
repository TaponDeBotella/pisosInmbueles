<?php
    session_start();
    
    $title = "Mis anuncios";
    $acceder = "Acceder";
    $css = "css/mis_anuncios.css";
    include 'includes/header.php';
    include 'includes/anuncios.php';
?>
        <h1>Mis anuncios</h1>
        <section id="sectionArticulos">
            <ul id="ul_articulos">
            <?php
                for($i=0; $i<sizeof($anuncios); $i++) { // recorro los anuncios con un bucle
                    $anuncio = $anuncios[$i]; // para cada iteracion selecciono un anuncio
                    if ($anuncio['duenyo'] == $_SESSION['nombre']) { // si el duenyo del anuncio coincide con el usuario de la sesion iniciada
            ?> <!-- en php se pueden abrir y cerrar las etiquetas php cuando quiera. Puedo cortar el if y el for y cerrarlos mas abajo para no tener que hacer un echo gigante -->
                <li> <!-- para cada vez que se de la condicion del if inyectara un nuevo elemento a la lista siguiendo la estructura del index pero metiendo los valores que pone en el enunciado -->
                    <article> 
                        <a  href="anuncio.php?idAnuncio=<?php echo $anuncio['idAnuncio']; ?>">
                            <img class="imagen_articulo" src="img/<?php echo $anuncio['fotos'][0][0]; ?>" alt="<?php echo $anuncios[1][0]; ?>">
                        </a>
                        <a href="anuncio.php?idAnuncio=<?php echo $anuncio['idAnuncio']; ?>" class="a_tituloPublicacion">
                            <h2><?php echo $anuncio['titulo']; ?></h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="<?php echo $anuncio['fecha']->format('Y-m-d'); ?>"><?php echo $anuncio['fecha']->format('d-m-Y'); ?></time></p>
                        <p class="precio">Precio:   <?php 
                                                        $cadena =  $anuncio['precio'].'€'; // precio en €
                                                        if($anuncio['tipoAnuncio'] == 'alquiler')
                                                            $cadena .= '/mes'; // si es un alquiler pongo el /mes
                                                        echo $cadena; // inserto la cadena en el <p>
                                                    ?>
                        </p>
                        <p class="pais">País: <?php echo $anuncio['pais']; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $anuncio['ciudad']; ?></p>
                        <p class="p_descripcionA"><?php echo substr($anuncio['texto'], 0, 100) . '...'; ?></p>  
                        
                        <a id="ver_anuncio" href="ver_anuncio.php?idAnuncio=<?php echo htmlspecialchars($anuncio['idAnuncio']) ?>"><i class="fa-solid fa-square-plus"></i>Añadir una foto</a>
                    </article>
                </li>
            <?php
                    } // cierro el if
                } // cierro el for
            ?>
            </ul>
        </section>

        <a id="nuevoAnuncio" href="crear_anuncio.php">¿Crear nuevo anuncio?</a>
<?php
    include 'includes/footer.php';
?>

