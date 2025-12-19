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

        if (!isset($_FILES['foto']) || !isset($_FILES['foto']['name'])) {
            header('Location: nueva_foto.php?error=foto_requerida');
            exit;
        }
        $names = $_FILES['foto']['name'];
        $numFiles = is_array($names) ? count($names) : ($names === '' ? 0 : 1);
        if ($numFiles < 1) {
            header('Location: nueva_foto.php?error=foto_requerida');
            exit;
        }

        require_once 'includes/funciones_registro.php';
        $rutas_subidas = array();
        $titulos_subidos = array();
        $alternativos_subidos = array();

        // normalizar $_FILES['foto'] a estructura de arrays aunque el formulario envíe un solo fichero
        $files = $_FILES['foto'];
        if (!is_array($files['name'])) {
            $files = array(
                'name' => array($files['name']),
                'type' => array($files['type']),
                'tmp_name' => array($files['tmp_name']),
                'error' => array($files['error']),
                'size' => array($files['size']),
            );
        }

        $regex = "/^<<(?P<valor>[\p{Latin}\p{M}\p{N}\s\.\-\_\'\,]+)>>$/um";

        $data_titulos = [];
        $data_alternativos = [];

        $titulo_foto = explode('>>', $titulo_foto); // separo por el delimitador
        $texto_alternativo_foto = explode('>>', $texto_alternativo_foto); // separo por el delimitador

        foreach($titulo_foto as $titulo) { // saco los titulos
            if(preg_match($regex, $titulo.'>>', $valores))
                $data_titulos[] = $valores['valor'];
        }

        foreach($texto_alternativo_foto as $alternativo) { // saco los textos alternativos
            if(preg_match($regex, $alternativo.'>>', $valores))
                $data_alternativos[] = $valores['valor'];
        }


        for ($i = 0; $i < count($files['name']); $i++) {
            
            // se comprueba que se haya subido un archivo: ignorar inputs vacíos, abortar en otros errores
            if (!isset($files['error'][$i])) {
                continue;
            }
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue; // input vacío
            }
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $error_mensaje = 'Error en subida: código ' . $files['error'][$i];
                break;
            }
            
            // si no hay error se procesa la foto
            if (empty($error_mensaje)) {
                // se valida y se sube la foto
                $archivo = array(
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                );
                $fotoValidacion = validarFoto($archivo);
                if(!$fotoValidacion['valida']) { // si no es valida se manda el error
                    $error_mensaje = 'Error: La foto no es válida. ' . $fotoValidacion['mensaje'];
                } else {
                    // si es valida entonces se sube la foto
                    $directorio = 'fotosSubidas/anuncios';
                    if (!is_dir($directorio)) { // se comprubea si la caprtea existe y si no se crea
                        mkdir($directorio, 0755, true);
                    }
                    
                    $nombreArchivo = uniqid() . '_' . basename($files['name'][$i]); // se usa el uniqid para evitar nombres repetidos
                    $rutaFoto = $directorio . '/' . $nombreArchivo;
                
                    if(move_uploaded_file($files['tmp_name'][$i], $rutaFoto)) { // se mueve el archivo a la carpeta
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
                            if (count($data_titulos) > $i && $data_titulos[$i] !== '') 
                                $titulo_foto = $data_titulos[$i];
                            else
                                $titulo_foto = 'predeterminado';

                            if (count($data_alternativos) > $i && $data_alternativos[$i] !== '') 
                                $texto_alternativo_foto = $data_alternativos[$i];
                            else
                                $texto_alternativo_foto = 'predeterminado';

                            $stmt->bind_param('sssi', $titulo_foto, $rutaFoto, $texto_alternativo_foto, $idAnuncio);
                            
                            if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                                $error_mensaje = 'Error al agregar la foto: ' . $stmt->error;
    
                                if(file_exists($rutaFoto)) { // se elimina el archivo subido si falla la ejecucion
                                    unlink($rutaFoto);
                                }
                            } else { // si no hay error se indica que la foto se agrego correctamente
                                $foto_agregada = true;
                                $rutas_subidas[] = $rutaFoto;
                                $titulos_subidos[] = $titulo_foto;
                                $alternativos_subidos[] = $texto_alternativo_foto;
    
                                // Si el anuncio no tiene foto principal, usar esta como foto principal
                                $stmt_get = $db->prepare('SELECT FPrincipal FROM Anuncios WHERE IdAnuncio = ?');
                                if ($stmt_get) {
                                    $stmt_get->bind_param('i', $idAnuncio);
                                    $stmt_get->execute();
                                    $res_get = $stmt_get->get_result();
                                    if ($row = $res_get->fetch_assoc()) {
                                        if (empty($row['FPrincipal'])) {
                                            $stmt_update = $db->prepare('UPDATE Anuncios SET FPrincipal = ?, Alternativo = ? WHERE IdAnuncio = ?');
                                            if ($stmt_update) {
                                                $stmt_update->bind_param('ssi', $rutaFoto, $texto_alternativo_foto, $idAnuncio);
                                                $stmt_update->execute();
                                                $stmt_update->close();
                                            }
                                        }
                                    }
                                    $stmt_get->close();
                                }
                            }
                            
                            $stmt->close();
                        }
                    } else {
                        $error_mensaje = 'Error al subir el archivo de la foto.';
                    }

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
                echo '<h3>Fotos agregadas con éxito</h3>';
                echo '<article>';
                echo '<p>Las fotos han sido agregadas correctamente al anuncio.</p>';
                // se muestran las fotos subidas
                if (!empty($rutas_subidas)) {
                    echo '<p><strong>Fotos subidas:</strong></p>';
                    foreach ($rutas_subidas as $idx => $rf) {
                        if (!file_exists($rf)) continue;
                        $t = isset($titulos_subidos[$idx]) ? $titulos_subidos[$idx] : '';
                        $a = isset($alternativos_subidos[$idx]) ? $alternativos_subidos[$idx] : '';
                        echo '<div style="display:inline-block; margin-right:12px; text-align:left;">';
                        echo '<p style="margin:4px 0;"><strong>Título:</strong> ' . htmlspecialchars($t) . '</p>';
                        echo '<p style="margin:4px 0;"><strong>Alt:</strong> ' . htmlspecialchars($a) . '</p>';
                        echo '<img src="' . htmlspecialchars($rf) . '" alt="' . htmlspecialchars($a) . '" style="max-width: 200px; height: auto; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
                        echo '</div>';
                    }
                } elseif (isset($rutaFoto) && file_exists($rutaFoto)) {
                    echo '<p><strong>Foto subida:</strong></p>';
                    echo '<p><strong>Título:</strong> ' . htmlspecialchars($titulo_foto) . '</p>';
                    echo '<p><strong>Texto alternativo:</strong> ' . htmlspecialchars($texto_alternativo_foto) . '</p>';
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
