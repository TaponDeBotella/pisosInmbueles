<?php
    $title="Respuesta registro";
    $acceder = "Mi perfil";
    $css="css/respuesta_registro.css";
   
    require_once 'includes/funciones.php';
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

        if (empty($_POST['nombre']) || empty($_POST['pass1']) || empty($_POST['pass2'])){
            
            $cadena1 = "";
            $errores = [];

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