<?php
    $title="Resultados de Búsqueda";
    $css="css/busqueda.css";
    $acceder="Acceder";
    include 'includes/header.php';   
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
                        <option value="venta">Venta</option>
                        <option value="alquiler">Alquiler</option>

                    </select>
                    </p>

                    <p>
                    <label for="tipo_inmueble">Tipo de inmueble: </label>
                    <select id="tipo_inmueble" class="input_select">
                        <option value="obra_nueva">Obra nueva</option>
                        <option value="vivienda">Vivienda</option>
                        <option value="oficina">Oficina</option>
                        <option value="local">Local</option>
                        <option value="garaje">Garaje</option>

                    </select>
                    </p>

                    <p>
                    <label for="pais_busqueda">País: </label>
                    <select id="pais_busqueda" class="input_select">
                        <option value="gr">Alemania</option>
                        <option value="es">España</option>
                        <option value="fr">Francia</option>
                        <option value="gre">Grecia</option>
                        <option value="it">Italia</option>
                        <option value="pol">Polonia</option>
                        <option value="uk">Reino unido</option>
                        <option value="swi">Suecia</option>
                        <option value="swe">Suiza</option>
                        <option value="ukr">Ucrania</option>
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
                        <a href="alerta">
                            <img class="imagen_articulo" src="img/orihuela.jpg" alt="Foto piso">
                        </a>
                        <a href="alerta" class="a_tituloPublicacion">
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
        </section>
<?php
    include 'includes/footer.php';