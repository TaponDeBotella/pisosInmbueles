<?php
    session_start();
    
    $title="Resultados de Búsqueda";
    $css="css/busqueda.css";
    $acceder="Acceder";
    include 'includes/header.php';   
    include 'includes/anuncios.php';
    include 'includes/funciones.php';

    // si el usuario ha enviado el formulario de busqueda se recogen los datos por si hay que hacer filtros para los anuncios que se ven
    // si hay algun campo vacio entonces se pone una cadena vacia para que no de error al hacer la consulta por si acaso
    $tipoAnuncio = isset($_GET['tipo_anuncio']) ? $_GET['tipo_anuncio'] : '';
    $tipoVivienda = isset($_GET['tipo_inmueble']) ? $_GET['tipo_inmueble'] : '';
    $ciudad = isset($_GET['ciudad_busquedaForm']) ? $_GET['ciudad_busquedaForm'] : '';
    $pais = isset($_GET['pais_busqueda']) ? $_GET['pais_busqueda'] : '';
    $precioMinimo = isset($_GET['precio_minimo']) ? $_GET['precio_minimo'] : '';
    $precioMaximo = isset($_GET['precio_maximo']) ? $_GET['precio_maximo'] : '';
    $fechaPublicacion = isset($_GET['fecha_publicacion']) ? $_GET['fecha_publicacion'] : '';

    // ahora segun los datos del formlario se hace la consulta para ver que anuncios se mestran
    /* $query = 'SELECT a.*, p.NomPais, ta.NomTAnuncio FROM Anuncios a, TiposAnuncios ta, Paises p WHERE a.TAnuncio = ta.IdTAnuncio AND a.Pais = p.IdPais';

    if (!empty($tipoAnuncio)) { // el tipo de anuncio
        $query .= " AND a.TAnuncio = " . intval($tipoAnuncio);
    }

    if (!empty($tipoVivienda)) { // el tipo de vivienda
        $query .= " AND a.TVivienda = " . intval($tipoVivienda);
    }

    if (!empty($ciudad)) { // la ciudad, y con el lower para que si el usuario pone minusculas o mayusculas no de error
        $query .= " AND LOWER(a.Ciudad) LIKE LOWER('%" . $ciudad . "%')";
    }

    if (!empty($pais)) { // el pais
        $query .= " AND a.Pais = " . intval($pais);
    }

    if (!empty($precioMinimo)) { // el precio minimo
        $query .= " AND a.Precio >= " . floatval($precioMinimo);
    }

    if (!empty($precioMaximo)) { // el precio maximo
        $query .= " AND a.Precio <= " . floatval($precioMaximo);
    }

    if (!empty($fechaPublicacion)) { // la fecha de publicacion, que muestre los anuncios desde esa fecha en adelante (preguntar??? porque no se si quiere que vaya asi o que muestre los de la fecha exacta solo)
        $query .= " AND DATE(a.FRegistro) >= '" . $fechaPublicacion . "'";
    }

    $query .= " ORDER BY a.FRegistro DESC"; // y se ordena por fecha de registro descendente para que los mas nuevos salgan primero */

    $query = 'SELECT a.*, p.NomPais, ta.NomTAnuncio FROM Anuncios a, TiposAnuncios ta, Paises p WHERE a.TAnuncio = ta.IdTAnuncio AND a.Pais = p.IdPais';

    $tipoparams = '';
    $params = [];

    if (!empty($tipoAnuncio)) { // el tipo de anuncio
        $query .= " AND a.TAnuncio = ?";
        $tipoparams .= 'i';
        $tipoAnuncioParam = ($tipoAnuncio !== '' ? intval($tipoAnuncio) : 0);
        $params[] = $tipoAnuncioParam;
    }

    if (!empty($tipoVivienda)) { // el tipo de vivienda
        $query .= " AND a.TVivienda = ?";
        $tipoparams .= 'i';
        $tipoViviendaParam = ($tipoVivienda !== '' ? intval($tipoVivienda) : 0);
        $params[] = $tipoViviendaParam;
    }

    if (!empty($ciudad)) { // la ciudad, y con el lower para que si el usuario pone minusculas o mayusculas no de error
        $query .= " AND LOWER(a.Ciudad) LIKE CONCAT('%', LOWER(?), '%')";
        $tipoparams .= 's';
        $params[] = $ciudad;
    }

    if (!empty($pais)) { // el pais
        $query .= " AND a.Pais = ?";
        $tipoparams .= 'i';
        $paisParam = ($pais !== '' ? intval($pais) : 0);
        $params[] = $paisParam;
    }

    if (!empty($precioMinimo)) { // el precio minimo
        $query .= " AND a.Precio >= ?";
        $tipoparams .= 'd';
        $precioMinParam = ($precioMinimo !== '' ? floatval($precioMinimo) : 0.0);
        $params[] = $precioMinParam;
    }

    if (!empty($precioMaximo)) { // el precio maximo
        $query .= " AND a.Precio <= ?";
        $tipoparams .= 'd';
        $precioMaxParam = ($precioMaximo !== '' ? floatval($precioMaximo) : 0.0);
        $params[] = $precioMaxParam;
    }

    if (!empty($fechaPublicacion)) { // la fecha de publicacion, que muestre los anuncios desde esa fecha en adelante (preguntar??? porque no se si quiere que vaya asi o que muestre los de la fecha exacta solo)
        $query .= " AND DATE(a.FRegistro) >= ?";
        $tipoparams .= 's';
        $params[] = $fechaPublicacion;
    }

    $query .= " ORDER BY a.FRegistro DESC"; // y se ordena por fecha de registro descendente para que los mas nuevos salgan primero

    $stmt = $db->prepare($query);

    if (!$stmt)  // comprobacion de si hay statement
        die('Error:  ' . $db->error);

    // preparo las variables para bind_param
    if(!empty($params)) {
        // primer elemento de bind debe ser la cadena de tipos
        array_unshift($params, $tipoparams); // esto pone $types primero en el array $params

        call_user_func_array([$stmt, 'bind_param'], refValues($params)); // con esto llamo a la funcion bind_param usando las referencias en $params
    }

    if(!$stmt->execute()) { // ejecuto y miro si hay error
        die('Error: ' . $stmt->error);
    }

    $resultado = $stmt->get_result(); // guardo el resultado del prepared statement
    if (!$resultado) {
        $stmt->close();
        die('Error getting result: ' . $db->error);
    }

    // recoger todos los anuncios en un array (más eficiente que fetch en bucle)
    $anuncios = $resultado->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    
    


    // se sacan todos los datos de la base de datos para ponerlos en los filtros en los selects
    $resultadoTiposAnuncios = $db->query('SELECT IdTAnuncio, NomTAnuncio FROM TiposAnuncios');
    if (!$resultadoTiposAnuncios) { // si no hay entonces se lanza un error
        die('Error: ' . $db->error);
    }
    $tiposAnuncios = []; // se crea el array para guardar los tipos de anuncios
    while ($fila = $resultadoTiposAnuncios->fetch_array(MYSQLI_ASSOC)) { // y se recorre toda la tabla
        $tiposAnuncios[] = $fila; // se guarda conforme va recorriendo la tabla
    }

    // lo mismo pero con los tipos de viviendas
    $resultadoTiposViviendas = $db->query('SELECT IdTVivienda, NomTVivienda FROM TiposViviendas');
    if (!$resultadoTiposViviendas) {
        die('Error: ' . $db->error);
    }
    $tiposViviendas = [];
    while ($fila = $resultadoTiposViviendas->fetch_array(MYSQLI_ASSOC)) {
        $tiposViviendas[] = $fila;
    }

    // y tambien con los paises, igual todo
    $resultadoPaises = $db->query('SELECT IdPais, NomPais FROM Paises');
    if (!$resultadoPaises) {
        die('Error: ' . $db->error);
    }
    $paises = [];
    while ($fila = $resultadoPaises->fetch_array(MYSQLI_ASSOC)) {
        $paises[] = $fila;
    }
?>

    <h1>Resultados de Búsqueda</h1>

    <section id="sectionsBusquedaResultado">
        <section id="filtrosBusquedaLateral">
                <h3>Filtros de Búsqueda</h3>
                <form id="formBusquedaFiltros" action="busqueda.php" method="GET">
                    <p>
                    <label for="tipo_anuncio">Tipo de anuncio: </label>
                    <select id="tipo_anuncio" name="tipo_anuncio" class="input_select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($tiposAnuncios as $tipo): // se recorre todo el array?>
                            <option value="<?php echo $tipo['IdTAnuncio'];  // se le pone la opcion?>">
                                <?php echo $tipo['NomTAnuncio']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </p>

                    <p>
                    <label for="tipo_inmueble">Tipo de inmueble: </label>
                    <select id="tipo_inmueble" name="tipo_inmueble" class="input_select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($tiposViviendas as $vivienda): // lo mismo que con los tipos de anuncios?>
                            <option value="<?php echo $vivienda['IdTVivienda']; ?>">
                                <?php echo $vivienda['NomTVivienda']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </p>

                    <p>
                    <label for="pais_busqueda">País: </label>
                    <select id="pais_busqueda" name="pais_busqueda" class="input_select">
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($paises as $pais): // y los paises mas de lo mismo?>
                            <option value="<?php echo $pais['IdPais']; ?>">
                                <?php echo $pais['NomPais']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </p>

                    <p>
                    <label for="ciudad_busquedaForm">Ciudad: </label>
                    <input type="text" id="ciudad_busquedaForm" name="ciudad_busquedaForm" class="input_select" value="<?php 
                        if(isset($_GET['ciudad_busqueda'])) { // si el usuario ha hecho una busqueda rapida entonces se mete al form
                            echo htmlspecialchars($_GET['ciudad_busqueda']);
                        } else { // si no simplemente se deja vacio y ya
                            echo '';
                        }
                    ?>">
                    </p>

                    <p>
                    <label for="precio_minimo">Precio mínimo: </label>
                    <input type="number" id="precio_minimo" name="precio_minimo" class="input_select">
                    </p>

                    <p>
                    <label for="precio_maximo">Precio máximo: </label>
                    <input type="number" id="precio_maximo" name="precio_maximo" class="input_select">
                    </p>

                    <p> 
                    <label for="fecha_publicacion">Fecha de publicación: </label>
                    <input type="date" id="fecha_publicacion" name="fecha_publicacion" class="input_select">       
                    </p>

                    <p>
                    <input type="submit" value="Confirmar" class="boton">
                    <input type="reset" value="Reset" class="boton">
                    </p>
                </form>
            </section>

            <section id="sectionArticulos"> <!-- ahora se hace un foreach para mostrar los anuncios necesarios y no los mismos 4 de siempre-->
                <?php if (!empty($anuncios)): // si hay anuncios se procede con el foreach?>
                    <ul id="ul_articulos">
                        <?php foreach ($anuncios as $anuncio): // se hace el foreach de los anuncios que se han obtenido con los filtros?>
                            <li>
                                <article>
                                    <a href="anuncio.php?idAnuncio=<?php echo $anuncio['IdAnuncio']; ?>">  <!-- se le pone el enlace al anuncio -->
                                        <img class="imagen_articulo" src="img/<?php echo htmlspecialchars($anuncio['FPrincipal']); ?>" alt="<?php echo htmlspecialchars($anuncio['Alternativo']); ?>"> <!-- la foto o el texto alternativo dependiendo de si va o no -->
                                    </a>
                                    <a href="anuncio.php?idAnuncio=<?php echo $anuncio['IdAnuncio']; ?>" class="a_tituloPublicacion"> <!-- el titulo del anuncio como enlace -->
                                        <h2><?php echo htmlspecialchars($anuncio['Titulo']); ?></h2>
                                    </a>  
                                    <p class="fecha">Fecha publicación: <time datetime="<?php echo date('Y-m-d', strtotime($anuncio['FRegistro'])); ?>"><?php echo date('d-m-Y', strtotime($anuncio['FRegistro'])); ?></time></p> <!-- la fecha de publicacion del anuncio -->

                                    <?php 
                                        if($anuncio['NomTAnuncio'] === 'Venta') // si es vennta se pone solo el precio
                                            $tipo_precio = '€';
                                        else
                                            $tipo_precio = '€/mes'; // si es alquiler se pone el precio por mes 
                                    ?>
                                    <p class="precio">Precio: <?php echo number_format($anuncio['Precio'], 2, ',', '.'); ?><?php echo $tipo_precio; ?></p> <!-- el precio del anuncio -->

                                    <p class="pais">País: <?php echo htmlspecialchars($anuncio['NomPais']); ?></p> <!-- el pais del anuncio -->
                                    <p class="ciudad">Ciudad: <?php echo htmlspecialchars($anuncio['Ciudad']); ?></p> <!-- la ciudad del anuncio -->
                                    <p class="p_descripcionA"><?php echo htmlspecialchars(substr($anuncio['Texto'], 0, 100)) . '...'; ?></p> <!-- y la descripcion del anuncio, y si se pasa de 100 caracteres pues se pone ... para que no ocupe mucho -->
                                </article>       
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No se encontraron anuncios con estos filtros.</p> <!-- si no hay anuncios que cumplan los filtros se muestra este mensaje y ya esta-->
                <?php endif; ?>
        </section>
    </section>
                
<?php
    include 'includes/footer.php';