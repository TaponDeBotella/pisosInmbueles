<?php
    session_start();
    
    $title = "Inicio";
    $acceder = "Acceder";
    $css = "css/index.css";
    include 'includes/header.php';


    $resultado = $db->query('SELECT FPrincipal, Alternativo, Titulo, Precio, FRegistro, Pais, Ciudad, Texto, NomTAnuncio FROM anuncios a, TiposAnuncios ta WHERE a.TAnuncio = ta.IdTAnuncio ORDER BY FRegistro DESC'); // query a la db donde se filtra ya por FRegistro para obtener los mas recientes
    if (!$resultado) { // comprobacion de si hay resultado
        die('Error:  ' . $db->error); // para y da el error
    }

    $anuncios = [];

    while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) { // hago fetch con array asociativo y guardo las filas en $anuncios por orden de la query
        $anuncios[] = $fila;
    }

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
                <?php

                for($i=0; $i<5; $i++) {    
                    echo '<li>
                            <article>
                                <a href="anuncio.php?idAnuncio=1">
                                    <img class="imagen_articulo" src="img/'.htmlspecialchars($anuncios[$i]['FPrincipal']).'" alt="'.htmlspecialchars($anuncios[$i]['Alternativo']).'">
                                </a>
                                <a href="anuncio.php?idAnuncio=1" class="a_tituloPublicacion">
                                    <h2>'.htmlspecialchars($anuncios[$i]['Titulo']).'</h2>
                                </a>';  

                                

                                $raw = $anuncios[$i]['FRegistro'];              // ej. "2025-11-09 14:30:00"
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
                            

                                echo '<p class="fecha">Fecha publicación: <time datetime="'.htmlspecialchars($iso).'">'.htmlspecialchars($visible).'</time></p>
                                <p class="precio">Precio:'.htmlspecialchars($anuncios[$i]['Precio']).htmlspecialchars($tipo_precio).'</p>
                                <p class="pais">País:'.htmlspecialchars($anuncios[$i]['Pais']).'</p>
                                <p class="ciudad">Ciudad:'.htmlspecialchars($anuncios[$i]['Ciudad']).'</p>
                                <p class="p_descripcionA">'.htmlspecialchars(substr($anuncios[$i]['Texto'], 0, 100) . '...').'</p>    
                            </article>       
                        </li>';
                    }
                ?>
        </section>

<?php
    include 'includes/footer.php';
?>

