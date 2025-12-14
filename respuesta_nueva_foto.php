<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    include 'includes/iniciarDB.php';
    
    $foto_agregada = false; // por si hay que mandar algun error o algo   
    $error_mensaje = ''; // para guardar el tipo de error
    $titulo_foto = ''; // para mostrar el titulo de la foto
    $texto_alternativo_foto = ''; // para mostrar el texto alternativo
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // se comprueba si ha habido un post y se le sacan todos los datos
        $titulo_foto = isset($_POST['titulo']) ? trim($_POST['titulo']) : ''; // se guarda el titulo
        $texto_alternativo_foto = isset($_POST['textoAlternativo']) ? trim($_POST['textoAlternativo']) : ''; // se guarda el texto alternativo
        $idAnuncio = isset($_POST['anuncio']) ? (int)$_POST['anuncio'] : 0; // id del anuncio
        
        // si el titulo esta vacio se manda el error
        if (empty($titulo_foto)) {
            header('Location: nueva_foto.php?error=titulo_vacio');
            exit;
        }
        
        // si el texto alternativo esta vacio se manda el error
        if (empty($texto_alternativo_foto)) {
            header('Location: nueva_foto.php?error=texto_alternativo_vacio');
            exit;
        }
        
        // si el texto alternativo es menor a 10 caracteres se manda el error
        if (strlen($texto_alternativo_foto) < 10) {
            header('Location: nueva_foto.php?error=texto_alternativo_corto');
            exit;
        }
        
        // si el texto alternativo empieza por una palabra redundante se manda el error
        $textoAlternativoLower = strtolower(trim($texto_alternativo_foto));
        $palabras_redundantes = array('foto', 'imagen', 'picture', 'image', 'photo');
        foreach ($palabras_redundantes as $palabra) {
            if (strpos($textoAlternativoLower, $palabra) === 0) {
                header('Location: nueva_foto.php?error=texto_alternativo_redundante');
                exit;
            }
        }
        
        // si el id del anuncio no existe se manda el error
        if ($idAnuncio <= 0) {
            header('Location: nueva_foto.php?error=anuncio_vacio');
            exit;
        }
        
        // se comprueba que el anuncio pertenece al usuario logueado
        $stmt_verificar = $db->prepare('SELECT u.NomUsuario FROM Anuncios a JOIN Usuarios u ON a.Usuario = u.IdUsuario WHERE a.IdAnuncio = ?');
        if ($stmt_verificar) {
            $stmt_verificar->bind_param('i', $idAnuncio);
            $stmt_verificar->execute();
            $resultado_verificar = $stmt_verificar->get_result();
            
            if ($fila_verificar = $resultado_verificar->fetch_assoc()) {
                if ($fila_verificar['NomUsuario'] !== $_SESSION['nombre']) {
                    $error_mensaje = 'Error: El anuncio no te pertenece';
                }
            } else {
                $error_mensaje = 'Error: El anuncio no existe';
            }
            $stmt_verificar->close();
        }
        
        // se comprueba que se haya subido un archivo
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== 0) {
            header('Location: nueva_foto.php?error=foto_requerida');
            exit;
        }
        
        // si no hay error se procesa la foto
        if (empty($error_mensaje)) {
            // se valida y se sube la foto
            require_once 'includes/funciones_registro.php';
            $fotoValidacion = validarFoto($_FILES['foto']);
            
            if(!$fotoValidacion['valida']) { // si no es valida se manda el error
                $error_mensaje = 'Error: La foto no es válida. ' . $fotoValidacion['mensaje'];
            } else {
                // si es valida entonces se sube la foto
                $directorio = 'fotosSubidas/anuncios';
                if (!is_dir($directorio)) { // se comprubea si la caprtea existe y si no se crea
                    mkdir($directorio, 0755, true);
                }
                
                $nombreArchivo = uniqid() . '_' . basename($_FILES['foto']['name']); // se usa el uniqid para evitar nombres repetidos
                $rutaFoto = $directorio . '/' . $nombreArchivo;
                
                if(move_uploaded_file($_FILES['foto']['tmp_name'], $rutaFoto)) { // se mueve el archivo a la carpeta
                    // y se mete en la base de datos tambien
                    $stmt = $db->prepare(
                        "INSERT INTO Fotos (Titulo, Foto, Alternativo, Anuncio) 
                         VALUES (?, ?, ?, ?)"
                    );
                    
                    if (!$stmt) { // si hay error al preparar la consulta se manda el error
                        $error_mensaje = 'Error en la preparación de la sentencia: ' . $db->error;
                        if(file_exists($rutaFoto)) { // se elimina el archivo subido si falla la preparacion
                            unlink($rutaFoto);
                        }
                    } else { // si no hay error se ejecuta la consulta
                        $stmt->bind_param('sssi', $titulo_foto, $rutaFoto, $texto_alternativo_foto, $idAnuncio);
                        
                        if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                            $error_mensaje = 'Error al agregar la foto: ' . $stmt->error;

                            if(file_exists($rutaFoto)) { // se elimina el archivo subido si falla la ejecucion
                                unlink($rutaFoto);
                            }
                        } else { // si no hay error se indica que la foto se agrego correctamente
                            $foto_agregada = true;
                        }
                        
                        $stmt->close();
                    }
                } else {
                    $error_mensaje = 'Error al subir el archivo de la foto.';
                }
            }
        }
    }
    
    $title="Respuesta nueva foto";
    $acceder = "Mi perfil";
    $css="css/respuesta_mensaje.css";
    include 'includes/header.php';
?>
        <h1>Respuesta nueva foto</h1>
        <?php 
            if (!empty($error_mensaje)) { // si hay algun error se muestra
                echo '<section class="error">' . htmlspecialchars($error_mensaje) . '</section>';
            } elseif ($foto_agregada) { // si no entonces se muestra la respuesta con todo lo relacionado con la foto anyadida
                echo '<h3>Foto agregada con éxito</h3>';
                echo '<article>';
                echo '<p>La foto ha sido agregada correctamente al anuncio.</p>';
                echo '<p><strong>Título:</strong> ' . htmlspecialchars($titulo_foto) . '</p>';
                echo '<p><strong>Texto alternativo:</strong> ' . htmlspecialchars($texto_alternativo_foto) . '</p>';
                // se muestra la foto subida
                if(isset($rutaFoto) && file_exists($rutaFoto)) {
                    echo '<p><strong>Foto subida:</strong></p>';
                    echo '<img src="' . htmlspecialchars($rutaFoto) . '" alt="' . htmlspecialchars($texto_alternativo_foto) . '" style="max-width: 400px; height: auto; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
                }
                echo '</article>';
                echo '<nav><a href="mis_anuncios.php">Ir a mis anuncios</a></nav>';
            } else { // esto es si se accede por url sin post
                echo '<p>No hay datos para mostrar.</p>';
            }
        ?>

<?php
    include 'includes/footer.php';
?>
