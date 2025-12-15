<?php
    $title="Respuesta registro";
    $acceder = "Mi perfil";
    $css="css/respuesta_registro.css";
   
    require_once 'includes/funciones.php';
    include 'includes/iniciarDB.php'; // asegura que $db esté inicializada
    include 'includes/funciones_registro.php';
    include 'includes/flash.php';
    

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
        
        // Guardar datos en la sesión (sin foto, se agregará después si se sube exitosamente)
        flash_set_data('registro', [
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
            header('Location: registro.php');
            exit; 

        }else{

            if($pass1 != $pass2) {
                flash_set_data('errores', 'passNoCoinciden');
                header('Location: registro.php');
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
            header('Location: registro.php');
            exit; 
        }
        else {
            $fechaRegistro = new DateTime();
            $fechaRegistroStr = $fechaRegistro->format('Y-m-d H:i:s');
            $stmt = $db->prepare(
                "INSERT INTO usuarios (NomUsuario, Clave, Email, Sexo, FNacimiento, Ciudad, Pais, Foto, FRegistro, Estilo) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            ); // se prepara la query para hacer el post

            // convierto pais a entero o NULL si está vacio
            if (!empty($pais) && $pais > 0) 
                $pais = (int)$pais;
            else 
                $pais = null;
            
            
            $estilo = 1;
            $passHash = password_hash($pass1, PASSWORD_DEFAULT);
            $fotoInicial = ''; // primero la foto va vacia, luego se actualiza si se sube una foto
            
            // convierto sexo de texto a numero (1=Hombre, 2=Mujer)
            if ($sex === 'Hombre') 
                $sexoInt = 1;
            else 
                $sexoInt = 2;
            
            
            // reformeteo la fecha de dia-mes-año a YYYY-MM-DD para la BD
            $fechaNacParts = explode('-', $nacimiento);
            if (count($fechaNacParts) === 3) 
                $nacimientoBD = $fechaNacParts[2] . '-' . $fechaNacParts[1] . '-' . $fechaNacParts[0];
            else 
                $nacimientoBD = $nacimiento; // fallback por si acaso
            

            if (!$stmt) { // si hay error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else { // si no entonces se le vinculan todos los parametros
                $stmt->bind_param(
                    'sssississi',
                    $nombre, 
                    $passHash, 
                    $email, 
                    $sexoInt, 
                    $nacimientoBD, 
                    $ciudad, 
                    $pais, 
                    $fotoInicial, 
                    $fechaRegistroStr, 
                    $estilo
                );

                if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                    $error_mensaje = 'Error al registrar usuario: ' . $stmt->error;
                } else { // si el registro fue exitoso, entonces procesar la foto
                    $id_anuncio_creado = $stmt->insert_id;
                    
                    // solo se tiene que subir la foto si el registro ha sido valido porque si no se duplican las fotos o se suben de mas
                    $fotoSubida = ''; // variable para guardar la ruta de la foto
                    if($_FILES['foto']['error'] == 0) { // si hay archivo subido se sigue
                        $fotoValidacion = validarFoto($_FILES['foto']); // validar la foto subida
                        if($fotoValidacion['valida']) { // si la foto es valida entonces se sube a la carpeta
                            $directorio = 'fotosSubidas/perfiles'; // el directorio donde va la foto
                            if (!is_dir($directorio)) { // si no existe ya el directorio se crea aunque si que deberia existir siempre
                                mkdir($directorio, 0755, true);
                            }

                            $fotoSubida = $directorio . '/' . uniqid() . '_' . basename($_FILES['foto']['name']);  // se le mete el uniqid para que no haya nombres duplicados
                            if(move_uploaded_file($_FILES['foto']['tmp_name'], $fotoSubida)) { // se mueve la foto subida al directorio
                                $stmtFoto = $db->prepare("UPDATE usuarios SET Foto = ? WHERE NomUsuario = ?"); // se prepara para hacerle el put a la colunmna de la foto
                                if($stmtFoto) { // si se ha preparado bien entonces se le vinculan los parametros
                                    $stmtFoto->bind_param('ss', $fotoSubida, $nombre);
                                    $stmtFoto->execute();
                                    $stmtFoto->close();

                                    $registroFlash = flash_get_data('registro'); // se actualiza la foto en los datos de flash para luego que en la respuesta se pueda mostrarr
                                    $registroFlash['foto'] = htmlspecialchars($fotoSubida); 
                                    flash_set_data('registro', $registroFlash); 
                                } else {
                                    if(file_exists($fotoSubida)) { // si no se ha podido subir por lo que sea se borra la foto subida para no dejar basura
                                        unlink($fotoSubida);
                                    }
                                }
                            }
                        }
                    } else {
                        $registroFlash = flash_get_data('registro'); // si no se ha subido ninguna foto entonces se pone la foto vacia del img en los datos de flash
                        $registroFlash['foto'] = '';
                        flash_set_data('registro', $registroFlash);
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
        $registro = flash_get_data('registro');
        if (!$registro) {
            echo '<p>No hay datos de registro disponibles.</p>';
        } else {
    ?>
    <section id="respuesta">
        <h4>Nombre: <?php echo htmlspecialchars($registro['nombre']); ?></h4>
        <h4>Contraseña: <?php echo htmlspecialchars($registro['pass1']); ?></h4>
        <h4>Repetir contraseña: <?php echo htmlspecialchars($registro['pass2']); ?></h4>
        <h4>Email: <?php echo htmlspecialchars($registro['email']); ?></h4>
        <h4>Sexo: <?php echo htmlspecialchars($registro['sex']); ?></h4>
        <h4>Fecha de nacimiento: <?php echo htmlspecialchars($registro['nacimiento']); ?></h4>
        <h4>Ciudad de residencia: <?php echo htmlspecialchars($registro['ciudad']); ?></h4>
        <h4>País de residencia: <?php echo htmlspecialchars($paises_nombre_bien_puesto[array_search($registro['pais'], $paises_value)]); ?> </h4>
        <h4>Foto de perfil:</h4>
        <?php if($registro['foto'] !== '') { ?> <!-- si no hay foto de perfil subida entonces se pone la de por defecto que ya habia antes -->
            <img src="<?php echo htmlspecialchars($registro['foto']); ?>" alt="Foto de perfil" style="max-width: 200px; height: auto;">
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