<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $anuncio_editado = false; // para saber si se ha editado bien o no
    $error_mensaje = ''; // para mandar el mensaje de error si hay algun problema
    $id_usuario = $_SESSION['id_usuario']; // id del usuario logueado
    $titulo_anuncio = ''; // titulo del anuncio editado
    $id_anuncio = 0; // id del anuncio
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $id_anuncio = isset($_POST['idAnuncio']) ? (int)$_POST['idAnuncio'] : 0; // se saca el id del anuncio
        
        if ($id_anuncio <= 0) { // si no se existe el id del anuncio se manda error
            $error_mensaje = 'Error: ID de anuncio inválido.';
        } else {
            // se verifica si el anuncio es del usuario logueado
            $stmt_verificar = $db->prepare('SELECT a.IdAnuncio FROM Anuncios a JOIN Usuarios u ON a.Usuario = u.IdUsuario WHERE a.IdAnuncio = ? AND u.NomUsuario = ?');
            $stmt_verificar->bind_param('is', $id_anuncio, $_SESSION['nombre']);
            $stmt_verificar->execute();
            $resultado_verificar = $stmt_verificar->get_result();
            
            if ($resultado_verificar->num_rows === 0) { // si no es su anuncio entonces no le deja editarlo
                $error_mensaje = 'Error: El anuncio no te pertenece.';
            } else {
                // se verificaque los campos obligatorios estan rellenados
                if (empty($_POST['titulo']) || empty($_POST['texto'])){
                    $error_mensaje = 'El título y el texto son obligatorios.';
                } else {
                    
                    // ahora se sacan todos los datos para editar el anuncio
                    $tipo_anuncio = intval($_POST['TipoAnuncio'] ?? 0);
                    $tipo_anuncio = $tipo_anuncio > 0 ? $tipo_anuncio : null;
                    
                    $tipo_vivienda = intval($_POST['tipoVivienda'] ?? 0);
                    $tipo_vivienda = $tipo_vivienda > 0 ? $tipo_vivienda : null;
                    
                    $titulo = $_POST['titulo'];
                    $texto = $_POST['texto'];
                    $precio = floatval($_POST['precio'] ?? 0);
                    $ciudad = $_POST['ciudad'] ?? '';
                    
                    $pais = intval($_POST['pais'] ?? 0);
                    $pais = $pais > 0 ? $pais : null;
                    
                    $superficie = floatval($_POST['superficie'] ?? 0);
                    $n_habitaciones = intval($_POST['habitaciones'] ?? 0);
                    $n_banyos = intval($_POST['banyos'] ?? 0);
                    $planta = intval($_POST['planta'] ?? 0);
                    $anyo = intval($_POST['fechaCreacion'] ?? date('Y'));
                    
                    $stmt = $db->prepare(
                        "UPDATE Anuncios SET TAnuncio = ?, TVivienda = ?, Titulo = ?, Precio = ?, Texto = ?, Ciudad = ?, Pais = ?, Superficie = ?, NHabitaciones = ?, NBanyos = ?, Planta = ?, Anyo = ? 
                         WHERE IdAnuncio = ?"
                    ); // se prepara el update para meter los datos nuevos a la base de datos en la fila del anuncio que ya existe
                    
                    if (!$stmt) { // si hay error se manda
                        $error_mensaje = 'Error en la preparación: ' . $db->error;
                    } else { // si no entonces se le vinculan todos los parametros
                        $stmt->bind_param(
                            'iisdsiidiiiii',
                            $tipo_anuncio,
                            $tipo_vivienda,
                            $titulo,
                            $precio,
                            $texto,
                            $ciudad,
                            $pais,
                            $superficie,
                            $n_habitaciones,
                            $n_banyos,
                            $planta,
                            $anyo,
                            $id_anuncio
                        );
                        
                        if (!$stmt->execute()) {
                            $error_mensaje = 'Error al editar el anuncio: ' . $stmt->error;
                        } else {
                            $anuncio_editado = true;
                            $titulo_anuncio = $titulo;
                        }
                        
                        $stmt->close();
                    }
                }
            }
            $stmt_verificar->close();
        }
    }
    
    $title="Respuesta editar anuncio";
    $acceder = "Mi perfil";
    $css="css/respuesta_borrar_anuncio.css";
    include 'includes/header.php';
?>

        <h1>Respuesta edición de anuncio</h1>

        <?php
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section class="error">';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '</section>';
            } elseif ($anuncio_editado) { // si no entonces se muestra la respuesta de que el usuario ha editado el anuncio bien
                echo '<section class="exito">';
                echo '<p>¡Anuncio editado correctamente!</p>';
                echo '<p><strong>Título:</strong> ' . htmlspecialchars($titulo_anuncio) . '</p>';
                echo '<nav>';
                echo '<a href="mis_anuncios.php" class="boton">Ver mis anuncios</a>';
                echo '</nav>';
                echo '</section>';
            } else { // esto es si se accede por url sin post
                echo '<p>No hay datos para procesar.</p>';
            }
        ?>
   
<?php
    include 'includes/footer.php';
?>
