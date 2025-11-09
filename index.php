<?php
    session_start();
    
    $title = "Inicio";
    $acceder = "Acceder";
    $css = "css/index.css";
    include 'includes/header.php';
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
                            <img class="imagen_articulo" src="img/orihuela.jpg" alt="Foto piso">
                        </a>
                        <a href="anuncio.php?idAnuncio=1" class="a_tituloPublicacion">
                            <h2>Piso en Orihuela</h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                        <p class="precio">Precio: 200.000€</p>
                        <p class="pais">País: España</p>
                        <p class="ciudad">Ciudad: Orihuela</p>
                        <p class="p_descripcionA">Piso grande en Orihuela, con 3 habitaciones y 2 baños. Patatas fritas incluidas.</p>    
                    </article>       
                </li>
                <li>
                    <article>
                        <a href="alerta.php">
                            <img class="imagen_articulo" src="img/orihuela.jpg" alt="Foto piso">
                        </a>
                        <a href="alerta.php" class="a_tituloPublicacion">
                            <h2>Piso en Orihuela</h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                        <p class="precio">Precio: 200.000€</p>
                        <p class="pais">País: España</p>
                        <p class="ciudad">Ciudad: Orihuela</p>
                        <p class="p_descripcionA">Piso grande en Orihuela, con 3 habitaciones y 2 baños. Patatas fritas incluidas.</p>    
                    </article> 
                </li>
                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=3">
                            <img class="imagen_articulo" src="img/orihuela.jpg" alt="Foto piso">
                        </a>
                        <a href="anuncio.php?idAnuncio=3" class="a_tituloPublicacion">
                            <h2>Piso en Orihuela</h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                        <p class="precio">Precio: 200.000€</p>
                        <p class="pais">País: España</p>
                        <p class="ciudad">Ciudad: Orihuela</p>
                        <p class="p_descripcionA">Piso grande en Orihuela, con 3 habitaciones y 2 baños. Patatas fritas incluidas.</p>    
                    </article>
                </li>
                <li>
                    <article>
                        <a href="anuncio.php?idAnuncio=4">
                            <img class="imagen_articulo" src="img/orihuela.jpg" alt="Foto piso">
                        </a>
                        <a href="anuncio.php?idAnuncio=4" class="a_tituloPublicacion">
                            <h2>Piso en Orihuela</h2>
                        </a>  
                        <p class="fecha">Fecha publicación: <time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                        <p class="precio">Precio: 200.000€</p>
                        <p class="pais">País: España</p>
                        <p class="ciudad">Ciudad: Orihuela</p>
                        <p class="p_descripcionA">Piso grande en Orihuela, con 3 habitaciones y 2 baños. Patatas fritas incluidas.</p>    
                    </article>
                </li>
            </ul>
        </section>

<?php
    include 'includes/footer.php';
?>

