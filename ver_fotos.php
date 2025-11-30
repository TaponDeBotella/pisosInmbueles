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
                p.NomPais, a.Ciudad, a.Texto, ta.NomTAnuncio, a.IdAnuncio, a.Usuario, NomTVivienda
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
    
    $es_propietario = isset($_SESSION['id_usuario']) && $anuncio['Usuario'] == $_SESSION['id_usuario']; // compruebo si el usuario es el propietario del anuncio para saber si puede borrar fotos o no
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
                    echo '<article class="contenedor_foto">';
                    echo '<h2>'.htmlspecialchars($fotos[$i]['Titulo']).'</h2>';
                    echo '<img src="img/'.htmlspecialchars($fotos[$i]['Foto']).'" alt="'.htmlspecialchars($fotos[$i]['Alternativo']).'">';
                    if ($es_propietario) {
                        echo '<button class="boton" onclick="abrirModalBorrarFoto('.htmlspecialchars($fotos[$i]['IdFoto']).', \''.htmlspecialchars($fotos[$i]['Titulo']).'\')"><i class="fa-solid fa-circle-xmark"></i></button>';
                    }
                    echo '</article>';
                }
            ?>
    </figure>

    <?php if ($es_propietario): ?> <!-- si el usuario es el duenyo entonces va a poder borrar las fotos, si no no-->
        <!-- la ventana modal para que el usuario pueda confirmar lo de borrar la foto -->
        <section id="modalBorrarFoto"> <!--esto es un copia pega de la ventana modal del borrar anuncio literalmente -->
            <section id="ventanaModalFoto">
                <h2>¿Borrar esta foto?</h2>
                <p id="nombreFoto">Foto: Foto de ejemplo</p>
                <p>Esta acción no se puede deshacer.</p>
                
                <section id="modalBotonesFoto">
                    <form id="formBorrarFoto" action="respuesta_borrar_foto.php" method="POST" style="display:inline;">
                        <input type="hidden" id="idFotoInput" name="idFoto" value="">
                        <input type="hidden" id="idAnuncioInput" name="idAnuncio" value="">
                        <button class="boton" type="submit">Confirmar</button>
                    </form>
                    <button class="boton" onclick="cerrarModalBorrarFoto()">Cancelar</button>
                </section>
            </section>
        </section>

        <script>
            function abrirModalBorrarFoto(idFoto, nombreFoto) {
                document.getElementById('idFotoInput').value = idFoto;
                document.getElementById('idAnuncioInput').value = <?php echo $idAnuncio; ?>; // paso el id del anuncio tambien para poder volver a esa pagina cuando se borre la foto
                document.getElementById('nombreFoto').textContent = 'Foto: ' + nombreFoto;
                document.getElementById('modalBorrarFoto').style.display = 'flex';
            }

            function cerrarModalBorrarFoto() {
                document.getElementById('modalBorrarFoto').style.display = 'none';
            }
        </script>
    <?php endif; ?>

<?php
    include 'includes/footer.php';
?>