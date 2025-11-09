<?php
    // no lo hago en el header porque me da problemas porque hay que ponerlo antes del html y todo para que sea lo primero que compruebe al iniciar la pagaina
    // esto lo que hace es verificar si el usuario esta logueado para poder darle acceso a la parte privada
    function verificarSesion() {
        session_start();
        
        if(!isset($_SESSION['logueado'])) { // si no esta logueado entonces se le manda a la pagina del login
            header('Location: login.php?error=no_logueado');
            exit;
        }
    }
?>
