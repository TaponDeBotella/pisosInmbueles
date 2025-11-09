<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se comprueba si el usuario esta logueado
    
    $title="Enviar mensaje";
    $acceder = "Acceder";
    $css="css/enviar_mensaje.css";
    include 'includes/header.php'; 
?>

        <h1>Enviar mensaje</h1>
        <form>
            <label id="texto_mensaje">Texto del mensaje</label>
            <textarea>
                
            </textarea>
            <label id="tipo_mensaje">Tipo de mensaje</label>
            <select class="boton" id="msg_type">
                <option value="mas_info">Más información</option>
                <option value="cita">Solicitar una cita</option>
                <option value="oferta">Comunicar una oferta</option>
            </select>
            <input class="boton" type="submit" value="Enviar mensaje">
            <nav>
                <a href="respuesta_mensaje.php">Simular que se envía el mensaje</a>
            </nav>
            
        </form>

<?php
    include 'includes/footer.php';
?>