<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    // obtengo el ID del anuncio de la URL
    $idAnuncio = (int)$_GET['idAnuncio'];
    $anuncio = null;
    
    $stmt = $db->prepare( // preparo la consulta (para evitar inyeccion sql, se pone ? donde se debe poner un parametro)
        "SELECT a.Superficie, a.NHabitaciones, a.NBanyos, a.Planta, a.Anyo, NomUsuario,
                a.FPrincipal, a.Alternativo, a.Titulo, a.Precio, a.FRegistro,
                p.NomPais, a.Ciudad, a.Texto, ta.NomTAnuncio, a.IdAnuncio, NomTVivienda
        FROM Anuncios a, Usuarios, TiposAnuncios ta, Paises p, TiposViviendas
        WHERE a.IdAnuncio = ? AND a.Pais = p.IdPais AND a.TAnuncio = ta.IdTAnuncio AND IdUsuario = Usuario AND TVivienda = IdTVivienda"
    );    
    
    if (!$stmt) { // comprobacion de si hay statement
        die('Error:  ' . $db->error); // para y da el error
    }
    
    $stmt->bind_param('i', $idAnuncio); // vinculo el parametro idAnuncio como entero

    if(!$stmt->execute()) { // ejecuto y miro si hay error
        die('Error: ' . $stmt->error);
    }

    $resultado = $stmt->get_result(); // guardo el resultado

    if($resultado) {
        $anuncio = $resultado->fetch_assoc(); // convierto el resultado en array asociativo
        $resultado->free();
    }

    $stmt->close();

    $stmt = $db->prepare('SELECT * FROM fotos WHERE Anuncio = ?'); // query para asociar fotos al anuncio 
    if(!$stmt) { // idem
        die('Error: '.$db->error); 
    }

    $stmt->bind_param('i', $idAnuncio); // vinculo el parametro idAnuncio como entero

    if(!$stmt->execute()) { // ejecuto y miro si hay error
        die('Error: ' . $stmt->error);
    }

    $resultado = $stmt->get_result(); // guardo el resultado
    if ($resultado) {
        // obtener todas las filas como array de arrays asociativos
        $fotos = $resultado->fetch_all(MYSQLI_ASSOC);
        $resultado->free();
    } else {
        $fotos = [];
    }

    $stmt->close();
    
    if(isset($_COOKIE['ultimos_4_anuncios'])) { // compruebo que la cookie existe
        $ultimos_4 = json_decode($_COOKIE['ultimos_4_anuncios'], true); // la recojo y decodifico de json
        $ultimos_4_copia = $ultimos_4;
            
        if($ultimos_4['0']['idAnuncio'] !== $idAnuncio && $ultimos_4['1']['idAnuncio'] !== $idAnuncio && $ultimos_4['2']['idAnuncio'] !== $idAnuncio && $ultimos_4['3']['idAnuncio'] !== $idAnuncio) { // evito repetir los anuncios
            // muevo todo una posicion a la derecha del array, sobreescribiendo el elemento que hay en la posicion 3
            $ultimos_4['3'] = $ultimos_4_copia['2'];
            $ultimos_4['2'] = $ultimos_4_copia['1'];
            $ultimos_4['1'] = $ultimos_4_copia['0'];
            $ultimos_4['0'] = array(    'idAnuncio' => $idAnuncio, 
                                        'tipoAnuncio' => $anuncio['NomTAnuncio'],
                                        'foto' => $anuncio['FPrincipal'],
                                        'altFoto' => $anuncio['Alternativo'],
                                        'titulo' => $anuncio['Titulo'],
                                        'precio' => $anuncio['Precio'],
                                        'ciudad' => $anuncio['Ciudad'],
                                        'pais' => $anuncio['NomPais']); // anyado el nuevo elemento a la posicion 0

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
                                        'tipoAnuncio' => $anuncio['NomTAnuncio'],
                                        'foto' => $anuncio['FPrincipal'],
                                        'altFoto' => $anuncio['Alternativo'],
                                        'titulo' => $anuncio['Titulo'],
                                        'precio' => $anuncio['Precio'],
                                        'ciudad' => $anuncio['Ciudad'],
                                        'pais' => $anuncio['NomPais']),

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
        <h2>Anuncio de <?php echo $anuncio['NomTAnuncio']; ?></h2>
        <h3><?php echo $anuncio['NomTVivienda']; ?></h3>
        <figure>
            <img id="carrusel" src="img/<?php echo $anuncio['FPrincipal']; ?>" alt="<?php echo $anuncio['Alternativo']; ?>">
            
            <figcaption>Foto de <?php echo strtolower($anuncio['NomTVivienda']); ?></figcaption>
            
            <button class="boton">&larr;</button>
            <button class="boton">&rarr;</button>
        </figure>


        <article>
            <h3><?php echo $anuncio['Titulo']; ?></h3>
            <h4><?php echo $anuncio['Texto']; ?></h4>

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
            ?>

            <time datetime="<?php echo htmlspecialchars($iso); ?>">
                <?php echo htmlspecialchars($visible); ?>
            </time>
            
            <table>
                <tr>
                    <th>Ciudad:</th>
                    <td><?php echo $anuncio['Ciudad']; ?></td>
                </tr>
                <tr>
                    <th>País:</th>
                    <td><?php echo $anuncio['NomPais']; ?></td>
                </tr>
                <tr>
                    <th>Precio:</th>
                    <td>
                        <?php 
                            $tipo_precio;
                            if($anuncio['NomTAnuncio'] === 'Venta')
                                $tipo_precio = '€';
                            else
                                $tipo_precio = '€/mes';
                            
                            echo $anuncio['Precio'].htmlspecialchars($tipo_precio);
                            
                        ?>
                    </td>
                </tr>
                <tr>
                    <th rowspan="5">Características:</th>
                    <td><?php 
                        echo $anuncio['NBanyos'];
                        if($anuncio['NBanyos'] > 1) 
                            echo ' baños';
                        else
                            echo ' baño';                      
                    ?></td>
                </tr>
                <tr>
                    <td><?php 
                        echo $anuncio['NHabitaciones'];
                        if($anuncio['NHabitaciones'] > 1) 
                            echo ' habitaciones';
                        else
                            echo ' habitación';
                    ?></td>
                </tr>
                <tr>
                    <td><?php echo $anuncio['Superficie']; ?>m<sup>2</sup></td>
                </tr>
                <tr>
                    <td>Planta <?php echo $anuncio['Planta']; ?></td>
                </tr>
                <tr>
                    <td>Año de construcción: <?php echo $anuncio['Anyo']; ?></td>
                </tr>
            </table>
        </article>
        
        <figure>
            <?php
                // Muestro todas las fotos en miniaturas porque lo pide el enunciado, esto en un futuro se sustituira por el carrusel al principio si le parece bien al profesor
                for($i = 0; $i < count($fotos) && $i < 4; $i++) 
                    echo '<img class="miniatura" src="img/'.htmlspecialchars($fotos[$i]['Foto']).'" alt="'.htmlspecialchars($fotos[$i]['Alternativo']).'">';
            ?>
        <nav id="masFotos">
            <a href="ver_fotos.php?idAnuncio=<?php echo htmlspecialchars($idAnuncio); ?>">Ver más fotos</a>
        </nav>
        </figure>


        <h4 style='margin-bottom: 1em;'><?php echo 'Esta vivienda pertenece a '.$anuncio['NomUsuario']?></h4>

        <nav id="simular">
            <a href="enviar_mensaje.php">Enviar mensaje al dueño</a>
            <a href="ver_mensajes.php?idAnuncio=<?php echo htmlspecialchars($idAnuncio); ?>">Ver mensajes de la oferta</a>
        </nav>
        
<?php
    include 'includes/footer.php';
?>