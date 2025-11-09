<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Página del anuncio";
    $acceder = "Acceder";
    $css="css/anuncio.css";
    include 'includes/header.php'; 
?>
        <h1><?php echo $title; ?></h1>
        <h2>Anuncio de alquiler</h2>
        <h3>Piso</h3>
        <figure>
            <img id="carrusel" src="img/orihuela.jpg" alt="Foto piso">
            
            <figcaption>Foto de un piso</figcaption>
            
            <button class="boton">&larr;</button>
            <button class="boton">&rarr;</button>
        </figure>


        <article>
            <h3>Título</h3>
            <h4>Texto</h4>
            <time datetime="2025-09-27 20:00">27-09-2025 20:00</time>
            
            <table>
                <tr>
                    <th>Ciudad:</th>
                    <td>San Vicent del Raspeig</td>
                </tr>
                <tr>
                    <th>País:</th>
                    <td>España</td>
                </tr>
                <tr>
                    <th>Precio:</th>
                    <td>700€/mes</td>
                </tr>
                <tr>
                    <th rowspan="4">Características:</th>
                    <td>2 baños</td>
                </tr>
                <tr>
                    <td>4 habitaciones</td>
                </tr>
                <tr>
                    <td>Cocina de gas</td>
                </tr>
                <tr>
                    <td>1 salón</td>
                </tr>
            </table>
        </article>
        
        <figure>
            <img src="img/orihuela.jpg" alt="Foto piso" width="25%" height="25%">
            <img src="img/orihuela.jpg" alt="Foto piso" width="25%" height="25%">
            <img src="img/orihuela.jpg" alt="Foto piso" width="25%" height="25%">
        </figure>

        <nav id="simular">
            <a href="enviar_mensaje.php">Enviar mensaje al dueño</a>
        </nav>
<?php
    include 'includes/footer.php';
?>