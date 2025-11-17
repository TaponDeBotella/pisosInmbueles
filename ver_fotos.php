<?php
    session_start();
    
    $title = "Ver fotos";
    $acceder = "Acceder";
    $css = "css/ver_fotos.css";
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
?>


    <h1>Ver fotos del anuncio</h1>
    <?php 
        $tipo_precio;
        if($anuncio['NomTAnuncio'] === 'Venta')
            $tipo_precio = '€';
        else
            $tipo_precio = '€/mes';        
    ?>
    <h2><?php echo htmlspecialchars($anuncio['Titulo']).' | '.htmlspecialchars($anuncio['Ciudad']).' | '.htmlspecialchars($anuncio['NomPais']).' | '.htmlspecialchars(round($anuncio['Precio'], 0)).htmlspecialchars($tipo_precio); ?></h2>
    
    <figure>
            <?php
                for($i = 0; $i < count($fotos); $i++) {
                    echo '<h2>'.htmlspecialchars($fotos[$i]['Titulo']).'</h2>';
                    echo '<img src="img/'.htmlspecialchars($fotos[$i]['Foto']).'" alt="'.htmlspecialchars($fotos[$i]['Alternativo']).'">';
                }
            ?>
    </figure>

<?php
    include 'includes/footer.php';
?>