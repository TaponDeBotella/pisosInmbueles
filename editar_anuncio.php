<?php // ESTO ES UNA COPIA DE CREAR ANUNCIO PERO CARGANDO LOS DATOS DEL ANUNCIO A EDITAR
    require_once 'includes/proteger.php';
    require_once 'includes/iniciarDB.php';
    
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $idAnuncio = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($idAnuncio <= 0) { // si el id del anuncio no es valido se redirige a mis anuncios con el error
        header('Location: mis_anuncios.php?error=anuncio_invalido');
        exit;
    }
    
    // se saca toda la informacion del anuncio que el usuario quiere edeitar
    $stmt_anuncio = $db->prepare('SELECT * FROM Anuncios WHERE IdAnuncio = ?');
    $stmt_anuncio->bind_param('i', $idAnuncio);
    $stmt_anuncio->execute();
    $resultado_anuncio = $stmt_anuncio->get_result();
    
    if ($resultado_anuncio->num_rows === 0) { // si no existe el anuncio se redirige a mis anuncios con el error
        header('Location: mis_anuncios.php?error=anuncio_no_existe');
        exit;
    }
    
    $anuncio = $resultado_anuncio->fetch_assoc(); // se guarda la informacion del anuncio
    
    // ahora se verifica que el anuncio pertenece al usuario porque si no no puede hacer nada
    $stmt_verificar = $db->prepare('SELECT IdUsuario FROM Usuarios WHERE NomUsuario = ?');
    $stmt_verificar->bind_param('s', $_SESSION['nombre']);
    $stmt_verificar->execute();
    $resultado_verificar = $stmt_verificar->get_result();
    $usuario = $resultado_verificar->fetch_assoc();
    
    if ($anuncio['Usuario'] !== $usuario['IdUsuario']) { // si no es su anuncio se redirige a mis anuncios con el error
        header('Location: mis_anuncios.php?error=anuncio_no_pertenece');
        exit;
    }
    
    $title="Editar anuncio";
    $acceder = "Acceder";
    $css="css/crear_anuncio.css"; // como es la misma pagina entonces si le pongo el mismo css va exactamnete igual
    include 'includes/header.php'; 
    
    $query_tAnuncios = $db->query('SELECT * FROM TiposAnuncios'); // se sacan los tipos de anuncios
    
    if (!$query_tAnuncios) { // si hay error se manda
        die('Error:  ' . $db->error);
    }

    $tAnuncios = [];

    while ($fila = $query_tAnuncios->fetch_array(MYSQLI_ASSOC)) { // se guardan en un array
        $tAnuncios[] = $fila;
    }


    $query_tViviendas = $db->query('SELECT * FROM TiposViviendas'); // se sacan los tipos de viviendas tambien y se guardan igual que lo otro
    
    if (!$query_tViviendas) {
        die('Error:  ' . $db->error);
    }

    $tViviendas = [];

    while ($fila = $query_tViviendas->fetch_array(MYSQLI_ASSOC)) { 
        $tViviendas[] = $fila;
    }

    $query_paises = $db->query('SELECT * FROM Paises'); // y lo mismo con los paises
    
    if (!$query_paises) {
        die('Error:  ' . $db->error);
    }

    $paises = [];

    while ($fila = $query_paises->fetch_array(MYSQLI_ASSOC)) {
        $paises[] = $fila;
    }
    
    ?>
    <script src="js/otras_funciones.js"></script>

    <h1>Editar anuncio</h1> <!-- todo esto es copia pega de la pagina de crear anuncio pero en los inputs se le cargan los datos que ya tiene el anuncio -->
    <form method="post" action="respuesta_editar_anuncio.php">
        <input type="hidden" name="idAnuncio" value="<?php echo htmlspecialchars($anuncio['IdAnuncio']); ?>">
        <section>
            <h3>Datos generales</h3>
            <label for="labelTipoAnuncio">Tipo de anuncio</label>
            <select class="input_select" name="TipoAnuncio" id="TipoAnuncio" onchange="cambiarTipoPrecio(this.value);">
                <option selected value=""></option>
                
                <?php
                
                for($i=0; $i<sizeof($tAnuncios); $i++) {
                    $selected = ($tAnuncios[$i]['IdTAnuncio'] == $anuncio['TAnuncio']) ? 'selected' : '';
                    echo '<option value="'.htmlspecialchars($tAnuncios[$i]['IdTAnuncio']).'" '.$selected.'>'.htmlspecialchars($tAnuncios[$i]['NomTAnuncio']).'</option>';
                }
                ?>
                
            </select>
    
    
            <label for="labelTipoVivienda">Tipo de vivienda</label>
            <select class="input_select" name="tipoVivienda" id="tipoVivienda">
                <option selected value=""></option>
                
                <?php
                
                for($i=0; $i<sizeof($tViviendas); $i++) {
                    $selected = ($tViviendas[$i]['IdTVivienda'] == $anuncio['TVivienda']) ? 'selected' : '';
                    echo '<option value="'.htmlspecialchars($tViviendas[$i]['IdTVivienda']).'" '.$selected.'>'.htmlspecialchars($tViviendas[$i]['NomTVivienda']).'</option>';
                }
                ?>
            </select>
    
            <label for="labelTitulo">Título</label>
            <input class="input_select" type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($anuncio['Titulo']); ?>">
    
            <label for="labelTexto">Texto</label>
            <textarea class="input_select" name="texto" id="texto"><?php echo htmlspecialchars($anuncio['Texto']); ?></textarea>
            
            <label for="labelPrecio">Precio</label>
            <input class="input_select" type="number" step="50" min="0" name="precio" id="precio" value="<?php echo htmlspecialchars($anuncio['Precio']); ?>"><label id="tipoPrecio"></label>
            
            <label for="labelCiudad">Ciudad</label>
            <input class="input_select" type="text" name="ciudad" id="ciudad" value="<?php echo htmlspecialchars($anuncio['Ciudad']); ?>">
    
            <label for="labelCountry">País de residencia: </label>
    
            <select class="input_select" name="pais" id="country">
                <option selected value=""></option>
                <?php
                    for($i=0; $i<sizeof($paises); $i++) {
                        $selected = ($paises[$i]['IdPais'] == $anuncio['Pais']) ? 'selected' : '';
                        echo '<option value="'.htmlspecialchars($paises[$i]['IdPais']).'" '.$selected.'>'.htmlspecialchars($paises[$i]['NomPais']).'</option>';
                    }
                ?>
            </select>
        </section>

        <section>
            <h3>Características de la vivienda</h3>
            <label for="labelSuperficie">Superficie</label>
            <input class="input_select" type="number" step="10" min="0" name="superficie" id="superficie" value="<?php echo htmlspecialchars($anuncio['Superficie']); ?>"> <label id="metrosCuadrados">m<sup>2</sup></label>

            <label for="labelBanyos">Baños</label>
            <input class="input_select" type="number" step="1" min="0" name="banyos" id="banyos" value="<?php echo htmlspecialchars($anuncio['NBanyos']); ?>">

            <label for="labelHabitaciones">Habitaciones</label>
            <input class="input_select" type="number" step="1" min="0" name="habitaciones" id="habitaciones" value="<?php echo htmlspecialchars($anuncio['NHabitaciones']); ?>">

            <label for="labelPlanta">Planta</label>
            <input class="input_select" type="number" step="1" min="0" name="planta" id="planta" value="<?php echo htmlspecialchars($anuncio['Planta']); ?>">

            <label for="labelFechaCreacion">Año de construcción de la vivienda</label>
            <input class="input_select" type="number" step="1" min="1900" max="<?php echo date('Y'); ?>" name="fechaCreacion" id="fechaCreacion" value="<?php echo htmlspecialchars($anuncio['Anyo']); ?>">
        </section>

        <section>
            <input type="submit" class="boton" value="Guardar cambios">
        </section>

    </form>

<?php
    include 'includes/footer.php';
?>
