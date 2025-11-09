<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // se comprueba si ha habido un post
        $texto_mensaje = isset($_POST['texto_mensaje']) ? trim($_POST['texto_mensaje']) : ''; // se guarda el mensaje
        $tipo_mensaje = isset($_POST['msg_type']) ? $_POST['msg_type'] : ''; // y el tipo de mensaje
        
        // si el mensaje esta vacio se manda el error al enviar mensaje para que lo muestre
        if (empty($texto_mensaje)) {
            header('Location: enviar_mensaje.php?error=texto_vacio');
            exit;
        }
    }
    
    $title="Respuesta mensaje";
    $acceder = "Mi perfil";
    $css="css/respuesta_mensaje.css";
    include 'includes/header.php';
?>
        <h1>Respuesta mensaje</h1>
        <h3>Mensaje enviado con éxito</h3>
        <article>
            <p>Tipo mensaje: 
                <?php 
                    // se muestran todos los datos dependiendo del tipo de mensaje
                    if ($tipo_mensaje === 'mas_info') { 
                        echo 'Más información';
                    } elseif ($tipo_mensaje === 'cita') {
                        echo 'Solicitar una cita';
                    } elseif ($tipo_mensaje === 'oferta') {
                        echo 'Comunicar una oferta';
                    }
                ?>
            </p>
            <p>Texto: <?php echo htmlspecialchars($texto_mensaje); ?></p>
        </article>

<?php
    include 'includes/footer.php';
?>