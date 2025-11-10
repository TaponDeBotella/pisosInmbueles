        <section id="ultimos4">
            <h3><strong>Últimos anuncios visitados</strong></h3>
            <ul id="ul_ultimos_anuncios">
            <?php
                if(isset($_COOKIE['ultimos_4_anuncios'])) { // lo pongo por si acaso, pero siempre se setea en el header
                    $ultimos_4 = json_decode($_COOKIE['ultimos_4_anuncios'], true); // El segundo parámetro true convierte el objeto en array asociativo, vamos, que accedo a los valores con ['algo'], en lugar de tener que hacer ->algo, pero vamos, si le paso un numero lo pasa a string, conque no tengo que pasar a string antes de meterlo
                    for($i = 0; $i < sizeof($ultimos_4); $i++) {
                        $idAnuncio = $ultimos_4[$i]['idAnuncio'];
                        $tipoAnuncio = $ultimos_4[$i]['tipoAnuncio'];
                        $foto = $ultimos_4[$i]['foto'];
                        $altFoto = $ultimos_4[$i]['altFoto'];
                        $titulo = $ultimos_4[$i]['titulo'];
                        $precio = $ultimos_4[$i]['precio'];
                        $ciudad = $ultimos_4[$i]['ciudad'];
                        $pais = $ultimos_4[$i]['pais'];

                        if($idAnuncio) { // si no esta vacio ese campo (se ha rellenado esa parte de la cookie)
                    
            ?>

                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=<?php echo $idAnuncio ?>">
                            <img class="imagen_articulo" src="img/<?php echo $foto; ?>" alt="<?php echo $altFoto; ?>">
                        </a>
                        <a href="anuncio.php?idAnuncio=<?php echo $idAnuncio; ?>" class="a_tituloPublicacion">
                            <h2><?php echo $titulo; ?></h2>
                        </a>  
                        <p class="precio">Precio:   <?php 
                                                        $cadena =  $precio.'€'; // precio en €
                                                        if($tipoAnuncio == 'alquiler')
                                                            $cadena .= '/mes'; // si es un alquiler pongo el /mes
                                                        echo $cadena; // inserto la cadena en el <p>
                                                    ?>
                        </p>
                        <p class="pais">País: <?php echo $pais; ?></p>
                        <p class="ciudad">Ciudad: <?php echo $ciudad; ?></p>
                    </article>       
                </li>
            
                <?php
                        }
                    }
                }
                ?>
            </ul>
        </section>

    </body>
        
        <footer> ©<time datetime="2025">2025</time> PI - Pisos & Inmuebles - Desarrollado por Ariadna Guillén y Raúl Cervera. <a href="accesibilidad.php">Accesibilidad de la Página</a></footer>

    </html>