<?php
    $title = "Login";
    $css = "css/login.css";
    $acceder = "Acceder";
    include 'includes/header.php';
?>

    
        <h1>Inicio de sesión</h1>
        <section>
            <form id="formularioLogin" onsubmit="misubmit( event );">
                <label for="labelEmail">Correo electrónico: </label>
                <input onfocus="restaurarEstilo(this.id);" class="input_select" type="text" id="email">
    
                <label for="labelPassword">Contraseña: </label>
                <input onfocus="restaurarEstilo(this.id);" class="input_select" type="password" id="password">
                <input class="boton" id="confirmar" type="submit" value="Confirmar">
    
            </form>
        </section>
        <nav id="simulacion">
                <a href="mi_perfil.php">Simular que has iniciado sesión</a>
        </nav>


<?php
    include 'includes/footer.php';
?>