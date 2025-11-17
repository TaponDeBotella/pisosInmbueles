<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Crear anuncio";
    $acceder = "Acceder";
    $css="css/crear_anuncio.css";
    include 'includes/header.php'; 
    
    $query_tAnuncios = $db->query('SELECT * FROM TiposAnuncios'); // query a la db donde se filtra ya por FRegistro para obtener los mas recientes y se obtiene el nombre del pais y el tipo de anuncio
    
    if (!$query_tAnuncios) { // comprobacion de si hay query_anuncios
        die('Error:  ' . $db->error); // para y da el error
    }

    $tAnuncios = [];

    while ($fila = $query_tAnuncios->fetch_array(MYSQLI_ASSOC)) { // hago fetch con array asociativo y guardo las filas en $anuncios por orden de la query
        $tAnuncios[] = $fila;
    }


    $query_tViviendas = $db->query('SELECT * FROM TiposViviendas'); // query a la db donde se filtra ya por FRegistro para obtener los mas recientes y se obtiene el nombre del pais y el tipo de anuncio
    
    if (!$query_tViviendas) { // comprobacion de si hay query_anuncios
        die('Error:  ' . $db->error); // para y da el error
    }

    $tViviendas = [];

    while ($fila = $query_tViviendas->fetch_array(MYSQLI_ASSOC)) { // hago fetch con array asociativo y guardo las filas en $anuncios por orden de la query
        $tViviendas[] = $fila;
    }

    $query_paises = $db->query('SELECT * FROM Paises'); // query a la db donde se filtra ya por FRegistro para obtener los mas recientes y se obtiene el nombre del pais y el tipo de anuncio
    
    if (!$query_paises) { // comprobacion de si hay query_anuncios
        die('Error:  ' . $db->error); // para y da el error
    }

    $paises = [];

    while ($fila = $query_paises->fetch_array(MYSQLI_ASSOC)) { // hago fetch con array asociativo y guardo las filas en $anuncios por orden de la query
        $paises[] = $fila;
    }
    
    ?>
    <script src="js/otras_funciones.js"></script>

    <h1>Crear anuncio</h1>
    <form method="post">
        <section>
            <h3>Datos generales</h3>
            <label for="labelTipoAnuncio">Tipo de anuncio</label>
            <select class="input_select" name="TipoAnuncio" id="TipoAnuncio" onchange="cambiarTipoPrecio(this.value);">
                <option selected value=""></option>
                
                <?php
                
                for($i=0; $i<sizeof($tAnuncios); $i++)
                    echo '<option value="'.htmlspecialchars($tAnuncios[$i]['IdTAnuncio']).'">'.htmlspecialchars($tAnuncios[$i]['NomTAnuncio']).'</option>';
                ?>
                
            </select>
    
    
            <label for="labelTipoVivienda">Tipo de vivienda</label>
            <select class="input_select" name="tipoVivienda" id="tipoVivienda">
                <option selected value=""></option>
                
                <?php
                
                for($i=0; $i<sizeof($tViviendas); $i++)
                    echo '<option value="'.htmlspecialchars($tViviendas[$i]['IdTVivienda']).'">'.htmlspecialchars($tViviendas[$i]['NomTVivienda']).'</option>';
                ?>
            </select>
    
            <label for="labelTitulo">Título</label>
            <input class="input_select" type="text" name="titulo" id="titulo">
    
            <label for="labelTexto">Texto</label>
            <textarea class="input_select" name="texto" id="texto"></textarea>
            
            <label for="labelPrecio">Precio</label>
            <input class="input_select" type="number" step="50" min="0" name="precio" id="precio"><label id="tipoPrecio"></label>
            
            <label for="labelCiudad">Ciudad</label>
            <input class="input_select" type="text" name="ciudad" id="ciudad">
    
            <label for="labelCountry">País de residencia: </label>
    
            <select class="input_select" name="pais" id="country">
                <option selected value=""></option>
                <?php
                    for($i=0; $i<sizeof($paises); $i++) 
                        echo '<option value="'.htmlspecialchars($paises[$i]['IdPais']).'">'.htmlspecialchars($paises[$i]['NomPais']).'</option>'; 
                ?>
            </select>
        </section>

        <section>
            <h3>Características de la vivienda</h3>
            <label for="labelSuperficie">Superficie</label>
            <input class="input_select" type="number" step="10" min="0" name="superficie" id="superficie"> <label id="metrosCuadrados">m<sup>2</sup></label>

            <label for="labelBanyos">Baños</label>
            <input class="input_select" type="number" step="1" min="0" name="banyos" id="banyos">

            <label for="labelHabitaciones">Habitaciones</label>
            <input class="input_select" type="number" step="1" min="0" name="habitaciones" id="habitaciones">

            <label for="labelPlanta">Planta</label>
            <input class="input_select" type="number" step="1" min="0" name="planta" id="planta">

            <label for="labelFechaCreacion">Año de construcción de la vivienda</label>
            <input class="input_select" type="number" step="1" min="1900" max="<?php echo date('Y'); ?>" name="fechaCreacion" id="fechaCreacion" value="2000">
        </section>

        
        <section>
            <label for="labelTitulo">Título de la foto</label>
            <input type="text" id="titulo" name="titulo">
        </section>

        <section>
            <label for="labelFoto">Foto: </label>
            <label for="foto" class="boton" id="examinar" name="examinar">Examinar </label>
            <!-- <input type="file" accept="image/*" required> -->
            <input id="foto" type="file" style="display:none;">
        </section>

        <section>
            <input type="submit" class="boton">
        </section>


    </form>

<?php
    include 'includes/footer.php';
?>