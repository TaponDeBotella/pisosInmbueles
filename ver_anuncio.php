    <?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Ver anuncio";
    $acceder = "Acceder";
    $css="css/ver_anuncio.css";
    include 'includes/header.php';
    include 'includes/anuncios.php';
    

    $anuncio = null; // inicializo el anuncio
    $anuncios_del_usuario = [];
    if(isset($_GET['idAnuncio'])) { // compruebo que esta en la peticion get de la url
        for($i=0; $i<sizeof($anuncios); $i++) { // para cada  anuncio de anuncios
            if($_GET['idAnuncio'] == $anuncios[$i]['idAnuncio']) // si la id del anuncio de esta iteracion coincide con el que recibo por el get
                $anuncio = $anuncios[$i]; // asigno a anuncio ese anuncio

            if($anuncios[$i]['duenyo'] == $_SESSION['nombre'])
                $anuncios_del_usuario[] = $anuncios[$i]; 
        }
    }

    if($anuncio['duenyo'] == $_SESSION['nombre']) { // si el usuario es correcto
        
    ?>


        <script src="js/otras_funciones.js"></script>

        <h1>Crear anuncio</h1>
        <form method="post">
            <section>
                <h3>Datos generales</h3>
                <label for="labelTipoAnuncio">Tipo de anuncio</label>
                <select disabled class="input_select" name="TipoAnuncio" id="TipoAnuncio" onchange="cambiarTipoPrecio(this.value);">
                    <option value=""></option>
                    <?php
                    if($anuncio['tipoAnuncio'] == 'alquiler')
                        echo '  <option selected value="alquiler">Alquiler</option>
                                <option value="venta">Venta</option>
                        ';
                    else
                        echo '  <option value="alquiler">Alquiler</option>
                                <option selected value="venta">Venta</option>
                        ';
                    ?>
                </select>
        
        
                <label for="labelTipoVivienda">Tipo de vivienda</label>
                <input readonly class="input_select" type="text" name="tipoVivienda" id="tipoVivienda" value="<?php echo $anuncio['tipoVivienda']; ?>" placeholder="Habitación, piso, casa...">
        
                <label for="labelTitulo">Título</label>
                <input readonly class="input_select" type="text" name="titulo" id="titulo" value="<?php echo $anuncio['titulo']; ?>">
        
                <label for="labelTexto">Texto</label>
                <textarea readonly class="input_select" name="texto" id="texto"><?php echo $anuncio['texto']; ?></textarea>
                
                <label for="labelPrecio">Precio</label>
                <input readonly class="input_select" type="number" step="50" min="0" name="precio" id="precio" value="<?php echo $anuncio['precio']; ?>"><label id="tipoPrecio"><?php echo $anuncio['tipoAnuncio'] == 'alquiler' ? '€/mes' : '€'; ?></label>
                
                <label for="labelCiudad">Ciudad</label>
                <input readonly class="input_select" type="text" name="ciudad" id="ciudad" value="<?php echo $anuncio['ciudad']; ?>">
        
                <label for="labelCountry">País de residencia: </label>
        
                <select disabled class="input_select" name="pais" id="country">
                    <?php
                            $paises_value = ["Alemania", "Espanya", "Francia", "Grecia", "Italia", "Polonia", "ReinoUnido", "Suecia", "Suiza", "Ucrania"];
                            $paises_nombre_bien_puesto = ["Alemania", "España", "Francia", "Grecia", "Italia", "Polonia", "Reino Unido", "Suecia", "Suiza", "Ucrania"];
                            for($i=0; $i<sizeof($paises_value); $i++) {
                                $cadena = '<option';
                                if($paises_nombre_bien_puesto[$i] == $anuncio['pais']) 
                                    $cadena .= ' selected';

                                $cadena .= ' value="'.htmlspecialchars($paises_value[$i]).'">'.htmlspecialchars($paises_nombre_bien_puesto[$i]).'</option>';

                                echo $cadena;
                            }
                    ?>

                </select>
            </section>

            <section>
                <h3>Características de la vivienda</h3>
                <label for="labelSuperficie">Superficie</label>
                <input readonly class="input_select" type="number" step="10" min="0" name="superficie" id="superficie" value="<?php echo $anuncio['caracteristicas']['superficieVivienda']; ?>"> <label id="metrosCuadrados">m<sup>2</sup></label>

                <label for="labelBanyos">Baños</label>
                <input readonly class="input_select" type="number" step="1" min="0" name="banyos" id="banyos" value="<?php echo $anuncio['caracteristicas']['numBanyo']; ?>">

                <label for="labelHabitaciones">Habitaciones</label>
                <input readonly class="input_select" type="number" step="1" min="0" name="habitaciones" id="habitaciones" value="<?php echo $anuncio['caracteristicas']['numHabitaciones']; ?>">

                <label for="labelPlanta">Planta</label>
                <input readonly class="input_select" type="number" step="1" min="0" name="planta" id="planta" value="<?php echo $anuncio['caracteristicas']['planta']; ?>">

                <label for="labelFechaCreacion">Año de construcción de la vivienda</label>
                <input readonly class="input_select" type="number" step="1" min="1900" max="<?php echo date('Y'); ?>" name="fechaCreacion" id="fechaCreacion" value="<?php echo $anuncio['caracteristicas']['anyoConstruccion']; ?>">
            </section>
        </form>
        
        <figure>
            <?php
                for($i = 0; $i < count($anuncio['fotos'][0]) && $i < 4; $i++) 
                    echo '<img class="miniatura" src="img/'.$anuncio['fotos'][0][$i].'" alt="'.$anuncio['fotos'][1][$i].'">';
            ?>
        </figure>

        <form method="post">   
            <section>
                <h3>¿Añadir una nueva foto?</h3>
                <label for="labelAnuncioAElegir">Elige el anuncio al que quieres añadir la foto</label>
                <select class="input_select" disabled name="anuncio" id="anuncio">
                    <?php
                        for($i=0; $i<sizeof($anuncios_del_usuario); $i++){
                            $cadena = '<option';
                            if($anuncios_del_usuario[$i]['idAnuncio'] == $anuncio['idAnuncio']) 
                                $cadena .= ' selected';

                            $cadena .= ' value="'.htmlspecialchars($anuncios_del_usuario[$i]['idAnuncio']).'">'.htmlspecialchars($anuncios_del_usuario[$i]['titulo']).'</option>';

                            echo $cadena;
                        }
                    ?>
                </select>
                
                <label for="labelTitulo">Título de la foto</label>
                <input class="input_select" required type="text" id="titulo" name="titulo">
                
                <label for="labelTextoAlternativo">Texto alternativo</label>
                <textarea class="input_select" required minlength="10" name="textoAlternativo" id="textoAlternativo"></textarea>

                <label for="labelFoto">Foto: </label>
                <label for="foto" class="boton" id="examinar" name="examinar">Examinar </label>
                <!-- <input type="file" accept="image/*" required> -->
                <input required id="foto" type="file" style="display:none;">

                <input type="submit" class="boton">
            </section>
        </form>

<?php
    } // cierro el if inicial
    else
        echo '<p><strong>USUARIO EQUIVOCADO</strong></p>';

    include 'includes/footer.php';
?>
    
    