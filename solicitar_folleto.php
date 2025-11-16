<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Solicitar folleto";
    $css="css/solicitar_folleto.css";
    $acceder = "Mi perfil";
    $js="js/solicitar_folleto.js";
    
    require_once 'includes/funciones.php';
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['error'])) {
        
            $error="Rellena campos obligatorios";
     

    }
    include 'includes/header.php';
    
    // primero se saca el id del usuario logueado
    $usuarioID = $_SESSION['id_usuario']; 
    
    // y se le sacan los anuncios al usuario logueado
    $queryAnuncios = "SELECT IdAnuncio, Titulo FROM Anuncios WHERE Usuario = " . intval($usuarioID) . " ORDER BY FRegistro DESC";
    $resultadoAnuncios = $db->query($queryAnuncios);
    
    if (!$resultadoAnuncios) { // si no hay se muestra un error
        die('Error: ' . $db->error);
    }
    
    $anunciosUsuario = []; // y se crea el array donde se guardaran los anuncios
    while ($fila = $resultadoAnuncios->fetch_array(MYSQLI_ASSOC)) {
        $anunciosUsuario[] = $fila;
    }

?>
        <h1><?php echo $title ?></h1>
        <section id="solicitudFolletoPresentacion">
            <h2>Solicitud de impresión de folleto publicitario</h2>
            <p>Presenta tu solicitud de impresión de folleto publicitario.</p>
        </section>
        <!-- se crea la tabla sobre el html de la pagina, pero se oculta con css -->
        <section id="seccion-precios" class="ocultarTabla">
            <!-- se crea el boton para cerrar (ocultar otra vez) la tabla -->
            <button id="botonCerrarTarifas" class="boton" onclick="cerrarTablaPrecios()">X</button>
            <!-- ahora se crea la tabla como tal, primero los encabezados que son fijos -->
            <table id="tablaPrecios" class="tabla">
                <tbody>
                    <tr >
                        <th colspan="2"></th>
                        <th colspan="2">Blanco y Negro</th>
                        <th colspan="2">Color</th>
                    </tr>
                    <tr>
                        <th>Numero de páginas</th>
                        <th>Número de fotos</th>
                        <th>150-300dpi</th>
                        <th>450-900dpi	</th>
                        <th>150-300dpi</th>
                        <th>450-900dpi</th>
                    </tr>
            <?php
                // y ahora se hace un for para crear 15 filas
                for ($i=0; $i<15; $i++){
                    $paginas = $i + 1; // se crea la variable de paginas para poder pasarsela a la funcion que calcula el precio
                    $fotos = ($i*3)+3; // y lo mismo con las fotos
                   
                    echo '<tr>';
                        echo '<td>' . $paginas .'</td>'; // se anyade la pagina a la celda
                    
                        echo '<td>' . $fotos . '</td>'; // y la foto
                        // y ahora se llama a la funcion de calcular precio pasandole todos los datos necesarios para que en cada celda salga el precio que tiene que salir
                        echo '<td>' . number_format(calcularPrecio($paginas,$fotos,false,300), 2) . ' €</td>';
                        echo '<td>' . number_format(calcularPrecio($paginas,$fotos,false,450), 2) . ' €</td>';
                        echo '<td>' . number_format(calcularPrecio($paginas,$fotos,true,300) , 2). ' €</td>';
                        echo '<td>' . number_format(calcularPrecio($paginas,$fotos,true,450) , 2). ' €</td>';
                        
                    echo '</tr>';
                }
            ?>

        
                </tbody>
            </table>

        </section>

        <section id="solcitarFolleto">
            <section id="sectionTarifas">
                <button id="botonTarifas" class="boton" onclick="verPrecios()">Ver Posibles Precios</button>
                <button id="botonTarifasPHP" class="boton" onclick="mostrarTablaPrecios()">Ver Posibles Precios PHP</button>
                <h3>Tarifas</h3>
                <table border="1" cellspacing="0" cellpadding="8" id="tablaTarifa">
                    <tr class="celdaOscura">
                        <th>Concepto</th>
                        <th>Tarifa</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Coste procesamiento y envío</th>
                        <th>10€</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Menos de 5 páginas></th>
                        <th>2€ por página</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Entre 5 y 10 páginas</th>
                        <th>1.8€ por página</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Más de 10 páginas</th>
                        <th>1.6€</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Blanco y negro</th>
                        <th>0€</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Color</th>
                        <th>0.5€ por foto</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Resolución 300dpi o menos</th>
                        <th>0€ por foto</th>
                    </tr>
                    <tr class="celdaClara">
                        <th>Resolución mayor que 300dpi</th>
                        <th>0.2€ por foto</th>
                    </tr>
                </table>
            </section>
            <section id="sectionFormFolleto">
                <h3>Formulario de solicitud</h3>
                <p>Rellena el siguiente formulario aportando todos los detalles para confeccionar tu folleto publicitario.
                Los campos marcados con (*) son obligatotios.</p> 
                <?php
                    if (isset($error)){
                        echo "<p style='color:red'>" . $error . "</p>";
                    }
                ?>

                <form id="formFolleto" action="respuesta_folleto.php" method="post">
                    <p>
                        <i class="fa-solid fa-clipboard"></i>
                        <label for="texto_adicional_folleto">Texto adicional</label>
                        <input type="text" id="texto_adicional_folleto" name="texto_adicional_folleto" maxlength="4000" class="input_select">
                    </p>  

                    <p>
                        <i class="fa-solid fa-user"></i>
                        <label for="nombre_folleto">Nombre (*)</label>
                        <input type="text" id="nombre_folleto" name="nombre_folleto" maxlength="200" class="input_select">  
                    </p>

                    <p>
                        <i class="fa-solid fa-envelope"></i>
                        <label for="email_folleto">Correo Electrónico (*)</label>
                        <input type="email" id="email_folleto" name="email_folleto" maxlength="200" class="input_select"> 
                    </p>

                    <p>
                        <i class="fa-solid fa-location-dot"></i>
                        <label for="direccion">Dirección (*)</label>
                        <input type="text" id="calle_folleto"  name="calle_folleto" placeholder="Calle" class="input_select"> 
                        <input type="number" id="numCalle_folleto" name="numCalle_folleto" min="1" placeholder="Número" class="input_select">                         
                        <input type="number" id="cp_folleto" name="cp_folleto" min="1" placeholder="CP" class="input_select">
                        <select id="localidad_folleto" name="localidad_folleto" placeholder="Localidad" class="input_select">
                            <option value="localidad 1">localidad 1</option>
                            <option value="localidad 2">localidad 2</option>
                            <option value="localidad 3">localidad 3</option>
                            <option value="localidad 4">localidad 4</option>
                        </select>
                        <select id="provincia_folleto" name="provincia_folleto" placeholder="Provincia" class="input_select">
                            <option value="Barcelona">Barcelona</option>
                            <option value="Málaga">Málaga</option>
                            <option value="Alicante">Alicante</option>
                            <option value="Murcia">Murcia</option>
                            <option value="Sevilla">Sevilla</option>
                        </select>
                    </p> 

                    <p>
                        <i class="fa-solid fa-phone"></i>
                        <label for="tel_folleto">Número de teléfono</label>
                        <input type="tel" id="tel_folleto" name="tel_folleto" class="input_select"> 
                    </p> 

                    <p>
                        <i class="fa-solid fa-brush"></i>
                        <label for="color_folleto">Color de la portada</label>
                        <input type="color" id="color_folleto" name="color_folleto" value="#000000" class="input_select">
                    </p> 

                    <p>
                        <i class="fa-solid fa-print"></i>
                        <label for="numCopias_folleto">Número páginas</label>
                        <input type="number" id="numCopias_folleto" name="numCopias_folleto" min="1" max="99" value="1" class="input_select">  
                    </p>

                    <p>
                        <i class="fa-solid fa-eye"></i>
                        <label for="resolucion_folleto">Resolución de impresión</label>
                        <input type="number" id="resolucion_folleto" name="resolucion_folleto" min="150" max="900" step="150" value="150" class="input_select">  
                    </p>

                    <p>
                        <i class="fa-solid fa-file"></i>
                        <label for="anuncio_folleto">Anuncio del usuario (*)</label>
                        <select id="anuncio_folleto" name="anuncio_folleto" class="input_select">
                            <option value="">-- Seleccionar anuncio --</option>
                            <?php foreach ($anunciosUsuario as $anuncio): ?> <!-- se recorre el array con los anuncios y se muestran en el select-->
                                <option value="<?php echo $anuncio['IdAnuncio']; ?>">
                                    <?php echo htmlspecialchars($anuncio['Titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p> 

                    <p>
                        <i class="fa-solid fa-calendar"></i>
                        <label for="fecha_folleto">Fecha de recepción</label>
                        <input type="date" id="fecha_folleto" name="fecha_folleto" class="input_select"> 
                    </p> 

                    <p>
                        <i class="fa-solid fa-palette"></i>
                        <label>¿Impresión a color?</label>
                        <label for="bn_folleto">Blanco y negro
                            <input type="radio" id="bn_folleto" name="impresion_color" value="no" class="input_select">
                        </label>
                        <label for="color_folleto">A todo color
                            <input type="radio" id="color_folleto" name="impresion_color" value="si" class="input_select">
                        </label>  
                    </p>

                    <p>
                        <i class="fa-solid fa-barcode"></i>
                        <label for="precio">¿Impresión del precio?</label>
                        <label for="siPrecio_folleto">Si</label>
                        <input type="radio" id="siPrecio_folleto" name="impresion_precio" value="si" class="input_select">
                        <label for="noPrecio_folleto">No</label> 
                        <input type="radio" id="noPrecio_folleto" name="impresion_precio" value="no" class="input_select">
                    </p>

                    <p>
                        <input type="submit" value="Confirmar" class="boton">
                        <input type="reset" value="Reset" class="boton">
                    </p>
                </form>
            </section>
        </section>

        


<?php
    include 'includes/footer.php';
?>