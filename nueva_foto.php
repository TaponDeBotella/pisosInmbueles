    <?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Ver anuncio";
    $acceder = "Acceder";
    $css="css/ver_anuncio.css";
    include 'includes/header.php';
    include 'includes/anuncios.php';
    

    $anuncio = null; // inicializo el anuncio
    $anuncios_del_usuario = [];
    for($i=0; $i<sizeof($anuncios); $i++) { // para cada  anuncio de anuncios
        if($anuncios[$i]['duenyo'] == $_SESSION['nombre']) // si el nombre del duenyo coincide con el del usuario de la sesion
            $anuncios_del_usuario[] = $anuncios[$i];  // anyado ese anuncio a la lista de anuncios del usuario
    }
        
    ?>
        <script src="js/otras_funciones.js"></script>

        <h1>Añadir foto a anuncio</h1>
        

        <form method="post">   
            <section>
                <h3>¿Añadir una nueva foto?</h3>
                <label for="labelAnuncioAElegir">Elige el anuncio al que quieres añadir la foto</label>
                <select class="input_select" name="anuncio" id="anuncio">
                    <?php
                        for($i=0; $i<sizeof($anuncios_del_usuario); $i++){
                            $cadena = '<option';
                            if($anuncios_del_usuario[$i]['idAnuncio'] == $anuncio['idAnuncio']) 
                                $cadena .= ' selected';

                            $cadena .= ' value="'.htmlspecialchars($anuncios_del_usuario[$i]['idAnuncio']).'">'.htmlspecialchars($anuncios_del_usuario[$i]['titulo']).'</option>';

                            echo $cadena;
                        }
                    ?>
                </select>
                
                <label for="labelTitulo">Título de la foto</label>
                <input class="input_select" required type="text" id="titulo" name="titulo">
                
                <label for="labelTextoAlternativo">Texto alternativo</label>
                <textarea class="input_select" required minlength="10" name="textoAlternativo" id="textoAlternativo"></textarea>

                <label for="labelFoto">Foto: </label>
                <label for="foto" class="boton" id="examinar" name="examinar">Examinar </label>
                <!-- <input type="file" accept="image/*" required> -->
                <input required id="foto" type="file" style="display:none;">

                <input type="submit" class="boton">
            </section>
        </form>

<?php
    include 'includes/footer.php';
?>
    
    