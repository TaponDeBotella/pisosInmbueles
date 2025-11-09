<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Mis mensajes";
    $acceder = "Mi perfil";
    $css="css/mis_mensajes.css";
    include 'includes/header.php'; 
?>
        <h1>Mis mensajes</h1>
        <section>
            <h2>ENVIADOS</h2>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario receptor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article><article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario receptor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario receptor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario receptor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario receptor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
        </section>
          
        <section>
            <h2>RECIBIDOS</h2>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario emisor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario emisor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario emisor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario emisor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
            <article class="todo_el_mensaje">
                <h3>Tipo de mensaje</h3>
                <article class="user_time">
                    <p class="nombre"><strong>Usuario emisor</strong></p><p class="fecha"><time datetime="2025-09-27 20:00">27-09-2025 20:00</time></p>
                </article>
                <p class="cont_mensaje">
                    CONTENIDO DEL MENSAJE
                </p>
            </article>
        </section>
        
<?php
    include 'includes/footer.php';
?>