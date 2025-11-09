<?php
    $title="Respuesta registro";
    $acceder = "Mi perfil";
    $css="css/respuesta_registro.css";
   
    require_once 'includes/funciones.php';

    

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
        $_SESSION['registro'] = [
            'nombre' => htmlspecialchars($nombre),
            'pass1' => htmlspecialchars($pass1),
            'pass2' => htmlspecialchars($pass2),
            'email' => htmlspecialchars($email),
            'sex' => htmlspecialchars($sex),
            'nacimiento' => htmlspecialchars($nacimiento),
            'ciudad' => htmlspecialchars($ciudad),
            'pais' => htmlspecialchars($pais)
        ];

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


            $_SESSION['errores'] = $errores;
            header('Location: registro.php');
            exit; 

        }else{

            if($pass1 != $pass2) {
                $_SESSION['errores'] = ['passNoCoinciden'];
                header('Location: registro.php');
                exit; 
            }

        }
    }

 include 'includes/header.php';
?>
    <section id="respuesta">
        <h4>Nombre: <?php echo $_SESSION['registro']['nombre'] ?></h4>
        <h4>Contraseña: <?php echo $_SESSION['registro']['pass1'] ?></h4>
        <h4>Repetir contraseña: <?php echo $_SESSION['registro']['pass2'] ?></h4>
        <h4>Email: <?php echo $_SESSION['registro']['email'] ?></h4>
        <h4>Sexo: <?php echo $_SESSION['registro']['sex'] ?></h4>
        <h4>Fecha de nacimiento: <?php echo $_SESSION['registro']['nacimiento'] ?></h4>
        <h4>Ciudad de residencia: <?php echo $_SESSION['registro']['ciudad'] ?></h4>
        <h4>País de residencia: <?php echo $paises_nombre_bien_puesto[array_search($_SESSION['registro']['pais'], $paises_value)];?> </h4> <!-- esto se hace asi porque si no, habria problemas con reino unido (espacio entre medias) y españa (letra ñ) -->
    </section>

<?php
    if(isset($_SESSION['registro'])) // elimino la informacion de registro una vez ha sido utilizada (registro correcto)
        unset($_SESSION['registro']);

    if(isset($_SESSION['errores'])) // elimino la informacion de errores una vez ha sido utilizada (registro correcto)
        unset($_SESSION['errores']);

    include 'includes/footer.php';
?>