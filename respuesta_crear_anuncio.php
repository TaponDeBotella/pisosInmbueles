<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $title="Respuesta crear anuncio";
    $acceder = "Mi perfil";
    $css="css/respuesta_borrar_anuncio.css"; // el mismo css que los demas

    $anuncio_creado = false; // para saber si se ha creado bien o no
    $error_mensaje = ''; // para mandar el mensaje de error si hay algun problema
    $id_usuario = $_SESSION['id_usuario']; // id del usuario logueado
    $id_anuncio_creado = 0; // id del anuncio que se ha creado
    $titulo_anuncio = ''; // titulo del anuncio creado

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validar campos obligatorios (solo título y texto según enunciado)
        if (empty($_POST['titulo']) || empty($_POST['texto'])){
         
            header('Location: ' . $_SERVER['HTTP_REFERER']."?error");
            exit; 
        } else {
       
            $tipo_anuncio = intval($_POST['TipoAnuncio'] ?? 0); // se sacan todos los datos del formulario que hay en crear anuncio
            $tipo_anuncio = $tipo_anuncio > 0 ? $tipo_anuncio : null; // convertir 0 a null porque si no da problemas en la base de datos por ser fk
            $tipo_vivienda = intval($_POST['tipoVivienda'] ?? 0);
            $tipo_vivienda = $tipo_vivienda > 0 ? $tipo_vivienda : null; // convertir 0 a null por lo mismo
            $titulo = $_POST['titulo'];
            $texto = $_POST['texto'];
            $precio = floatval($_POST['precio'] ?? 0);
            $ciudad = $_POST['ciudad'] ?? '';
            $pais = intval($_POST['pais'] ?? 0);
            $pais = $pais > 0 ? $pais : null; // convertir 0 a null por lo mismo
            $superficie = floatval($_POST['superficie'] ?? 0);
            $n_habitaciones = intval($_POST['habitaciones'] ?? 0);
            $n_banyos = intval($_POST['banyos'] ?? 0);
            $planta = intval($_POST['planta'] ?? 0);
            $anyo = intval($_POST['fechaCreacion'] ?? date('Y'));

            $stmt = $db->prepare(
                "INSERT INTO Anuncios (TAnuncio, TVivienda, Titulo, Precio, Texto, Ciudad, Pais, Superficie, NHabitaciones, NBanyos, Planta, Anyo, Usuario) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            ); // se prepara la query para hacer el post

            if (!$stmt) { // si hay error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else { // si no entonces se le vinculan todos los parametros
                $stmt->bind_param(
                    'iissdsdiiiiii',
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
                    $id_usuario
                );

                if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                    $error_mensaje = 'Error al crear el anuncio: ' . $stmt->error;
                } else { // si no entonces se marca como creado
                    $anuncio_creado = true;
                    $id_anuncio_creado = $stmt->insert_id;
                    $titulo_anuncio = $titulo;
                }

                $stmt->close();
            }
        }
    }

    include 'includes/header.php';
?>

        <h1>Respuesta creación de anuncio</h1>

        <?php
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section class="error">';
                echo '<p>' . htmlspecialchars($error_mensaje) . '</p>';
                echo '</section>';
            } elseif ($anuncio_creado) { // si no entonces se muestra la respuesta de que el usuario ha creado el anuncio bien
                echo '<section class="exito">';
                echo '<p>¡Anuncio creado correctamente!</p>';
                echo '<p><strong>Título:</strong> ' . htmlspecialchars($titulo_anuncio) . '</p>';
                echo '<p>Ahora puedes añadir tu primera fotografía al anuncio.</p>';
                echo '<nav>';
                echo '<a href="nueva_foto.php" class="boton">Añadir foto</a>';
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
