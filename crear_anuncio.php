<?php
    require_once 'includes/proteger.php';
    verificarSesion(); // se verifica si el usuario esta logueado
    
    $title="Página de crear anuncio";
    $acceder = "Acceder";
    $css="css/crear_anuncio.css";
    include 'includes/header.php'; 
    ?>
    <script src="js/otras_funciones.js"></script>

    <form method="post">
        <section>
            <h3>Datos generales</h3>
            <label for="labelTipoAnuncio">Tipo de anuncio</label>
            <select class="input_select" name="TipoAnuncio" id="TipoAnuncio" onchange="cambiarTipoPrecio(this.value);">
                <option selected value=""></option>
                <option value="alquiler">Alquiler</option>
                <option value="venta">Venta</option>
            </select>
    
    
            <label for="labelTipoVivienda">Tipo de vivienda</label>
            <input class="input_select" type="text" name="tipoVivienda" id="tipoVivienda" placeholder="Habitación, piso, casa...">
    
            <label for="labelTitulo">Título</label>
            <input class="input_select" type="text" name="titulo" id="titulo">
    
            <label for="labelTexto">Texto</label>
            <textarea class="input_select" name="texto" id="texto"></textarea>
            
            <label for="labelPrecio">Precio</label>
            <input class="input_select" type="number" step="50" min="0" name="precio" id="precio"><label id="tipoPrecio"></label>
            
            <label for="labelCiudad">Ciudad</label>
            <input class="input_select" type="text" name="ciudad" id="ciudad">
    
            <label for="labelCountry">País de residencia: </label>
    
            <select class="input_select" name="pais" id="country">
                <option selected value=""></option>
                <option value="Alemania">Alemania</option>
                <option value="Espanya">España</option>
                <option value="Francia">Francia</option>
                <option value="Grecia">Grecia</option>
                <option value="Italia">Italia</option>
                <option value="Polonia">Polonia</option>
                <option value="ReinoUnido">Reino Unido</option>
                <option value="Suecia">Suecia</option>
                <option value="Suiza">Suiza</option>
                <option value="Ucrania">Ucrania</option>
            </select>
        </section>

        <section>
            <h3>Características de la vivienda</h3>
            <label for="labelSuperficie">Superficie</label>
            <input class="input_select" type="number" step="10" min="0" name="superficie" id="superficie"> <label id="metrosCuadrados">m<sup>2</sup></label>

            <label for="labelBanyos">Baños</label>
            <input class="input_select" type="number" step="1" min="0" name="banyos" id="banyos">

            <label for="labelHabitaciones">Habitaciones</label>
            <input class="input_select" type="number" step="1" min="0" name="habitaciones" id="habitaciones">

            <label for="labelPlanta">Planta</label>
            <input class="input_select" type="number" step="1" min="0" name="planta" id="planta">

            <label for="labelFechaCreacion">Año de construcción de la vivienda</label>
            <input class="input_select" type="number" step="1" min="1900" max="<?php echo date('Y'); ?>" name="fechaCreacion" id="fechaCreacion" value="2000">
        </section>

        



    </form>

<?php
    include 'includes/footer.php';
?>