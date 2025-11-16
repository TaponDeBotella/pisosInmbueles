<?php
    session_start();
    
    $title = "Inicio";
    $acceder = "Acceder";
    $css = "css/index.css";
    include 'includes/header.php';
    include 'includes/iniciarDB.php';


    $resultado = $db->query('SELECT * FROM anuncios ORDER BY FRegistro ASC');
    if (!$resultado) {
        die('Error:  ' . $db->error);
    }

    $anuncios = [];

    while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
        $anuncios[] = $fila;
    }

    echo $anuncios[0]['Precio'];



?>
        <section id="sectio_barraNav">
            <form action="busqueda.php">
                <input type="text" id="ciudad_busqueda" name="ciudad_busqueda">
                <input type="submit" value="Confirmar" id="boton_buscar" class="boton">
            </form> 
        </section>

        <h1>Inicio</h1>
        <section id="sectionArticulos">
            <ul id="ul_articulos">
                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=1">
                            <img class="imagen_articulo" src="img/<?php echo $anuncios[0]['fotos'][0][0]; ?>" alt="<?php echo $anuncios[0]['fotos'][1][0]; ?>">
                        </a>
                        <a href="anuncio.php?idAnuncio=1" class="a_tituloPublicacion">
                            <h2><?php echo $anuncios[0]['titulo']; ?></h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-11-09">09-11-2025</time></p>
                        <p class="precio">Precio: <?php echo $anuncios[0]['precio']?>€/mes</p>
                        <p class="pais">País: <?php echo $anuncios[0]['pais']; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $anuncios[0]['ciudad']; ?></p>
                        <p class="p_descripcionA"><?php echo substr($anuncios[0]['texto'], 0, 100) . '...'; ?></p>    
                    </article>       
                </li>
                <li>
                    <article>
                        <a href="alerta.php">
                            <img class="imagen_articulo" src="img/<?php echo $anuncios[1]['fotos'][0][0]; ?>" alt="<?php echo $anuncios[1]['fotos'][1][0]; ?>">
                        </a>
                        <a href="alerta.php" class="a_tituloPublicacion">
                            <h2><?php echo $anuncios[1]['titulo']; ?></h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-11-08">08-11-2025</time></p>
                        <p class="precio">Precio: <?php echo $anuncios[1]['precio']?>€</p>
                        <p class="pais">País: <?php echo $anuncios[1]['pais']; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $anuncios[1]['ciudad']; ?></p>
                        <p class="p_descripcionA"><?php echo substr($anuncios[1]['texto'], 0, 100) . '...'; ?></p>    
                    </article> 
                </li>
                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=3">
                            <img class="imagen_articulo" src="img/<?php echo $anuncios[0]['fotos'][0][0]; ?>" alt="<?php echo $anuncios[0]['fotos'][1][0]; ?>">
                        </a>
                        <a href="anuncio.php?idAnuncio=3" class="a_tituloPublicacion">
                            <h2><?php echo $anuncios[0]['titulo']; ?></h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-11-09">09-11-2025</time></p>
                        <p class="precio">Precio: <?php echo $anuncios[0]['precio']?>€/mes</p>
                        <p class="pais">País: <?php echo $anuncios[0]['pais']; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $anuncios[0]['ciudad']; ?></p>
                        <p class="p_descripcionA"><?php echo substr($anuncios[0]['texto'], 0, 100) . '...'; ?></p>    
                    </article>
                </li>
                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=4">
                            <img class="imagen_articulo" src="img/<?php echo $anuncios[1]['fotos'][0][0]; ?>" alt="<?php echo $anuncios[1]['fotos'][1][0]; ?>">
                        </a>
                        <a href="anuncio.php?idAnuncio=4" class="a_tituloPublicacion">
                            <h2><?php echo $anuncios[1]['titulo']; ?></h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-11-08">08-11-2025</time></p>
                        <p class="precio">Precio: <?php echo $anuncios[1]['precio']?>€</p>
                        <p class="pais">País: <?php echo $anuncios[1]['pais']; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $anuncios[1]['ciudad']; ?></p>
                        <p class="p_descripcionA"><?php echo substr($anuncios[1]['texto'], 0, 100) . '...'; ?></p>    
                    </article>
                </li>
            </ul>
        </section>

<?php
    include 'includes/cerrarDB.php';
    include 'includes/footer.php';
?>

