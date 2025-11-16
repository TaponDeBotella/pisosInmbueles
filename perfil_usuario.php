<?php
    session_start();
    
    $title = "Perfil Usuario";
    $acceder = "Acceder";
    $css = "css/perfil_usuario.css";
    include 'includes/header.php';
    
    $usuario = null;
    $anuncios_usuario = [];
    $idUsuario = null;
    
    // se comprueba que se haya pasado un id de usuario por GET
    if (isset($_GET['idUsuario'])) {
        $idUsuario = intval($_GET['idUsuario']);
    }
    
    if ($idUsuario) {
        // se sacan los datos del usuario
        $resultado = $db->query("SELECT IdUsuario, NomUsuario, Foto, FRegistro FROM Usuarios WHERE IdUsuario = " . $idUsuario);
        if (!$resultado) { // si no hay se lanza error
            die('Error: ' . $db->error);
        }
        
        $usuario = $resultado->fetch_array(MYSQLI_ASSOC); // si si que hay se meten los datos del usuario en una variable
        
        // si no existe el usuario se muestra un mensaje
        if (!$usuario) {
            echo '<h1>Usuario no encontrado</h1>';
        } else {
            // se sacan todos los anuncios del usuario
            $resultado = $db->query("SELECT IdAnuncio, Titulo, FPrincipal, Alternativo, Precio, NomTAnuncio, Ciudad 
                                         FROM Anuncios a 
                                         JOIN TiposAnuncios ta ON a.TAnuncio = ta.IdTAnuncio 
                                         WHERE a.Usuario = " . $idUsuario . " 
                                         ORDER BY a.FRegistro DESC");
            
            if (!$resultado) {
                die('Error: ' . $db->error);
            }
            
            while ($fila = $resultado->fetch_array(MYSQLI_ASSOC)) {
                $anuncios_usuario[] = $fila;
            }
        }
    }
?>

    <h1>Perfil de Usuario</h1>
    
    <?php if ($usuario): ?>
        <section id="sectionPerfilUsuario">
            <section class="perfilUsuario">
                <figure class="fotoUsuario">
                    <?php if (!empty($usuario['Foto'])): ?> <!-- si el usuario tiene foto se muestra -->
                        <img src="img/<?php echo htmlspecialchars($usuario['Foto']); ?>" alt="Foto de <?php echo htmlspecialchars($usuario['NomUsuario']); ?>">
                    <?php endif; ?>
                </figure>
                
                <section class="datosUsuario"> <!-- aqui se muestrantodos los datos del usuario (he puesto solo el nombre y la fecha como en el ejemplo del profe)-->
                    <h2><?php echo htmlspecialchars($usuario['NomUsuario']); ?></h2>
                    <p class="fechaIncorporacion">Miembro desde: <?php echo date('d-m-Y', strtotime($usuario['FRegistro'])); ?></p>
                </section>
            </section>
        </section>
        
        <h2>Anuncios del usuario</h2>
        
        <?php if (!empty($anuncios_usuario)): ?> <!-- ahora se muestran los anuncios del usuario si tiene como en el index pero con menos datos-->
            <section id="sectionAnunciosUsuario">
                <ul id="ul_anunciosUsuario">
                    <?php foreach ($anuncios_usuario as $anuncio): ?>
                        <li>
                            <article class="anuncioSimplificado">
                                <a href="ver_anuncio.php?idAnuncio=<?php echo htmlspecialchars($anuncio['IdAnuncio']); ?>">
                                    <img class="imagen_anuncio" src="img/<?php echo htmlspecialchars($anuncio['FPrincipal']); ?>" alt="<?php echo htmlspecialchars($anuncio['Alternativo']); ?>">
                                </a>
                                
                                <header class="info_anuncio">
                                    <a href="ver_anuncio.php?idAnuncio=<?php echo htmlspecialchars($anuncio['IdAnuncio']); ?>" class="a_titulo">
                                        <h3><?php echo htmlspecialchars($anuncio['Titulo']); ?></h3>
                                    </a>
                                    
                                    <p class="tipo_anuncio"><?php echo htmlspecialchars($anuncio['NomTAnuncio']); ?></p>
                                    
                                    <p class="precio">
                                        <?php 
                                            echo htmlspecialchars(round($anuncio['Precio'], 2));
                                            echo ($anuncio['NomTAnuncio'] === 'Venta') ? '€' : '€/mes';
                                        ?>
                                    </p>
                                    
                                    <p class="ciudad"><?php echo htmlspecialchars($anuncio['Ciudad']); ?></p>
                                </header>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php else: ?>
            <p>Este usuario no tiene anuncios.</p> <!-- si no tiene anuncios se muestra esto -->
        <?php endif; ?>
    
    <?php endif; ?>

<?php
    include 'includes/footer.php';
?>
