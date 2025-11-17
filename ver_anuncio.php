    <?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Ver anuncio";
    $acceder = "Acceder";
    $css="css/ver_anuncio.css";
    include 'includes/header.php';
    include 'includes/anuncios.php';
    
    // obtengo el ID del anuncio de la URL
    $idAnuncio = (int)$_GET['idAnuncio'];
    $anuncio = null;

    $nombre_usuario = $_SESSION['nombre'];

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
        $anuncio = $resultado->fetch_array(MYSQLI_BOTH);
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

    $resultadoPaises = $db->query('SELECT IdPais, NomPais FROM Paises');
    if (!$resultadoPaises) {
        die('Error: ' . $db->error);
    }
    $paises = [];
    while ($fila = $resultadoPaises->fetch_array(MYSQLI_ASSOC)) {
        $paises[] = $fila;
    }

    if($anuncio['NomUsuario'] === $nombre_usuario) { // si el usuario es correcto
        
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
                    
                        for($i=0; $i<sizeof($tAnuncios); $i++) {
                            $cadena = '';
                            $cadena .= '<option ';

                            if($anuncio['NomTAnuncio'] == $tAnuncios[$i]['NomTAnuncio'])
                                $cadena .= 'selected ';

                            $cadena .= 'value="'.htmlspecialchars($tAnuncios[$i]['IdTAnuncio']).'">'.htmlspecialchars($tAnuncios[$i]['NomTAnuncio']).'</option>';

                            echo $cadena;
                        }
                    ?>
                
                </select>
    

        
                <label for="labelTipoVivienda">Tipo de vivienda</label>
                <select disabled class="input_select" name="tipoVivienda" id="tipoVivienda">
                    <option value=""></option>
                    <?php
                        for($i=0; $i<sizeof($tViviendas); $i++) {
                            $cadena = '';
                            $cadena .= '<option ';

                            if($anuncio['NomTVivienda'] == $tViviendas[$i]['NomTVivienda'])
                                $cadena .= 'selected ';

                            $cadena .= 'value="'.htmlspecialchars($tViviendas[$i]['IdTVivienda']).'">'.htmlspecialchars($tViviendas[$i]['NomTVivienda']).'</option>';

                            echo $cadena;
                        }
                    ?>
                </select>        
                <label for="labelTitulo">Título</label>
                <input readonly class="input_select" type="text" name="titulo" id="titulo" value="<?php echo $anuncio['Titulo']; ?>">
        
                <label for="labelTexto">Texto</label>
                <textarea readonly class="input_select" name="texto" id="texto"><?php echo $anuncio['Texto']; ?></textarea>
                
                <label for="labelPrecio">Precio</label>
                <input readonly class="input_select" type="number" step="50" min="0" name="precio" id="precio" value="<?php echo $anuncio['Precio']; ?>"><label id="tipoPrecio"><?php echo $anuncio['NomTAnuncio'] == 'alquiler' ? '€/mes' : '€'; ?></label>
                
                <label for="labelCiudad">Ciudad</label>
                <input readonly class="input_select" type="text" name="ciudad" id="ciudad" value="<?php echo $anuncio['Ciudad']; ?>">
        
                <label for="labelCountry">País de residencia: </label>
        
                <select disabled class="input_select" name="pais" id="country">
                    <?php
                        for($i=0; $i<sizeof($paises); $i++) {
                            $cadena = '<option';
                            if($paises[$i]['IdPais'] == $anuncio['NomPais']) 
                                $cadena .= ' selected';

                            $cadena .= ' value="'.htmlspecialchars($paises[$i]['IdPais']).'">'.htmlspecialchars($paises[$i]['NomPais']).'</option>';

                            echo $cadena;
                        }   
                    ?>
                </select>
            </section>

            <section>
                <h3>Características de la vivienda</h3>
                <label for="labelSuperficie">Superficie</label>
                <input readonly class="input_select" type="number" step="10" min="0" name="superficie" id="superficie" value="<?php echo $anuncio['Superficie']; ?>"> <label id="metrosCuadrados">m<sup>2</sup></label>

                <label for="labelBanyos">Baños</label>
                <input readonly class="input_select" type="number" step="1" min="0" name="banyos" id="banyos" value="<?php echo $anuncio['NBanyos']; ?>">

                <label for="labelHabitaciones">Habitaciones</label>
                <input readonly class="input_select" type="number" step="1" min="0" name="habitaciones" id="habitaciones" value="<?php echo $anuncio['NHabitaciones']; ?>">

                <label for="labelPlanta">Planta</label>
                <input readonly class="input_select" type="number" step="1" min="0" name="planta" id="planta" value="<?php echo $anuncio['Planta']; ?>">

                <label for="labelFechaCreacion">Año de construcción de la vivienda</label>
                <input readonly class="input_select" type="number" step="1" min="1900" max="<?php echo date('Y'); ?>" name="fechaCreacion" id="fechaCreacion" value="<?php echo $anuncio['Anyo']; ?>">
            </section>
        </form>
        
        <figure>
            <?php
                for($i = 0; $i < count($fotos); $i++) 
                    echo '<img class="miniatura" src="img/'.htmlspecialchars($fotos[$i]['Foto']).'" alt="'.htmlspecialchars($fotos[$i]['Alternativo']).'">';
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
    
    