<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    include 'includes/anuncios.php';
    
    // obtengo el ID del anuncio de la URL
    $idAnuncio = (int)$_GET['idAnuncio'];
    
    // selecciono el anuncio según si el ID es par o impar
    if($idAnuncio % 2 == 0) 
        $indiceAnuncio = 2;  // si es par, usamos el segundo anuncio
    else 
        $indiceAnuncio = 1;  // si es impar, usamos el primer anuncio
    
    $anuncio = null;

    for($i=0; $i<sizeof($anuncios); $i++) {
        if($anuncios[$i]['idAnuncio'] == $indiceAnuncio)
            $anuncio = $anuncios[$i]; 
    }

    if(isset($_COOKIE['ultimos_4_anuncios'])) { // compruebo que la cookie existe
        $ultimos_4 = json_decode($_COOKIE['ultimos_4_anuncios'], true); // la recojo y decodifico de json
        $ultimos_4_copia = $ultimos_4;
            
        if($ultimos_4['0']['idAnuncio'] !== $idAnuncio && $ultimos_4['1']['idAnuncio'] !== $idAnuncio && $ultimos_4['2']['idAnuncio'] !== $idAnuncio && $ultimos_4['3']['idAnuncio'] !== $idAnuncio) { // evito repetir los anuncios
            // muevo todo una posicion a la derecha del array, sobreescribiendo el elemento que hay en la posicion 3
            $ultimos_4['3'] = $ultimos_4_copia['2'];
            $ultimos_4['2'] = $ultimos_4_copia['1'];
            $ultimos_4['1'] = $ultimos_4_copia['0'];
            $ultimos_4['0'] = array(    'idAnuncio' => $idAnuncio, 
                                        'tipoAnuncio' => $anuncio['tipoAnuncio'],
                                        'foto' => $anuncio['fotos'][0][0],
                                        'altFoto' => $anuncio['fotos'][1][0],
                                        'titulo' => $anuncio['titulo'],
                                        'precio' => $anuncio['precio'],
                                        'ciudad' => $anuncio['ciudad'],
                                        'pais' => $anuncio['pais']); // anyado el nuevo elemento a la posicion 0

            $duracion_cookie = time() + (7*24*60*60); // dura una semana
            setcookie('ultimos_4_anuncios', json_encode($ultimos_4), $duracion_cookie, '/', '', false, true);  
        }
        else {
            $posicion = -1;
            for($i=0; $i<sizeof($ultimos_4); $i++) {
                if($ultimos_4[$i]['idAnuncio'] === $idAnuncio)
                    $posicion = $i;
            }
            
            switch($posicion) { // cambio las posiciones solo si posicion es distinto de 0
                case 1: $ultimos_4['0'] = $ultimos_4_copia['1'];
                        $ultimos_4['1'] = $ultimos_4_copia['0'];
                        break;

                case 2: $ultimos_4['0'] = $ultimos_4_copia['2'];
                        $ultimos_4['1'] = $ultimos_4_copia['0'];
                        $ultimos_4['2'] = $ultimos_4_copia['1'];
                        break;

                case 3: $ultimos_4['0'] = $ultimos_4_copia['3'];
                        $ultimos_4['1'] = $ultimos_4_copia['0'];
                        $ultimos_4['2'] = $ultimos_4_copia['1'];
                        $ultimos_4['3'] = $ultimos_4_copia['2'];
                        break;
            }

            // actualizo la cookie, si ha sido posicion == 0 simplemente se restaura la duracion
            $duracion_cookie = time() + (7*24*60*60); // dura una semana
            setcookie('ultimos_4_anuncios', json_encode($ultimos_4), $duracion_cookie, '/', '', false, true);  
        }
    }
    else {
        $duracion_cookie = time() + (7*24*60*60); // dura una semana
        $ultimos_4 = array("0"=>array(  'idAnuncio' => $idAnuncio, // 0 es el mas reciente, 3 el mas antiguo, pongo el anuncio y el id por separado porque estoy usando solo dos anuncios para 4 paginas, lo que me interesa es el idAnuncio, que es el id que realmente tendria el anuncio si no los repitiese
                                        'tipoAnuncio' => $anuncio['tipoAnuncio'],
                                        'foto' => $anuncio['fotos'][0][0],
                                        'altFoto' => $anuncio['fotos'][1][0],
                                        'titulo' => $anuncio['titulo'],
                                        'precio' => $anuncio['precio'],
                                        'ciudad' => $anuncio['ciudad'],
                                        'pais' => $anuncio['pais']),

                            "1"=>array( 'idAnuncio' => '',
                                        'tipoAnuncio' => '',
                                        'foto' => '',
                                        'altFoto' => '',
                                        'titulo' => '',
                                        'precio' => '',
                                        'ciudad' => '',
                                        'pais' => ''),

                            "2"=>array( 'idAnuncio' => '',
                                        'tipoAnuncio' => '',
                                        'foto' => '',
                                        'altFoto' => '',
                                        'titulo' => '',
                                        'precio' => '',
                                        'ciudad' => '',
                                        'pais' => ''),

                            "3"=>array( 'idAnuncio' => '',
                                        'tipoAnuncio' => '',
                                        'foto' => '',
                                        'altFoto' => '',
                                        'titulo' => '',
                                        'precio' => '',
                                        'ciudad' => '',
                                        'pais' => ''));

        if(!isset($_COOKIE['ultimos_4_anuncios']))
            setcookie('ultimos_4_anuncios', json_encode($ultimos_4), $duracion_cookie, '/', '', false, true);  
    }
        

    $title="Página del anuncio";
    $acceder = "Acceder";
    $css="css/anuncio.css";
    include 'includes/header.php';
?>
        <h1><?php echo $title; ?></h1>
        <h2>Anuncio de <?php echo $anuncio['tipoAnuncio']; ?></h2>
        <h3><?php echo $anuncio['tipoVivienda']; ?></h3>
        <figure>
            <img id="carrusel" src="img/<?php echo $anuncio['fotos'][0][0]; ?>" alt="<?php echo $anuncio['fotos'][1][0]; ?>">
            
            <figcaption>Foto de <?php echo strtolower($anuncio['tipoVivienda']); ?></figcaption>
            
            <button class="boton">&larr;</button>
            <button class="boton">&rarr;</button>
        </figure>


        <article>
            <h3><?php echo $anuncio['titulo']; ?></h3>
            <h4><?php echo $anuncio['texto']; ?></h4>
            <time datetime="<?php echo $anuncio['fecha']->format('Y-m-d'); ?>">
                <?php echo $anuncio['fecha']->format('d-m-Y'); ?>
            </time>
            
            <table>
                <tr>
                    <th>Ciudad:</th>
                    <td><?php echo $anuncio['ciudad']; ?></td>
                </tr>
                <tr>
                    <th>País:</th>
                    <td><?php echo $anuncio['pais']; ?></td>
                </tr>
                <tr>
                    <th>Precio:</th>
                    <td><?php 
                        if($anuncio['tipoAnuncio'] == 'Alquiler') 
                            echo $anuncio['precio'].'€/mes';
                        else 
                            echo $anuncio['precio'].'€';
                    ?></td>
                </tr>
                <tr>
                    <th rowspan="5">Características:</th>
                    <td><?php 
                        echo $anuncio['caracteristicas']['numBanyo'];
                        if($anuncio['caracteristicas']['numBanyo'] > 1) 
                            echo ' baños';
                        else
                            echo ' baño';                      
                    ?></td>
                </tr>
                <tr>
                    <td><?php 
                        echo $anuncio['caracteristicas']['numHabitaciones'];
                        if($anuncio['caracteristicas']['numHabitaciones'] > 1) 
                            echo ' habitaciones';
                        else
                            echo ' habitación';
                    ?></td>
                </tr>
                <tr>
                    <td><?php echo $anuncio['caracteristicas']['superficieVivienda']; ?>m<sup>2</sup></td>
                </tr>
                <tr>
                    <td>Planta <?php echo $anuncio['caracteristicas']['planta']; ?></td>
                </tr>
                <tr>
                    <td>Año de construcción: <?php echo $anuncio['caracteristicas']['anyoConstruccion']; ?></td>
                </tr>
            </table>
        </article>
        
        <figure>
            <?php
                // Muestro todas las fotos en miniaturas porque lo pide el enunciado, esto en un futuro se sustituira por el carrusel al principio si le parece bien al profesor
                for($i = 0; $i < count($anuncio['fotos'][0]) && $i < 4; $i++) 
                    echo '<img class="miniatura" src="img/'.$anuncio['fotos'][0][$i].'" alt="'.$anuncio['fotos'][1][$i].'">';
            ?>
        </figure>

        <h4 style='margin-bottom: 1em;'><?php echo 'Esta vivienda pertenece a '.$anuncio['duenyo']?></h4>

        <nav id="simular">
            <a href="enviar_mensaje.php">Enviar mensaje al dueño</a>
        </nav>
        
<?php
    include 'includes/footer.php';
?>