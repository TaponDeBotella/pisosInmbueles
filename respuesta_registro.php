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
        // el campo 'foto' puede venir por upload ($_FILES) o no venir en absoluto;
        // evitar warning si no existe y usar cadena vacía por defecto
        $foto = isset($_POST['foto']) ? $_POST['foto'] : '';
        
        
        // Guardar datos en la sesión
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

            $pais = (int)$pais; // me aseguro de que es entero
            $estilo = 1;
            $passHash = password_hash($pass1, PASSWORD_DEFAULT);

            if (!$stmt) { // si hay error se manda
                $error_mensaje = 'Error en la preparación: ' . $db->error;
            } else { // si no entonces se le vinculan todos los parametros
                $stmt->bind_param(
                    'ssssssissi',
                    $nombre, 
                    $passHash, 
                    $email, 
                    $sex, 
                    $nacimiento, 
                    $ciudad, 
                    $pais, 
                    $foto, 
                    $fechaRegistroStr, 
                    $estilo
                );

                if (!$stmt->execute()) { // si hay error al ejecutar se manda el error
                    $error_mensaje = 'Error al registrar usuario: ' . $stmt->error;
                } else { // si no entonces se marca como creado
                    $id_anuncio_creado = $stmt->insert_id;
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
    </section>
    <?php
        }
    ?>

<?php
    include 'includes/footer.php';
?>