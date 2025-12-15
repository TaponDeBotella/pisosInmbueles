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

// buscar el usuario en la base de datos (solo por email, usando prepared statement)
$stmt = $db->prepare("SELECT IdUsuario, NomUsuario, Email, Clave, Estilo FROM Usuarios WHERE Email = ?");

if (!$stmt) {
    die('Error en la preparación: ' . $db->error);
}

$stmt->bind_param('s', $email);

if (!$stmt->execute()) {
    die('Error al ejecutar: ' . $stmt->error);
}

$resultado = $stmt->get_result();

// si no se encuentra se manda el error de credenciales incorrectas
if($resultado->num_rows === 0) {
    $stmt->close();
    header('Location: ../login.php?error=credenciales_incorrectas');
    exit;
}

// si se encuentra el usuario se sacan sus datos
$usuario_encontrado = $resultado->fetch_array(MYSQLI_ASSOC);
$stmt->close();

// verifico la contrasenya con password_verify
if (!password_verify($password, $usuario_encontrado['Clave'])) {
    header('Location: ../login.php?error=credenciales_incorrectas');
    exit;
}

// si si que existe se inicia la sesion con el SESSION
$_SESSION['id_usuario'] = $usuario_encontrado['IdUsuario'];
$_SESSION['usuario'] = $usuario_encontrado['Email'];
$_SESSION['nombre'] = $usuario_encontrado['NomUsuario'];
$_SESSION['estilo'] = $usuario_encontrado['Estilo'];
$_SESSION['logueado'] = true;

// si marco recordarme se guardan las cookies
if($recordarme) {
    $duracion_cookie = time() + (90 * 24 * 60 * 60);
    setcookie('recordarme_email', $usuario_encontrado['Email'], $duracion_cookie, '/', '', false, true);
    setcookie('recordarme_password', $usuario_encontrado['Clave'], $duracion_cookie, '/', '', false, true);
    setcookie('recordarme_ultima_visita', date('d/m/Y H:i'), $duracion_cookie, '/', '', false, true);
    $_SESSION['es_recordado'] = true;
}

// se redirige al perfil de usuario
header('Location: ../mi_perfil.php');
exit;
?>
