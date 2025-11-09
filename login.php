<?php
    session_start();
    
    // se pone esto lo primero porque si no me da error, tiene que hacerse lo primero de todo lo de comprobar si el usuario etiene una sesion ya o no
    if(isset($_SESSION['logueado']) && $_SESSION['logueado'] === true) {
        header('Location: mi_perfil.php');
        exit;
    }
    
    $title = "Login";
    $css = "css/login.css";
    $css_errores = "css/errores.css";
    $acceder = "Acceder";
    include 'includes/header.php';
    
    // esto es para los errores, asi se pueden mostrar mensajes de error en el formulario al lado del campo que ha fallado
    $email_error = false;
    $password_error = false;
    $credenciales_error = false;
    $no_logueado_error = false;
    
    if(isset($_GET['error'])) { // se comprueba que clase de error hay
        if($_GET['error'] == 'ambos_vacios') { // si los dos estan vacios
            $email_error = true;
            $password_error = true;
        } else if($_GET['error'] == 'email_vacio') { // si solo el email esta vacio
            $email_error = true;
        } else if($_GET['error'] == 'password_vacio') { // si solo la contrasenya esta vacia
            $password_error = true;
        } else if($_GET['error'] == 'credenciales_incorrectas') { // si son credenciales incorrectas es que o una esta vacia o se ha introducido un usuario que no existe o que se ha equivocado poniendo el email o contrasenya
            $credenciales_error = true;
        } else if($_GET['error'] == 'no_logueado') { // si intenta acceder a una pagina protegida sin estar logueado se manda error
            $no_logueado_error = true;
        }
    }
?>

    
        <h1>Inicio de sesión</h1>
        <section>
            <?php 
                if($no_logueado_error) // error cuando intenta acceder a paginas protegida sin estar logueado
                    echo '<p class="mensaje-error-grande"><strong>Debes iniciar sesión para acceder a esa página</strong></p>'; 
            ?>
            <form id="formularioLogin" onsubmit="misubmit( event );" method="POST" action="includes/procesar_login.php">
                <label for="labelEmail">Correo electrónico: </label>
                <input onfocus="restaurarEstilo(this.id);" class="input_select" type="text" id="email" name="email">
                <!-- si hay error se muestra al lado del input -->
                <?php 
                    if($email_error) // error del email vacio
                        echo '<span class="mensaje-error">Campo requerido</span>'; 
                ?>
                <?php 
                    if($credenciales_error) // error de credenciales incorrectas
                        echo '<span class="mensaje-error">Email o contraseña incorrectos</span>'; 
                ?>
    
                <label for="labelPassword">Contraseña: </label>
                <input onfocus="restaurarEstilo(this.id);" class="input_select" type="password" id="password" name="password">
                <?php 
                    if($password_error) // error de contrasenya vacia
                        echo '<span class="mensaje-error">Campo requerido</span>'; 
                ?>
                <?php 
                    if($credenciales_error && !$password_error) 
                        echo '<span class="mensaje-error"></span>'; 
                ?>

                <!-- el checkbox para saber si guardar las cookies o no -->
                <label for="recordarme" id="checkboxLogin">
                    <input type="checkbox" id="recordarme" name="recordarme" value="on">
                    Recordarme en este equipo
                </label>
                
                <input class="boton" id="confirmar" type="submit" value="Confirmar">
    
            </form>
        </section>

<?php
    include 'includes/footer.php';
?>