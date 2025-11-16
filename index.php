<?php
    session_start();
    
    $title = "Inicio";
    $acceder = "Acceder";
    $css = "css/index.css";
    include 'includes/funciones.php';
    
    // he tenido que cambiar de lado el include del header porque si no no se podia hacer el get del formulario
    if (!empty($_GET['ciudad_busqueda'])) { // se saca el texto de la busqueda rapida
        include 'includes/iniciarDB.php'; // se incluye la base de datos para poder hacer la consulta y como el header aun no se ha llamado pues tengo que llamar a la base de datos aqui
        $parametros = procesarBusquedaRapida($_GET['ciudad_busqueda'], $db); // se llama a la funcion 
        
        // se crea la url con los parametros sacados de la funcion de la busqueda rapida si hay
        $urlBusqueda = 'busqueda.php?';
        if (!empty($parametros['tipo_inmueble'])) { // se le pone el tipo de inmueble si hay 
            $urlBusqueda .= 'tipo_inmueble=' . urlencode($parametros['tipo_inmueble']) . '&';
        }
        if (!empty($parametros['tipo_anuncio'])) { // el tipo de anuncio si hay 
            $urlBusqueda .= 'tipo_anuncio=' . urlencode($parametros['tipo_anuncio']) . '&';
        }
        if (!empty($parametros['ciudad_busquedaForm'])) { // y la ciudad o pais (que lo de pais aun no me funciona pero bueno) si hay 
            $urlBusqueda .= 'ciudad_busquedaForm=' . urlencode($parametros['ciudad_busquedaForm']) . '&';
        }
        
        // y se redirige a la busqueda pero filtrada
        header('Location: ' . rtrim($urlBusqueda, '&'));
        exit;
    }
    
    include 'includes/header.php';

    $query_anuncios = $db->query('SELECT FPrincipal, Alternativo, Titulo, Precio, FRegistro, NomPais, Ciudad, Texto, NomTAnuncio, IdAnuncio FROM Anuncios a, TiposAnuncios ta, Paises p WHERE a.TAnuncio = ta.IdTAnuncio AND a.Pais = p.IdPais ORDER BY FRegistro DESC'); // query a la db donde se filtra ya por FRegistro para obtener los mas recientes y se obtiene el nombre del pais y el tipo de anuncio
    
    if (!$query_anuncios) { // comprobacion de si hay query_anuncios
        die('Error:  ' . $db->error); // para y da el error
    }

    $anuncios = [];

    while ($fila = $query_anuncios->fetch_array(MYSQLI_ASSOC)) { // hago fetch con array asociativo y guardo las filas en $anuncios por orden de la query
        $anuncios[] = $fila;
    }

?>
        <section id="sectio_barraNav">
            <form action="index.php" method="GET">
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
                                <a href="anuncio.php?idAnuncio='.htmlspecialchars($anuncios[$i]['IdAnuncio']).'">
                                    <img class="imagen_articulo" src="img/'.htmlspecialchars($anuncios[$i]['FPrincipal']).'" alt="'.htmlspecialchars($anuncios[$i]['Alternativo']).'">
                                </a>
                                <a href="anuncio.php?idAnuncio='.htmlspecialchars($anuncios[$i]['IdAnuncio']).'" class="a_tituloPublicacion">
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
                                <p class="precio">Precio:'.htmlspecialchars(round($anuncios[$i]['Precio'], 0)).htmlspecialchars($tipo_precio).'</p>
                                <p class="pais">País:'.htmlspecialchars($anuncios[$i]['NomPais']).'</p>
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

