<?php // para eliminar el inicio de sesion y todo lo que tenga que ver
session_start();

// se borra todo lo de session
$_SESSION = array();

// se borra las cookies tambien
if(isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 1000, '/');
}

if(isset($_COOKIE['recordarme_email'])) {
    setcookie('recordarme_email', '', time() - 1000, '/'); // se borra y se le pone el tiempo -1 segundo
}
if(isset($_COOKIE['recordarme_password'])) {
    setcookie('recordarme_password', '', time() - 1000, '/');
}
if(isset($_COOKIE['recordarme_ultima_visita'])) {
    setcookie('recordarme_ultima_visita', '', time() - 1000, '/');
}

// se destruye la sesin
session_destroy();

// y se le manda al usuario al login.pho para que inicie sesion otra vez
header('Location: ../login.php');
exit;
?>
