<?php
    session_start();
    
    $title = "Ver mensajes";
    $acceder = "Acceder";
    $css = "css/ver_mensajes.css";
    include 'includes/header.php';


    // obtengo el ID del anuncio de la URL
    $idAnuncio = (int)$_GET['idAnuncio'];
    $anuncio = null;

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
        $anuncio = $resultado->fetch_assoc(); // convierto el resultado en array asociativo
        $resultado->free();
    }

    $stmt->close();

    $stmt = $db->prepare('SELECT Texto, Anuncio, NomTMensaje, FRegistro FROM Mensajes, TiposMensajes WHERE TMensaje = IdTMensaje AND Anuncio = ?'); // query para asociar mensajes al anuncio 
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
        $mensajes = $resultado->fetch_all(MYSQLI_ASSOC);
        $resultado->free();
    } else {
        $mensajes = [];
    }

    $stmt->close();
?>


    <h1>Ver mensajes del anuncio</h1>
    <?php 
        $tipo_precio;
        if($anuncio['NomTAnuncio'] === 'Venta')
            $tipo_precio = '€';
        else
            $tipo_precio = '€/mes';        
    ?>
    <h2><?php echo htmlspecialchars($anuncio['Titulo']).' | '.htmlspecialchars($anuncio['Ciudad']).' | '.htmlspecialchars($anuncio['NomPais']).' | '.htmlspecialchars(round($anuncio['Precio'], 0)).htmlspecialchars($tipo_precio); ?></h2>
    
    <p>
            <?php
                for($i = 0; $i < count($mensajes); $i++) {
                    echo '<h2>'.htmlspecialchars($mensajes[$i]['NomTMensaje']).'</h2>';

                    $raw = $mensajes[$i]['FRegistro'];              // ej. "2025-11-09 14:30:00"
                    if (!empty($raw)) {
                        $dt = new DateTime($raw);
                        $iso = $dt->format('c');                   // formato ISO 8601 con offset: 2025-11-09T14:30:00+01:00
                        $visible = $dt->format('d-m-Y');           // formato de visualización: 09-11-2025
                    } else {
                        $iso = '';
                        $visible = 'Fecha no disponible';
                    }

                    $tipo_precio = '';

                    echo '<p class="fecha">Fecha publicación: <time datetime="'.htmlspecialchars($iso).'">'.htmlspecialchars($visible).'</time></p>';
                    echo '<p>'.htmlspecialchars($mensajes[$i]['Texto']).'</p>';
                }
            ?>
    </p>

<?php
    include 'includes/footer.php';
?>