<?php
$db = mysqli_connect('localhost:3306', 'root', '');
$db->select_db('pisosbd');

// Configuración alternativa para servidor en producción
// $db = mysqli_connect('localhost', 'u360956521_admin', 'x3uPYWPW:');
// $db->select_db('u360956521_pisosBD');
?>
