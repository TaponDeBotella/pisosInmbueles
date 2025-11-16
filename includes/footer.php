        <section id="ultimos4">
            <h3><strong>Últimos anuncios visitados</strong></h3>
            <ul id="ul_ultimos_anuncios">
            <?php
                // Solo mostrar los últimos anuncios si el usuario está logueado
                if(isset($_SESSION['logueado']) && isset($_COOKIE['ultimos_4_anuncios'])) {
                    $ultimos_4 = json_decode($_COOKIE['ultimos_4_anuncios'], true); // El segundo parametro true convierte el objeto en array asociativo, vamos, que accedo a los valores con ['algo'], en lugar de tener que hacer ->algo, pero vamos, si le paso un numero lo pasa a string, conque no tengo que pasar a string antes de meterlo
                    for($i = 0; $i < sizeof($ultimos_4); $i++) {
                        $idAnuncio = $ultimos_4[$i]['idAnuncio'];
                        $foto = $ultimos_4[$i]['foto'];
                        $altFoto = $ultimos_4[$i]['altFoto'];
                        $titulo = $ultimos_4[$i]['titulo'];
                        $ciudad = $ultimos_4[$i]['ciudad'];
                        $pais = $ultimos_4[$i]['pais'];
                        $precio = $ultimos_4[$i]['precio'];
                        $tipoAnuncio = $ultimos_4[$i]['tipoAnuncio'];

                        if($idAnuncio) { // si no esta vacio ese campo (se ha rellenado esa parte de la cookie)
                    
            ?>

                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=<?php echo $idAnuncio ?>">
                            <img src="img/<?php echo $foto; ?>" alt="<?php echo $altFoto; ?>">
                            <h4><?php echo $titulo; ?></h4>
                            <p class="detalles"><?php 
                                $detalles = [];
                                if($precio) {
                                    $precio_formateado = $precio . '€';
                                    if($tipoAnuncio === 'alquiler') $precio_formateado .= '/mes';
                                    $detalles[] = $precio_formateado;
                                }
                                if($ciudad) $detalles[] = $ciudad;
                                if($pais) $detalles[] = $pais;
                                echo implode(' | ', $detalles);
                            ?></p>
                        </a>
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
<?php
    include __DIR__ . '/cerrarDB.php'; // se cierra la conexion con la base de datos
?>