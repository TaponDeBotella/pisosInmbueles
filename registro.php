<?php
    $title="Registro";
    $acceder = "Acceder";
    $css="css/registro.css";
    include 'includes/header.php'; 
?>

        <h1>Registro</h1>
        <section>
            <form id="formularioRegistro" onsubmit="misubmit( event );">
                <label for="labelName">Nombre: </label>
                <input class="input_select" onfocus="restaurarEstilo(this.id);" type="text" id="name">
    
                <label for="labelPassword">Contraseña: </label>
                <input class="input_select" onfocus="restaurarEstilo(this.id);" type="password" id="password">
                
                <label for="labelPassword2">Repetir contraseña: </label>
                <input class="input_select" onfocus="restaurarEstilo(this.id);" type="password" id="password2">
                
                <label for="labelEmail">Correo electrónico: </label>
                <input class="input_select" onfocus="restaurarEstilo(this.id);" type="text" id="email" placeholder="parte-local@dominio">
                
                <label for="labelSex">Sexo: </label>
                <select onchange="borrarVacio();" onclick="restaurarEstilo(this.id);" class="input_select" id="sex">
                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                    <option id="vacio" value=""></option>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
                
                <label for="labelBirth">Fecha de nacimiento: </label>
                <input class="input_select" onfocus="restaurarEstilo(this.id);" placeholder="dia-mes-año" type="text" id="birth">
                
                <label for="labelCity">Ciudad de residencia: </label>
                <input class="input_select" onfocus="restaurarEstilo(this.id);" type="text" id="city">
                
                <label for="labelCountry">País de residencia: </label>
                <select class="input_select" id="country">
                    <option value="gr">Alemania</option>
                    <option value="es">España</option>
                    <option value="fr">Francia</option>
                    <option value="gre">Grecia</option>
                    <option value="it">Italia</option>
                    <option value="pol">Polonia</option>
                    <option value="uk">Reino unido</option>
                    <option value="swi">Suecia</option>
                    <option value="swe">Suiza</option>
                    <option value="ukr">Ucrania</option>
                </select>
                
                <label for="labelFoto">Foto: </label>
                <label for="foto" class="boton" id="examinar">Examinar </label>
                <!-- <input type="file" accept="image/*" required> -->
                <input id="foto" type="file" style="display:none;">
    
                
                <input class="boton" id="confirmar" type="submit" value="Confirmar">
                <input class="boton" type="reset" value="Reset">
    
            </form>
        </section>
        <nav id="simulacion">
                <a href="mi_perfil.php">Simular que has iniciado sesión</a>
        </nav>

<?php
    include 'includes/footer.php';
?>