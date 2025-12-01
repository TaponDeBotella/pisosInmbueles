<?php
    session_start();
    $title="Mis datos";
    $acceder = "Acceder";
    $css="css/registro.css";
    include 'includes/header.php'; 
    include 'includes/flash.php';
    
    
    // Recupero los datos de la sesión si existen
    $cambio_datos = flash_get_data('mis_datos');
    $errores = flash_get_data('errores');

    // inicializo las variables de formulario para evitar warnings si no vienen en
    // flash ni en la sesión. Se rellenarán más abajo si hay datos.
    $nombre = $pass1 = $pass2 = $email = $sex = $nacimiento = $ciudad = $pais = '';

    // si $errores no es null y tampoco es un array, convierto a array
    if ($errores !== null && !is_array($errores)) {
        $errores = [$errores];
    }

    var_dump($_SESSION['nombre']);

    if(isset($_SESSION['nombre'])) {        
        $stmt = $db->prepare( // preparo la consulta (para evitar inyeccion sql, se pone ? donde se debe poner un parametro)
            "SELECT NomUsuario, Clave, NomPais, Email, Sexo, FNacimiento, Ciudad
            FROM Usuarios, Paises
            WHERE NomUsuario = ? AND Pais = IdPais"
        );    
        
        if (!$stmt) { // comprobacion de si hay statement
            die('Error:  ' . $db->error); // para y da el error
        }
        
        $stmt->bind_param('s', $_SESSION['nombre']); // vinculo el parametro

        if(!$stmt->execute()) { // ejecuto y miro si hay error
            die('Error: ' . $stmt->error);
        }

        $resultado = $stmt->get_result(); // guardo el resultado

        if($resultado) {
            $usuario = $resultado->fetch_assoc(); // convierto el resultado en array asociativo
            $resultado->free();
        }

        $stmt->close();


        $nombre = $usuario['NomUsuario'];
        $pass1 = '';
        $pass2 = '';
        $email = $usuario['Email'];
        $sex = $usuario['Sexo'];
        $nacimiento = $usuario['FNacimiento'];
        $ciudad = $usuario['Ciudad'];
        $pais = $usuario['NomPais'];
    }

    if($cambio_datos && $cambio_datos != null) { // si el usuario ha hecho algun cambio los actualizo con lo que ha puesto
        $nombre = $cambio_datos['nombre'];
        $pass1 = $cambio_datos['pass1'];
        $pass2 = $cambio_datos['pass2'];
        $email = $cambio_datos['email'];
        $sex = $cambio_datos['sex'];
        $nacimiento = $cambio_datos['nacimiento'];
        $ciudad = $cambio_datos['ciudad'];
        $pais = $cambio_datos['pais'];
    }
        


    $resultadoPaises = $db->query('SELECT IdPais, NomPais FROM Paises ORDER BY NomPais ASC');
    if (!$resultadoPaises) {
        die('Error: ' . $db->error);
    }
    $paises = [];
    while ($fila = $resultadoPaises->fetch_array(MYSQLI_ASSOC)) {
        $paises[] = $fila;
    }
?>

        <h1>Actualizar mis datos</h1>
        <section>
            <form id="formRegistro" action="respuesta_mis_datos.php" method="post">
                <label for="labelName">Nombre: </label>
                <?php
                    if($cambio_datos == null && $errores == null) { // si no hay errores ni se ha rellenado este campo en una sesion anterior pongo el html por defecto
                        if(isset($_SESSION['nombre'])) {// si ya hay una sesion iniciada (estoy en "MIS DATOS")
                            echo '<input class="input_select" name="nombre" value="'.htmlspecialchars($nombre).'" type="text" id="name">';
                        }
                        else
                            echo '<input class="input_select" name="nombre" type="text" id="name">';
                    }
                    else if($errores !== null && $nombre && in_array('malNombre', $errores)) { // si hay un error en el nombre lo resalto
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="nombre" value="'.htmlspecialchars($nombre).'" type="text" id="name"><p class="errorForm">Formato incorrecto</p>
                                </section>
                                <section class="consejo">
                                    <p>Caracteres válidos: [A-Z, a-z, 0-9]</p>
                                    <p>Caracteres ingleses</p>
                                    <p>NO puede empezar por número.</p>
                                    <p>Longitud: [3,15]</p>
                                </section>';
                                
                    }
                    else if($errores !== null && $nombre === '') // nombre vacio
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="nombre" type="text" id="name"><p class="errorForm">Campo vacío</p>
                                </section>
                                ';
                    else if($nombre) // si no hay errores en este campo pero se ha recargado la pagina se mantienen los datos almacenados en sesion
                        echo '<input class="input_select" name="nombre" value="'.htmlspecialchars($nombre).'" type="text" id="name">';

                ?>

                <label for="labelPassword">Nueva contraseña: </label>
                <?php
                    if ($cambio_datos == null && $errores == null) { // si no hay errores ni se ha rellenado este campo en una sesion anterior pongo el html por defecto
                        if(isset($_SESSION['nombre'])) // si ya hay una sesion iniciada (estoy en "MIS DATOS")
                            echo '<input class="input_select" name="pass1" value="'.htmlspecialchars($pass1).'" type="password" id="password">';
                        else 
                            echo '<input class="input_select" name="pass1"  type="password" id="password">';
                    }
                    else if($errores !== null && $pass1 && in_array('malPass1', $errores)) { // si hay un error anyado el mensaje para resaltarlo
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass1" value="'.htmlspecialchars($pass1).'" type="password" id="password"><p class="errorForm">Formato incorrecto</p>
                                </section>
                                <section class="consejo">
                                    <p>Caracteres válidos: [A-Z, a-z, 0-9, "-_"]</p>
                                    <p>Caracteres ingleses</p>
                                    <p>Al menos, una mayúscula</p>
                                    <p>Al menos, una minúscula</p>
                                    <p>Al menos, un número</p>
                                    <p>Longitud: [6,15]</p>
                                </section>';
                    }
                    else if($errores !== null && $pass1 === '') { // si este campo esta vacio anyado el mensaje de error para resaltarlo
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass1" value="'.htmlspecialchars($pass1).'" type="password" id="password"><p class="errorForm">Campo vacio</p>
                                </section>';
                    }
                    else // si no hay errores en este campo pero se ha recargado la pagina se mantienen los datos almacenados en sesion
                        echo '<input class="input_select" name="pass1" value="'.htmlspecialchars($pass1).'" type="password" id="password">';
                ?>
                
                
                <label for="labelPassword2">Repetir contraseña: </label>
                <?php
                    if ($cambio_datos == null && $errores == null) { // si no hay errores ni se ha rellenado este campo en una sesion anterior pongo el html por defecto
                        if(isset($_SESSION['nombre'])) // si ya hay una sesion iniciada (estoy en "MIS DATOS")
                            echo '<input class="input_select" value="'.htmlspecialchars($pass2).'" name="pass2" type="password" id="password2">';
                        else
                            echo '<input class="input_select" name="pass2" type="password" id="password2">';
                    }
                    /* else if($errores != null && in_array('pass2Vacia', $errores)) { // si este campo esta vacio anyado el mensaje de error para resaltarlo
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass2" type="password" id="password2"><p class="errorForm">Este campo no puede estar vacío</p>
                                </section>';
                    } */
                    else if($errores != null && $pass2 && in_array('malRepetirPass', $errores)) { // si las contrasenyas no coinciden
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass2" type="password" value="'.htmlspecialchars($pass2).'" id="password2"><p class="errorForm">Las contraseñas no coinciden</p>
                                </section>';
                    }
                    else if($errores != null && $pass2 === '') { // si las contrasenyas no coinciden
                        echo   '<section class="errorForm">
                                    <input class="input_select" name="pass2" type="password" id="password2"><p class="errorForm">Campo vacío</p>
                                </section>';
                    }
                    else // si no hay errores en este campo pero se ha recargado la pagina se mantienen los datos almacenados en sesion
                        echo '<input class="input_select" value="'.htmlspecialchars($pass2).'" name="pass2" type="password" id="password2">';
                ?>
                
                
                <label for="labelEmail">Correo electrónico: </label>
                <?php
                    if ($cambio_datos == null)  {// si no hay datos en la sesion pongo el html por defecto
                        if(isset($_SESSION['nombre']))
                            echo '<input class="input_select" name="email" value="'.htmlspecialchars($email).'" type="text" id="email" placeholder="parte-local@dominio">';
                        else
                            echo '<input class="input_select" name="email" type="text" id="email" placeholder="parte-local@dominio">';
                    }
                    else {
                        if($errores !== null && in_array('malEmail', $errores, true) && $email !== '') // error en email
                            echo   '<section class="errorForm">
                                        <input class="input_select" name="email" type="text" value="'.htmlspecialchars($email).'" id="email" placeholder="parte-local@dominio"><p class="errorForm">Formato incorrecto</p>
                                    </section>
                                    <section class="consejo">
                                        <p>Formato: parte-local@dominio</p>
                                        <p>Longitud local: [1,64]</p>
                                        <p>Longitud dominio: [1,255]</p>
                                        <p>Caracteres ingleses</p>
                                        <p>Caracteres permitidos local: [A-Z, a-z, 0-9, "!#$%&’*+-/=?^_‘{|}~."]</p>
                                        <p>No pueden haber dos o más puntos seguidos ni empezar o acabar en punto, tanto en la local como el dominio</p>
                                        <p>El dominio puede separarse en subdominios por puntos</p>
                                        <p>Longitud subdominio: [1,63]</p>
                                        <p>Caracteres permitidos subdominio: [A-Z, a-z, 0-9, "-"]</p>
                                        <p>El guión no puede aparecer ni al principio ni al final del subdominio</p>
                                        <p>Longitud dirección entera: [3,254]</p>






                                    </section>';

                        else if($errores !== null && in_array('malEmail', $errores, true) && $email === '') // email vacio
                            echo   '<section class="errorForm">
                                        <input class="input_select" name="email" type="text" id="email" placeholder="parte-local@dominio"><p class="errorForm">Campo vacío</p>
                                    </section>';
                        else
                            echo '<input class="input_select" name="email" value="'.htmlspecialchars($email).'" type="text" id="email" placeholder="parte-local@dominio">';

                    }
                ?>
                
                <label for="labelSex">Sexo: </label>
                <?php
                    if(isset($_SESSION['nombre'])) { // loggeado
                        if($sex == 'Hombre') { // caso hombre
                            echo '  <select  name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option selected value="Hombre">Hombre</option>
                                    <option value="Mujer">Mujer</option>
                                </select>';
                        }
                        else { // caso mujer
                            echo '  <select   name="sex" class="input_select" id="sex">
                                    <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                    <option value="Hombre">Hombre</option>
                                    <option selected value="Mujer">Mujer</option>
                                </select>';
                        }
                    }
                    else {
                        echo '  <select   name="sex" class="input_select" id="sex">
                                <!-- <option value="null" selected disabled hidden>Selecciona una opción</option> -->
                                <option id="vacio" value=""></option>
                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                            </select>';
                    }
                ?>
                
                
                <label for="labelBirth">Fecha de nacimiento: </label>
                <?php
                    if ($cambio_datos == null) {// si no hay nada en la sesion pongo los datos por defecto
                        if(isset($_SESSION['nombre'])) 
                            echo '<input class="input_select" name="nacimiento" value="'.htmlspecialchars($nacimiento).'" placeholder="dia-mes-año" type="text" id="birth">';
                        else
                            echo '<input class="input_select" name="nacimiento" placeholder="dia-mes-año" type="text" id="birth">';
                    }
                    else {
                        if($errores !== null && in_array('malNacimiento', $errores, true) && $nacimiento !== '') // error en nacimiento
                            echo   '<section class="errorForm">
                                        <input class="input_select" name="nacimiento" value="'.htmlspecialchars($nacimiento).'" placeholder="dia-mes-año" type="text" id="birth"><p class="errorForm">Formato incorrecto</p>
                                    </section>
                                    <section class="consejo">
                                        <p>Formato: dia-mes-año</p>
                                        <p>Sólamente números y guiones, sin espacios</p>
                                        <p>Debes ser mayor de 18 años</p>
                                    </section>';

                        else if($errores !== null && $nacimiento === '') // nacimiento vacio
                            echo   '<section class="errorForm">
                                        <input class="input_select" name="nacimiento" placeholder="dia-mes-año" type="text" id="birth"><p class="errorForm">Campo vacío</p>
                                    </section>';
                        else
                            echo '<input class="input_select" name="nacimiento" value="'.htmlspecialchars($nacimiento).'" placeholder="dia-mes-año" type="text" id="birth">';
                    }
                ?>
                        

                
                <label for="labelCity">Ciudad de residencia: </label>
                <?php
                    if ($cambio_datos == null) {// si no hay nada en la sesion pongo los datos por defecto
                        if(isset($_SESSION['nombre'])) 
                            echo '<input class="input_select" name="ciudad" value="'.htmlspecialchars($ciudad).'" type="text" id="city">';
                        else
                            echo '<input class="input_select" name="ciudad" type="text" id="city">';
                    }
                    else
                        echo '<input class="input_select" name="ciudad" value="'.htmlspecialchars($ciudad).'" type="text" id="city">';
                ?>

                <label for="labelCountry">País de residencia: </label>

                <select class="input_select" name="pais" id="country">
                    <?php
                        if ($cambio_datos == null) { // si no hay nada en la sesion pongo los datos por defecto
                            if(isset($_SESSION['nombre'])) {
                                for($i=0; $i<sizeof($paises); $i++) {
                                    $cadena = '<option';
                                    if($paises[$i]['NomPais'] == $pais) 
                                        $cadena .= ' selected';

                                    $cadena .= ' value="'.htmlspecialchars($paises[$i]['IdPais']).'">'.htmlspecialchars($paises[$i]['NomPais']).'</option>';

                                    echo $cadena;
                                }
                            }
                            else {
                                echo '  
                                        <option selected value=""></option>';
    
                                for($i=0; $i<sizeof($paises); $i++) {
                                    $cadena = '<option';
                                    $cadena .= ' value="'.htmlspecialchars($paises[$i]['IdPais']).'">'.htmlspecialchars($paises[$i]['NomPais']).'</option>';
    
                                    echo $cadena;
                                }
                            }
                            
                        }
                        else {
                            for($i=0; $i<sizeof($paises); $i++) {
                                $cadena = '<option';
                                if($paises[$i]['NomPais'] == $pais) 
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