<?php
    $title="Registro";
    $acceder = "Acceder";
    $css="css/registro.css";
    include 'includes/header.php'; 

    $localizacion = $_SERVER['REQUEST_URI'];
    
    if(explode('registro.php', $localizacion)[1] != '') { // si hay algo despues de registro.php
        $separadorLocalizacion = explode('_', $localizacion);
    
        $nombre = $separadorLocalizacion[1];
        $pass1 = $separadorLocalizacion[3];
        $pass2 = $separadorLocalizacion[5];
        $email = $separadorLocalizacion[7];
        $sex = $separadorLocalizacion[9];
        $nacimiento = $separadorLocalizacion[11];
        $ciudad = $separadorLocalizacion[13];
        $pais = $separadorLocalizacion[15];
    }

?>

        <h1>Registro</h1>
        <section>
            <form id="formRegistro" action="respuesta_registro.php" method="post">
                <label for="labelName">Nombre: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '') 
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="nombre" type="text" id="name">';

                    else if(sizeof(explode('nombreVacio', $localizacion)) == 2) {
                        echo   '<section class="errorForm">
                                    <input class="input_select" onfocus="restaurarEstilo(this.id);" name="nombre" type="text" id="name"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    }
                    else
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="nombre" value="'.htmlspecialchars($nombre).'" type="text" id="name">';

                ?>

                <label for="labelPassword">Contraseña: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '') 
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="pass1" type="password" id="password">';

                    else if(sizeof(explode('pass1Vacia', $localizacion)) == 2) {
                        echo   '<section class="errorForm">
                                    <input class="input_select" onfocus="restaurarEstilo(this.id);" name="pass1" type="password" id="password"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    }
                    else
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="pass1" value="'.htmlspecialchars($pass1).'" type="password" id="password">';


                ?>
                
                
                <label for="labelPassword2">Repetir contraseña: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '') 
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="pass2" type="password" id="password2">';

                    else if(sizeof(explode('pass2Vacia', $localizacion)) == 2) {
                        echo   '<section class="errorForm">
                                    <input class="input_select" onfocus="restaurarEstilo(this.id);" name="pass2" type="password" id="password2"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    }
                    else if(sizeof(explode('passNoCoinciden', $localizacion)) == 2) {
                        echo   '<section class="errorForm">
                                    <input class="input_select" onfocus="restaurarEstilo(this.id);" name="pass2" type="password" value="'.htmlspecialchars($pass2).'" id="password2"><p class="errorForm">Las contraseñas no coinciden</p>
                                </section>';
                    }
                    else 
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" value="'.htmlspecialchars($pass2).'" name="pass2" type="password" id="password2">';
                ?>
                
                
                <label for="labelEmail">Correo electrónico: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '') 
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="email" type="text" id="email" placeholder="parte-local@dominio">';
                    else
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="email" value="'.htmlspecialchars($email).'" type="text" id="email" placeholder="parte-local@dominio">';

                ?>
                
                <label for="labelSex">Sexo: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '') {
                        echo '  <select onchange="borrarVacio();" onclick="restaurarEstilo(this.id);" name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option id="vacio" value=""></option>
                                    <option value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>';
                    }
                    else {
                        if($sex == 'Hombre') {
                            echo '  <select onchange="borrarVacio();" onclick="restaurarEstilo(this.id);" name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option id="vacio" value=""></option>
                                    <option selected value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>';
                        }
                        else if($sex == 'Mujer') {
                            echo '  <select onchange="borrarVacio();" onclick="restaurarEstilo(this.id);" name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option id="vacio" value=""></option>
                                    <option value="Hombre">Hombre</option>
                                    <option selected value="Mujer">Mujer</option>
                                </select>';
                        }
                        else {
                            echo '  <select onchange="borrarVacio();" onclick="restaurarEstilo(this.id);" name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option id="vacio" value=""></option>
                                    <option value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>';
                        }
                    }

                ?>
                
                
                <label for="labelBirth">Fecha de nacimiento: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '') 
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="nacimiento" placeholder="dia-mes-año" type="text" id="birth">';
                    else
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="nacimiento" value="'.htmlspecialchars($nacimiento).'" placeholder="dia-mes-año" type="text" id="birth">';
                ?>
                        

                
                <label for="labelCity">Ciudad de residencia: </label>
                <?php
                    if (explode('registro.php', $localizacion)[1] == '')
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="ciudad" type="text" id="city">';
                    else
                        echo '<input class="input_select" onfocus="restaurarEstilo(this.id);" name="ciudad" value="'.htmlspecialchars($ciudad).'" type="text" id="city">';
                ?>

                <label for="labelCountry">País de residencia: </label>

                <select class="input_select" name="pais" id="country">
                    <?php
                        if (explode('registro.php', $localizacion)[1] == '') {
                            echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                        }
                        else {
                            switch($pais) {
                                case "Alemania":
                                    echo '  <option selected value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Espanya":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option selected value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Francia":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option selected value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Grecia":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option selected value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Italia":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option selected value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Polonia":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option selected value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "ReinoUnido":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option selected value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Suecia":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option selected value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Suiza":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option selected value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;

                                case "Ucrania":
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option selected value="Ucrania">Ucrania</option>';
                                    break;

                                default:
                                    echo '  <option value="Alemania">Alemania</option>
                                    <option value="Espanya">España</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="ReinoUnido">Reino Unido</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Ucrania">Ucrania</option>';
                                    break;
                            }
                        }
                    ?>
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