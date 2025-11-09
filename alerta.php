<?php
    session_start();
    
    $title="Mi perfil";
    $acceder = "Mi perfil";
    $css="css/mi_perfil.css";
    include 'includes/header.php'; 
?>
        <p>Para poder acceder al anuncio primero debe iniciar sesión.</p> 
        <a href="log_registro.php">Acceder</a>
        
            
        <nav class="todos_enlaces">
            <a href="log_registro.php">Acceder</a>
            <a href="alerta.php">Alerta</a>
            <a href="anuncio.php">Anuncio</a>
            <a href="busqueda.php">Búsqueda</a>
            <a href="enviar_mensaje.php">Enviar mensaje</a>
            <a href="index.php">Index</a>
            <a href="login.php">Login</a>
            <a href="mi_perfil.php">Mi perfil</a>
            <a href="mis_mensajes.php">Mis mensajes</a>
            <a href="registro.php">Registro</a>
            <a href="respuesta_folleto.php">Respuesta folleto</a>
            <a href="respuesta_mensaje.php">Respuesta mensaje</a>
            <a href="solicitar_folleto.php">Solicitar folleto</a>
            <a href="accesibilidad.php">Accesibilidad</a>

        </nav>

<?php
    include 'includes/footer.php'; 
?>