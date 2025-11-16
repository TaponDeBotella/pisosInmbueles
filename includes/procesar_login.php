<?php
session_start();

// primero se sacan los datos del formulario de inicio sesion ignorando las tabulaciones y espacios
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$recordarme = isset($_POST['recordarme']) && $_POST['recordarme'] === 'on';

// se comprueba que no estan vacios y que no solo contienen espacios y tabulaciones
if(empty($email) && empty($password)) {
    // si los dos estan vacios se manda el error de ambos vacios para que lo indique en los dos inputs
    header('Location: ../login.php?error=ambos_vacios');
    exit;
} else if(empty($email)) {
    // lo mismo pero solo con el email vacio
    header('Location: ../login.php?error=email_vacio');
    exit;
} else if(empty($password)) {
    // lo mismo pero con la contrasenya vacia
    header('Location: ../login.php?error=password_vacio');
    exit;
}

// se conecta a la base de datos
require_once 'iniciarDB.php';

// buscar el usuario en la base de datos
$query = "SELECT IdUsuario, NomUsuario, Email, Clave, Estilo FROM Usuarios WHERE Email = '" . $email . "' AND Clave = '" . $password . "'";
$resultado = $db->query($query); // se hace la query

if (!$resultado) { // si no encuentra se manda error
    die('Error: ' . $db->error);
}

// si no se encuentra se manda el error de credenciales incorrectas
if($resultado->num_rows === 0) {
    header('Location: ../login.php?error=credenciales_incorrectas');
    exit;
}

// si se encuentra el usuario se sacan sus datos
$usuario_encontrado = $resultado->fetch_array(MYSQLI_ASSOC);

// si si que existe se inicia la sesion con el SESSION
$_SESSION['id_usuario'] = $usuario_encontrado['IdUsuario'];
$_SESSION['usuario'] = $usuario_encontrado['Email'];
$_SESSION['nombre'] = $usuario_encontrado['NomUsuario'];
$_SESSION['estilo'] = $usuario_encontrado['Estilo'];
$_SESSION['logueado'] = true;

// se guarda la fecha de la ultima visita
$duracion_cookie = time() + (90 * 24 * 60 * 60);
setcookie('recordarme_ultima_visita', date('d/m/Y H:i'), $duracion_cookie, '/', '', false, true);
$_SESSION['es_recordado'] = true;

// ahora se hace lo de las cookies si el usurio lo ha marcado recordarme
if($recordarme) {
    // se guarda el email y contrasenya
    setcookie('recordarme_email', $email, $duracion_cookie, '/', '', false, true);
    setcookie('recordarme_password', $password, $duracion_cookie, '/', '', false, true); // aqui hay que hacer una especie de hash
}

// se redirige al perfil de usuario
header('Location: ../mi_perfil.php');
exit;
?>
