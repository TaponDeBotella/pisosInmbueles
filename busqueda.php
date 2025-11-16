<?php
    session_start();
    
    $title="Resultados de Búsqueda";
    $css="css/busqueda.css";
    $acceder="Acceder";
    include 'includes/header.php';   
    include 'includes/anuncios.php';

    $resultado = $db->query('SELECT * FROM anuncios ORDER BY FRegistro ASC'); // para poner los aunucios con los datos de la base de datos
    if (!$resultado) {
        die('Error:  ' . $db->error);
    }

    $anuncios = [];

    while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
        $anuncios[] = $fila;
    }

    echo $anuncios[0]['Precio'];

    // se sacan todos los datos de la base de datos para ponerlos en los filtros en los selects
    $resultadoTiposAnuncios = $db->query('SELECT IdTAnuncio, NomTAnuncio FROM TiposAnuncios');
    if (!$resultadoTiposAnuncios) { // si no hay entonces se lanza un error
        die('Error: ' . $db->error);
    }
    $tiposAnuncios = []; // se crea el array para guardar los tipos de anuncios
    while ($fila = $resultadoTiposAnuncios->fetch_array(MYSQLI_ASSOC)) { // y se recorre toda la tabla
        $tiposAnuncios[] = $fila; // se guarda conforme va recorriendo la tabla
    }

    // lo mismo pero con los tipos de viviendas
    $resultadoTiposViviendas = $db->query('SELECT IdTVivienda, NomTVivienda FROM TiposViviendas');
    if (!$resultadoTiposViviendas) {
        die('Error: ' . $db->error);
    }
    $tiposViviendas = [];
    while ($fila = $resultadoTiposViviendas->fetch_array(MYSQLI_ASSOC)) {
        $tiposViviendas[] = $fila;
    }

    // y tambien con los paises, igual todo
    $resultadoPaises = $db->query('SELECT IdPais, NomPais FROM Paises');
    if (!$resultadoPaises) {
        die('Error: ' . $db->error);
    }
    $paises = [];
    while ($fila = $resultadoPaises->fetch_array(MYSQLI_ASSOC)) {
        $paises[] = $fila;
    }
?>

    <section id="secio_barraNav">
            <form action="busqueda.php">
                <input type="text" id="ciudad_busqueda" name="ciudad_busqueda">
                <input type="submit" value="Confirmar" id="boton_buscar" class="boton">
            </form> 
        </section>

        <h1>Resultados de Búsqueda</h1>

        <section id="sectionsBusquedaResultado">
            <section id="filtrosBusquedaLateral">
                <h3>Filtros de Búsqueda</h3>
                <form id="formBusquedaFiltros">
                    <p>
                    <label for="tipo_anuncio">Tipo de anuncio: </label>
                    <select id="tipo_anuncio" class="input_select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($tiposAnuncios as $tipo): // se recorre todo el array?>
                            <option value="<?php echo $tipo['IdTAnuncio'];  // se le pone la opcion?>">
                                <?php echo $tipo['NomTAnuncio']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </p>

                    <p>
                    <label for="tipo_inmueble">Tipo de inmueble: </label>
                    <select id="tipo_inmueble" class="input_select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($tiposViviendas as $vivienda): // lo mismo que con los tipos de anuncios?>
                            <option value="<?php echo $vivienda['IdTVivienda']; ?>">
                                <?php echo $vivienda['NomTVivienda']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </p>

                    <p>
                    <label for="pais_busqueda">País: </label>
                    <select id="pais_busqueda" class="input_select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($paises as $pais): // y los paises mas de lo mismo?>
                            <option value="<?php echo $pais['IdPais']; ?>">
                                <?php echo $pais['NomPais']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </p>

                    <p>
                    <label for="ciudad_busquedaForm">Ciudad: </label>
                    <input type="text" id="ciudad_busquedaForm" class="input_select" value="<?php 
                        if(isset($_GET['ciudad_busqueda'])) { // si el usuario ha hecho una busqueda rapida entonces se mete al form
                            echo htmlspecialchars($_GET['ciudad_busqueda']);
                        } else { // si no simplemente se deja vacio y ya
                            echo '';
                        }
                    ?>">
                    </p>

                    <p>
                    <label for="precio_minimo">Precio mínimo: </label>
                    <input type="number" id="precio_minimo" class="input_select">
                    </p>

                    <p>
                    <label for="precio_maximo">Precio máximo: </label>
                    <input type="number" id="precio_maximo" class="input_select">
                    </p>

                    <p> 
                    <label for="fecha_publicacion">Fecha de publicación: </label>
                    <input type="date" id="fecha_publicacion" class="input_select">       
                    </p>

                    <p>
                    <input type="submit" value="Confirmar" class="boton">
                    <input type="reset" value="Reset" class="boton">
                    </p>
                </form>
            </section>

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
        </section>
<?php
    include 'includes/footer.php';