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
            $idUsuario = $_SESSION['id_usuario'];
            // si no se proporciona foto en el formulario, usar cadena vacía
            $foto = isset($_POST['foto']) ? $_POST['foto'] : '';

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
                    $foto,
                    $idUsuario
                );

                if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                    $error_mensaje = 'Error al actualizar usuario: ' . $stmt->error;
                } else {
                    // actualización correcta
                    $filas = $stmt->affected_rows;
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
    </section>
    <?php
        }
    ?>

<?php
    include 'includes/footer.php';
?>