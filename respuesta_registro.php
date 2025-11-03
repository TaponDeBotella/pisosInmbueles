<?php
    $title="Respuesta registro";
    $acceder = "Mi perfil";
    $css="css/respuesta_registro.css";
   
    require_once 'includes/funciones.php';

    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nombre= $_POST['nombre'];
        $pass1= $_POST['pass1'];
        $pass2= $_POST['pass2'];
        $email= $_POST['email'];
        $sex= $_POST['sex'];
        $nacimiento= $_POST['nacimiento'];
        $ciudad= $_POST['ciudad'];
        $pais= $_POST['pais'];
        $cadena2 = "";
        $cadena2 = $cadena2."?nombre=_".htmlspecialchars($nombre)."_";
        $cadena2 = $cadena2."?pass1=_".htmlspecialchars($pass1)."_";
        $cadena2 = $cadena2."?pass2=_".htmlspecialchars($pass2)."_";
        $cadena2 = $cadena2."?email=_".htmlspecialchars($email)."_";
        $cadena2 = $cadena2."?sex=_".htmlspecialchars($sex)."_";
        $cadena2 = $cadena2."?nacimiento=_".htmlspecialchars($nacimiento)."_";
        $cadena2 = $cadena2."?ciudad=_".htmlspecialchars($ciudad)."_";
        $cadena2 = $cadena2."?pais=_".htmlspecialchars($pais)."_";

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


            $localizacion = $_SERVER['HTTP_REFERER']; // recojo la ruta anterior

            $separadorLocalizacion = explode('registro.php', $localizacion); // separo todo lo anterior a respuesta_registro.php para limpiar la ruta y que no se acumulen errores

            $localizacionLimpia = $separadorLocalizacion[0].'registro.php'; // anyado respuesta_registro.php para que la ruta tenga sentido

            header('Location: '.htmlspecialchars($localizacionLimpia).htmlspecialchars($cadena2)."?error=".htmlspecialchars($cadena1)); // modifico la ruta con la que se pasara a la respuesta con los errores
            exit; 

        }else{

            if($pass1 != $pass2) {
                $cadena = "passNoCoinciden";
                $localizacion = $_SERVER['HTTP_REFERER']; // recojo la ruta anterior

                $separadorLocalizacion = explode('registro.php', $localizacion); // separo todo lo anterior a respuesta_registro.php para limpiar la ruta y que no se acumulen errores

                $localizacionLimpia = $separadorLocalizacion[0].'registro.php'; // anyado respuesta_registro.php para que la ruta tenga sentido

                header('Location: '.htmlspecialchars($localizacionLimpia).htmlspecialchars($cadena2)."?error=".htmlspecialchars($cadena)); // modifico la ruta con la que se pasara a la respuesta con los errores
                exit; 
            }

        }
    }

 include 'includes/header.php';
?>
    <section id="respuesta">
        <h4>Nombre: <?php echo htmlspecialchars($nombre) ?></h4>
        <h4>Contraseña: <?php echo htmlspecialchars($pass1) ?></h4>
        <h4>Repetir contraseña: <?php echo htmlspecialchars($pass2) ?></h4>
        <h4>Email: <?php echo htmlspecialchars($email) ?></h4>
        <h4>Sexo: <?php echo htmlspecialchars($sex) ?></h4>
        <h4>Fecha de nacimiento: <?php echo htmlspecialchars($nacimiento) ?></h4>
        <h4>Ciudad de residencia: <?php echo htmlspecialchars($ciudad) ?></h4>
        <h4>País de residencia: <?php echo htmlspecialchars($pais) ?></h4>
    </section>



<?php
    include 'includes/footer.php';
?>