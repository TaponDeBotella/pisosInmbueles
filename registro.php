<?php
    session_start();
    $title="Registro";
    $acceder = "Acceder";
    $css="css/registro.css";
    include 'includes/header.php'; 

    
    // Recupero los datos de la sesión si existen
    if(isset($_SESSION['registro'])) {
        $nombre = $_SESSION['registro']['nombre'];
        $pass1 = $_SESSION['registro']['pass1'];
        $pass2 = $_SESSION['registro']['pass2'];
        $email = $_SESSION['registro']['email'];
        $sex = $_SESSION['registro']['sex'];
        $nacimiento = $_SESSION['registro']['nacimiento'];
        $ciudad = $_SESSION['registro']['ciudad'];
        $pais = $_SESSION['registro']['pais'];
    }


    $resultadoPaises = $db->query('SELECT IdPais, NomPais FROM Paises');
    if (!$resultadoPaises) {
        die('Error: ' . $db->error);
    }
    $paises = [];
    while ($fila = $resultadoPaises->fetch_array(MYSQLI_ASSOC)) {
        $paises[] = $fila;
    }
?>

        <h1>Registro</h1>
        <section>
            <form id="formRegistro" action="respuesta_registro.php" method="post">
                <label for="labelName">Nombre: </label>
                <?php
                    if (!isset($_SESSION['registro']) && !isset($_SESSION['errores'])) { // si no hay errores ni se ha rellenado este campo en una sesion anterior pongo el html por defecto
                        echo '<input class="input_select" name="nombre" type="text" id="name">';
                    }
                    else if(isset($_SESSION['errores']) && in_array('nombreVacio', $_SESSION['errores'])) { // si este campo esta vacio anyado el mensaje de error para resaltarlo
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="nombre" type="text" id="name"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    }
                    else if($nombre) // si no hay errores en este campo pero se ha recargado la pagina se mantienen los datos almacenados en sesion
                        echo '<input class="input_select" name="nombre" value="'.htmlspecialchars($nombre).'" type="text" id="name">';

                ?>

                <label for="labelPassword">Contraseña: </label>
                <?php
                    if (!isset($_SESSION['registro']) && !isset($_SESSION['errores'])) { // si no hay errores ni se ha rellenado este campo en una sesion anterior pongo el html por defecto
                        echo '<input class="input_select" name="pass1" type="password" id="password">';
                    }
                    else if(isset($_SESSION['errores']) && in_array('pass1Vacia', $_SESSION['errores'])) { // si este campo esta vacio anyado el mensaje de error para resaltarlo
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass1" type="password" id="password"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    }
                    else // si no hay errores en este campo pero se ha recargado la pagina se mantienen los datos almacenados en sesion
                        echo '<input class="input_select" name="pass1" value="'.htmlspecialchars($pass1).'" type="password" id="password">';
                ?>
                
                
                <label for="labelPassword2">Repetir contraseña: </label>
                <?php
                    if (!isset($_SESSION['registro']) && !isset($_SESSION['errores'])) { // si no hay errores ni se ha rellenado este campo en una sesion anterior pongo el html por defecto
                        echo '<input class="input_select" name="pass2" type="password" id="password2">';
                    }
                    else if(isset($_SESSION['errores']) && in_array('pass2Vacia', $_SESSION['errores'])) { // si este campo esta vacio anyado el mensaje de error para resaltarlo
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass2" type="password" id="password2"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    }
                    else if(isset($_SESSION['errores']) && in_array('passNoCoinciden', $_SESSION['errores'])) { // si las contrasenyas no coinciden
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass2" type="password" value="'.htmlspecialchars($pass2).'" id="password2"><p class="errorForm">Las contraseñas no coinciden</p>
                                </section>';
                    }
                    else // si no hay errores en este campo pero se ha recargado la pagina se mantienen los datos almacenados en sesion
                        echo '<input class="input_select" value="'.htmlspecialchars($pass2).'" name="pass2" type="password" id="password2">';
                ?>
                
                
                <label for="labelEmail">Correo electrónico: </label>
                <?php
                    if (!isset($_SESSION['registro']))  // si no hay datos en la sesion pongo el html por defecto
                        echo '<input class="input_select" name="email" type="text" id="email" placeholder="parte-local@dominio">';
                    else // si hay datos en la sesion los introduzco
                        echo '<input class="input_select" name="email" value="'.htmlspecialchars($email).'" type="text" id="email" placeholder="parte-local@dominio">';
                ?>
                
                <label for="labelSex">Sexo: </label>
                <?php
                    if (!isset($_SESSION['registro'])) { // si no hay nada en la sesion pongo los datos por defecto
                        echo '  <select   name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option id="vacio" value=""></option>
                                    <option value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>';
                    }
                    else {
                        if($sex == 'Hombre') { // caso hombre
                            echo '  <select  name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option selected value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>';
                        }
                        else if($sex == 'Mujer') { // caso mujer
                            echo '  <select   name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option value="Hombre">Hombre</option>
                                    <option selected value="Mujer">Mujer</option>
                                </select>';
                        }
                        else { // caso si habia sesion pero se dejo el campo por defecto
                            echo '  <select   name="sex" class="input_select" id="sex">
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
                    if (!isset($_SESSION['registro'])) // si no hay nada en la sesion pongo los datos por defecto
                        echo '<input class="input_select" name="nacimiento" placeholder="dia-mes-año" type="text" id="birth">';
                    else
                        echo '<input class="input_select" name="nacimiento" value="'.htmlspecialchars($nacimiento).'" placeholder="dia-mes-año" type="text" id="birth">';
                ?>
                        

                
                <label for="labelCity">Ciudad de residencia: </label>
                <?php
                    if (!isset($_SESSION['registro'])) // si no hay nada en la sesion pongo los datos por defecto
                        echo '<input class="input_select" name="ciudad" type="text" id="city">';
                    else
                        echo '<input class="input_select" name="ciudad" value="'.htmlspecialchars($ciudad).'" type="text" id="city">';
                ?>

                <label for="labelCountry">País de residencia: </label>

                <select class="input_select" name="pais" id="country">
                    <?php
                        if (!isset($_SESSION['registro'])) { // si no hay nada en la sesion pongo los datos por defecto
                            echo '  
                                    <option selected value=""></option>';

                            for($i=0; $i<sizeof($paises); $i++) {
                                $cadena = '<option';
                                $cadena .= ' value="'.htmlspecialchars($paises[$i]['IdPais']).'">'.htmlspecialchars($paises[$i]['NomPais']).'</option>';

                                echo $cadena;
                            }
                        }
                        else {
                            for($i=0; $i<sizeof($paises); $i++) {
                                $cadena = '<option';
                                if($paises[$i]['IdPais'] == $pais) 
                                    $cadena .= ' selected';

                                $cadena .= ' value="'.htmlspecialchars($paises[$i]['IdPais']).'">'.htmlspecialchars($paises[$i]['NomPais']).'</option>';

                                echo $cadena;
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

<?php
    include 'includes/footer.php';
?>