<?php
    $title="Respuesta mis datos";
    $acceder = "Mi perfil";
    $css="css/respuesta_registro.css";
   
    require_once 'includes/funciones.php';
    include 'includes/funciones_registro.php';
    include 'includes/flash.php';
    require_once 'includes/iniciarDB.php';
    

    session_start();
    $paises_value = ["Alemania", "Espanya", "Francia", "Grecia", "Italia", "Polonia", "ReinoUnido", "Suecia", "Suiza", "Ucrania"];
    $paises_nombre_bien_puesto = ["Alemania", "España", "Francia", "Grecia", "Italia", "Polonia", "Reino Unido", "Suecia", "Suiza", "Ucrania"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];
        $email = $_POST['email'];
        $sex = $_POST['sex'];
        $nacimiento = $_POST['nacimiento'];
        $ciudad = $_POST['ciudad'];
        $pais = $_POST['pais'];
        
        // Guardar datos en la sesión
        flash_set_data('mis_datos', [
            'nombre' => htmlspecialchars($nombre),
            'pass1' => htmlspecialchars($pass1),
            'pass2' => htmlspecialchars($pass2),
            'email' => htmlspecialchars($email),
            'sex' => htmlspecialchars($sex),
            'nacimiento' => htmlspecialchars($nacimiento),
            'ciudad' => htmlspecialchars($ciudad),
            'pais' => htmlspecialchars($pais)
        ]);

        $cadena1 = "";
        $errores = [];

        if($nombre === null)
            $nombre = '';

        if($pass1 === null)
            $pass1 = '';

        if($pass2 === null)
            $pass2 = '';

        if($email === null)
            $email = '';

        if($sex === null)
            $sex = '';
        
        if($nacimiento === null)
            $nacimiento = '';
        /* if (empty($_POST['nombre']) || empty($_POST['pass1']) || empty($_POST['pass2'])){
            

            if(empty($_POST['nombre'])) 
                $errores[] = 'nombreVacio';
            

            if(empty($_POST['pass1'])) 
                $errores[] = "pass1Vacia";
            
            
            if(empty($_POST['pass2'])) 
                $errores[] = "pass2Vacia";
            
            for($i = 0; $i < sizeof($errores); $i++) {
                $cadena1 = $cadena1.$errores[$i]; // meto cadena_hasta_ahora + "error_especifico"
                
                if($i + 1 != sizeof($errores))
                    $cadena1 = $cadena1.'-'; // si aun no he acabao el array le concateno un guion
            }


            flash_set_data('errores', $errores);
            header('Location: mis_datos.php');
            exit; 

        }else{

            if($pass1 != $pass2) {
                flash_set_data('errores', 'passNoCoinciden');
                header('Location: mis_datos.php');
                exit; 
            }

        } */

        if(validarNombre($nombre) === null)
            $errores[] = "malNombre";

        if(validarPass($pass1) === null)
            $errores[] = "malPass1";
        
        if(validarRepeatPass($pass1, $pass2) === null)
            $errores[] = "malRepetirPass";
        
        if(validarEmail($email) === null)
            $errores[] = "malEmail";

        if(validarSexo($sex) === false)
            $errores[] = "malSexo";

        if(validarFechaNac($nacimiento) === false)
            $errores[] = "malNacimiento";
    
        if(sizeof($errores) !== 0) {
            flash_set_data('errores', $errores);
            header('Location: mis_datos.php');
            exit; 
        }
        else {
            // primero se saca la foto del usuario
            $stmt_foto_actual = $db->prepare("SELECT Foto FROM Usuarios WHERE IdUsuario = ?"); // se prepara la consulta del select para sacar la foto
            $idUsuario = $_SESSION['id_usuario']; // se saca el id del usuaro de la sesion
            $stmt_foto_actual->bind_param('i', $idUsuario);
            $stmt_foto_actual->execute();
            $resultado_foto_actual = $stmt_foto_actual->get_result(); // se guarda el resultado de la foto
            $fila_foto_actual = $resultado_foto_actual->fetch_array(MYSQLI_ASSOC); // se convierte el resultado en array asociativo
            $foto_antigua = $fila_foto_actual['Foto']; // se guarda la foto actual
            $stmt_foto_actual->close();
            
            // se pone la foto nueva igual a la antigua por defecto
            $foto_nueva = $foto_antigua;
            
            // por si se quiere eliminar la foto se comprueba si se ha marcado la casilla
            $eliminar_foto = isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] == '1';
            
            if($eliminar_foto) { // si el usuario quiere eliminar la foto
                if(!empty($foto_antigua) && file_exists($foto_antigua)) { // si la foto antigua no esta vacia y existe entonces se quita
                    unlink($foto_antigua);
                }
                $foto_nueva = ''; // poner foto vacia para que use la predeterminada
            }
            
            // ahora se hace el update de los datos
            $stmt = $db->prepare(
                "UPDATE Usuarios SET 
                     NomUsuario = ?, 
                     Clave = ?, 
                     Email = ?, 
                     Sexo = ?, 
                     FNacimiento = ?, 
                     Ciudad = ?, 
                     Pais = ?, 
                     Foto = ? 
                 WHERE IdUsuario = ?"
            ); // se prepara la query para hacer el update

            $pais = (int)$pais; // me aseguro de que es entero
            $passHash = password_hash($pass1, PASSWORD_DEFAULT);

            if (!$stmt) { // si hay error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else { // si no entonces se le vinculan todos los parametros
                $stmt->bind_param(
                    'sssissisi',
                    $nombre,
                    $passHash,
                    $email,
                    $sex,
                    $nacimiento,
                    $ciudad,
                    $pais,
                    $foto_nueva,
                    $idUsuario
                );

                if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                    $error_mensaje = 'Error al actualizar usuario: ' . $stmt->error;
                } else {
                    // actualizacion correcta
                    $filas = $stmt->affected_rows;
                    
                    // ahora se procesa la subida de una nueva foto si se ha seleccionado
                    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                        // se valida la foto primero
                        $fotoValidacion = validarFoto($_FILES['foto']);
                        if($fotoValidacion['valida']) { // si la foto es valida entonces se sube
                            $directorio = 'fotosSubidas/perfiles';
                            if (!is_dir($directorio)) { // primero se comprueba si el directorio existe para crearlo si no
                                mkdir($directorio, 0755, true);
                            }
                            
                            // si ya habia una foto se elimina la antigua
                            if(!empty($foto_antigua) && file_exists($foto_antigua)) {
                                unlink($foto_antigua);
                            }
                            
                            // se sube la nueva foto como en el registro
                            $fotoSubida = $directorio . '/' . uniqid() . '_' . basename($_FILES['foto']['name']);
                            if(move_uploaded_file($_FILES['foto']['tmp_name'], $fotoSubida)) {
                                $stmtFoto = $db->prepare("UPDATE Usuarios SET Foto = ? WHERE IdUsuario = ?");
                                if($stmtFoto) {
                                    $stmtFoto->bind_param('si', $fotoSubida, $idUsuario);
                                    $stmtFoto->execute();
                                    $stmtFoto->close();
                                    
                                    $foto_nueva = $fotoSubida;
                                } else { // si hay error al preparar la consulta de la foto se elimina la foto subida
                                    if(file_exists($fotoSubida)) {
                                        unlink($fotoSubida);
                                    }
                                }
                            }
                        }
                    }
                    
                    // se actualizan los datos en flash tambien para la respuesta
                    $cambio_datos = flash_get_data('mis_datos');
                    if($cambio_datos) {
                        $cambio_datos['foto'] = htmlspecialchars($foto_nueva);
                        flash_set_data('mis_datos', $cambio_datos);
                    }
                }

                $stmt->close();
            }
        }
    }


 include 'includes/header.php';
?>
    <?php
        // leer los datos guardados en flash
        $mis_datos = flash_get_data('mis_datos');
        if (!$mis_datos) {
            echo '<p>No hay datos de registro disponibles.</p>';
        } else {
    ?>
    <section id="respuesta">
        <h4>Nombre: <?php echo htmlspecialchars($mis_datos['nombre']); ?></h4>
        <h4>Contraseña: <?php echo htmlspecialchars($mis_datos['pass1']); ?></h4>
        <h4>Repetir contraseña: <?php echo htmlspecialchars($mis_datos['pass2']); ?></h4>
        <h4>Email: <?php echo htmlspecialchars($mis_datos['email']); ?></h4>
        <h4>Sexo: <?php echo htmlspecialchars($mis_datos['sex']); ?></h4>
        <h4>Fecha de nacimiento: <?php echo htmlspecialchars($mis_datos['nacimiento']); ?></h4>
        <h4>Ciudad de residencia: <?php echo htmlspecialchars($mis_datos['ciudad']); ?></h4>
        <h4>País de residencia: <?php echo htmlspecialchars($paises_nombre_bien_puesto[array_search($mis_datos['pais'], $paises_value)]); ?> </h4>
        <h4>Foto de perfil:</h4>
        <?php if(isset($mis_datos['foto']) && $mis_datos['foto'] !== '') { ?> <!-- se muestra la foto actual -->
            <img src="<?php echo htmlspecialchars($mis_datos['foto']); ?>" alt="Foto de perfil" style="max-width: 200px; height: auto;">
        <?php } else { ?>
            <img src="./img/foto1.jpg" alt="Foto de perfil por defecto" style="max-width: 200px; height: auto;">
        <?php } ?>
    </section>
    <?php
        }
    ?>

<?php
    include 'includes/footer.php';
?>