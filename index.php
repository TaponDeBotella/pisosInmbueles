<?php
    session_start();
    
    $title = "Inicio";
    $acceder = "Acceder";
    $css = "css/index.css";
    include 'includes/funciones.php';
    
    require_once 'includes/extraer_experto.php';

    $data = extraer_experto('anuncios_recomendados.experto');

    $ids_array = array_column($data, 'id-anuncio'); // creo array de claves (ids)
    array_multisort(array_map('intval', $ids_array), SORT_ASC, $data); // uso map para convertirlas en entero y uso ese array de enteros como claves para array-multisort, que  ordenara siguiendo esas claves  y de forma ascendente el contenido de data

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

    
    if(count($data) !== 0) {// hay datos
        $tipoparams = '';
        $params = [];
        $query = 'SELECT FPrincipal, Alternativo, Precio, Ciudad, NomPais, NomTAnuncio, IdAnuncio FROM Anuncios a, TiposAnuncios ta, Paises p WHERE a.TAnuncio = ta.IdTAnuncio AND a.Pais = p.IdPais AND IdAnuncio IN';

        for($i=0; $i<count($data); $i++) { // preparo las id que tiene que buscar la base de datos
            if($i === 0) // inicio del bucle
                $query .= ' (?';
            else
                $query .= '?';

            if($i+1 !== count($data)) // si aun quedan iteraciones
                $query .= ',';
            else
                $query .= ')';
        }

        $query .= ' ORDER BY IdAnuncio ASC';

        $stmt = $db->prepare($query);

        for($i=0; $i<count($data); $i++) { // preparo el bind
            $tipoparams .= 'i';
            $params[] = $data[$i]['id-anuncio'];
        }

        if (!$stmt)  // si hay error se manda
            $error_mensaje = 'Error en la preparación: ' . $db->error;
        
        else {
            array_unshift($params, $tipoparams);
            call_user_func_array([$stmt, 'bind_param'], refValues($params)); // con esto llamo a la funcion bind_param usando las referencias en $params
        }
        
        if(!$stmt->execute()) { // ejecuto y miro si hay error
            die('Error: ' . $stmt->error);
        }
        $resultado = $stmt->get_result(); // guardo el resultado del prepared statement
        if (!$resultado) {
            $stmt->close();
            die('Error getting result: ' . $db->error);
        }

        // recoger todos los anuncios en un array (más eficiente que fetch en bucle)
        $anuncios_recomendados = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
    
?>
        <section id="sectio_barraNav">
            <form action="index.php" method="GET">
                <input type="text" id="ciudad_busqueda" name="ciudad_busqueda">
                <input type="submit" value="Confirmar" id="boton_buscar" class="boton">
            </form> 
        </section>
        <h2>Anuncios destacados</h2>
        <section class="sectionArticulos">
            <ul class="ul_articulos">
                <?php

                for($i=0; $i<count($data); $i++) {    
                    echo '
                        <li>
                            <article>
                                <a href="anuncio.php?idAnuncio='.htmlspecialchars($anuncios_recomendados[$i]['IdAnuncio']).'">
                                    <img class="imagen_articulo" src="img/'.htmlspecialchars($anuncios_recomendados[$i]['FPrincipal']).'" alt="'.htmlspecialchars($anuncios_recomendados[$i]['Alternativo']).'">
                                </a>
                                <a href="anuncio.php?idAnuncio='.htmlspecialchars($anuncios_recomendados[$i]['IdAnuncio']).'" class="a_tituloPublicacion">
                                    <h2>'.htmlspecialchars($data[$i]['titulo']).'</h2>
                                </a>';  

                                $tipo_precio = '';
                                
                                if($anuncios_recomendados[$i]['NomTAnuncio'] === 'Venta')
                                    $tipo_precio = '€';
                                else
                                    $tipo_precio = '€/mes';
                            

                                echo '<p class="precio">Precio:'.htmlspecialchars(round($anuncios_recomendados[$i]['Precio'], 0)).htmlspecialchars($tipo_precio).'</p>
                                <p class="pais">País:'.htmlspecialchars($anuncios_recomendados[$i]['NomPais']).'</p>
                                <p class="ciudad">Ciudad:'.htmlspecialchars($anuncios_recomendados[$i]['Ciudad']).'</p>';

                                $texto_completo = '';
                                $texto_pasado = '';
                                if(isset($data[$i]['texto']))
                                    $texto_completo = $data[$i]['texto'];

                                if (mb_strlen($texto_completo) > 100) {
                                    $texto_pasado = htmlspecialchars(mb_substr($texto_completo, 0, 100)) . '...';
                                } else {
                                    $texto_pasado = htmlspecialchars($texto_completo);
                                }

                                echo '<p class="p_descripcionA">'.'"'.htmlspecialchars($texto_pasado).'"'.' - '.htmlspecialchars($data[$i]['autor']).'</p>    
                            </article>       
                        </li>';
                    }
                ?>
            </ul>
        </section>

        <h1>Inicio</h1>
        <section class="sectionArticulos">
            <ul class="ul_articulos">
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
            </ul>
        </section>

<?php
    include 'includes/footer.php';
?>

