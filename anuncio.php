<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Página del anuncio";
    $acceder = "Acceder";
    $css="css/anuncio.css";
    include 'includes/header.php'; 
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