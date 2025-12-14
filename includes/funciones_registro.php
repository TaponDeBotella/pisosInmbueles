<?php
function validarNombre(string $nombre):?string {
    $regex = '/^[A-Za-z][A-Za-z0-9]{2,14}$/'; // con / / indico que es regex. El corchete que sigue detras de ^ solo se aplica al primer caracter, de modo que ya estamos obligando a que exista este primer caracter. El segundo corchete. (primero compruebo que no valen numeros pero si letras, despues valen numeros y letras). Y como he puesto junto al corcheta unas llaves, estas obligan a que esa subcadena tenga de 2 a 14 caracteres. $ da fin a la cadena
    $filtro_regex = filter_id('validate_regexp');
    if($filtro_regex !== false)  // busco el filtro en la lista con la id
        $nombre = filter_var($nombre, $filtro_regex, ['options' => ['regexp' => $regex]]);
    else if(defined('FILTER_VALIDATE_REGEXP')) // si no existe la id, miro si esta definida la constante con el nombre que deberia tener actualmente
        $nombre = filter_var($nombre, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex]]);
    else { // si no exite el filtro, compruebo el regex yo con preg_match
        $correcto = preg_match($regex, $nombre);
        if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
            $nombre = null;
    }

    if($nombre === false)
        $nombre = null;

    return $nombre;
}

function validarEmail($email):?string {
    $filtro_email = filter_id('validate_email');

    if($email !== '' && gettype($email) === 'string' && strlen($email) <= 254) {
           $regex = "/^(?!\.)(?!.*\.\.)[A-Za-z0-9!#$%&'*.+\-\/=?^_`{|}~]{1,64}(?<!\.)@(?!\.)[A-Za-z0-9\-.]{1,255}(?<!\.)$/";
           
           $filtro_regex = filter_id('validate_regexp');
           
           if($filtro_regex !== false)  // busco el filtro en la lista con la id
           $email = filter_var($email, $filtro_regex, ['options' => ['regexp' => $regex]]);
           else if(defined('FILTER_VALIDATE_REGEXP')) // si no existe la id, miro si esta definida la constante con el nombre que deberia tener actualmente
           $email = filter_var($email, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex]]);
           else { // si no exite el filtro, compruebo el regex yo con preg_match
            $correcto = preg_match($regex, $email);
            if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
            $email = null;
        }
        
        if($email === false)
            $email = null;
        

        // ahora falta comprobar los subdominios. Estos deben tener como mucho 63 caracteres y no pueden empezar ni acabar por -

        if($email !== null) { // si las comprobaciones anteriores no han anulado $email
            $subdominios = explode('@', $email); // divido por @ la cadena para obtener los dominios
            if(sizeof($subdominios) > 1) {
                $subdominios = explode('.', $subdominios[1]); // divido por . para obtener los subdominios
            
                $regex = "/^(?!\-)[A-Za-z0-9\-]{1,63}(?<!\-)$/";
                
                if($filtro_regex !== false) {  // busco el filtro en la lista con la id
                    $correcto = true;
                    for($i=0; $i<sizeof($subdominios) && $correcto; $i++) { // recorro el array y escapo en el momento en que hay un subdominio invalido
                        $valor_filtro = filter_var($subdominios[$i], $filtro_regex, ['options' => ['regexp' => $regex]]);
                        if($valor_filtro === false || $valor_filtro === 0)
                            $correcto = false;
                    }
                    if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
                        $subdominios = null;
                }        
                else if(defined('FILTER_VALIDATE_REGEXP')) {// si no existe la id, miro si esta definida la constante con el nombre que deberia tener actualmente
                    $correcto = true;
                    for($i=0; $i<sizeof($subdominios) && $correcto; $i++) { // recorro el array y escapo en el momento en que hay un subdominio invalido
                        $valor_filtro = filter_var($subdominios[$i], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex]]);
                        if($valor_filtro === false || $valor_filtro === 0)
                            $correcto = false;
                    }
                    if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
                        $subdominios = null;
                }
                else { // si no exite el filtro, compruebo el regex yo con preg_match
                    $correcto = true;
                    for($i=0; $i<sizeof($subdominios) && $correcto; $i++) { // recorro el array y escapo en el momento en que hay un subdominio invalido
                        $valor_consulta_regex = preg_match($regex, $subdominios[$i]);
                        if($valor_consulta_regex === false || $valor_consulta_regex === 0)
                            $correcto = false;
                    }
                    if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
                        $subdominios = null;
                }
            

                if($subdominios === null)
                    $email = null;
                
            }
        }
    }
    else
        $email = null;
    

    return $email;
}


function validarPass(string $pass1):?string {
    $regex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9_-]{6,15}$/'; // (?=.* [algo]) indica que al menos debe haber un caracter que siga lo que hay en ese [algo]. Si pongo - al final del patron no cuenta como rango. Otra opcion hubiese sido poner \- en cualquier punto del patron

    $filtro_regex = filter_id('validate_regexp');
    if($filtro_regex !== false)  // busco el filtro en la lista con la id
        $pass1 = filter_var($pass1, $filtro_regex, ['options' => ['regexp' => $regex]]);
    else if(defined('FILTER_VALIDATE_REGEXP')) // si no existe la id, miro si esta definida la constante con el nombre que deberia tener actualmente
        $pass1 = filter_var($pass1, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex]]);
    else { // si no exite el filtro, compruebo el regex yo con preg_match
        $correcto = preg_match($regex, $pass1);
        if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
            $pass1 = null;
    }

    if($pass1 === false)
        $pass1 = null;

    return $pass1;
}

function validarRepeatPass($pass1, $pass2):bool {
    $repeatPassValido = false;
    
    if($pass1 === $pass2)
        $repeatPassValido = true;

    return $repeatPassValido;
}

function validarSexo($sex):bool {
    $sexoValido = false;

    if($sex === 'Hombre' || $sex === 'Mujer')
        $sexoValido = true;

    return $sexoValido;
}

function validarFechaNac($nacimiento):bool {
    $fechaValida = false;
    $diaValido = true;
    $mesValido = true;
    $anyoValido = true;
    $caracteresNumericos = true;
    $fechaCompleta = true;
    $fecha_actual = new DateTime();
    
    // compruebo que la fecha sigue el formato
    $regex = '/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/'; // formato de fecha dia-mes-anyo
    $filtro_regex = filter_id('validate_regexp');
    if($filtro_regex !== false)  // busco el filtro en la lista con la id
        $nacimiento = filter_var($nacimiento, $filtro_regex, ['options' => ['regexp' => $regex]]);
    else if(defined('FILTER_VALIDATE_REGEXP')) // si no existe la id, miro si esta definida la constante con el nombre que deberia tener actualmente
        $nacimiento = filter_var($nacimiento, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $regex]]);
    else { // si no exite el filtro, compruebo el regex yo con preg_match
        $correcto = preg_match($regex, $nacimiento);
        if($correcto === false || $correcto === 0) // si el regex es incorrecto o ha habido algun fallo
        $nacimiento = null;
    }
    
    if($nacimiento === false)
        $nacimiento = null;
    
    if($nacimiento !== null) { // compruebo que el formato se haya cumplido
        $fechaDividida = explode('-', $nacimiento);
    
        if(count($fechaDividida) == 3) { // compruebo que solamente hay dos - (dia-mes-anyo)
            if($caracteresNumericos && $fechaCompleta) {   // si se han introducido solo numeros
                $dia = intval($fechaDividida[0]);
                $mes = intval($fechaDividida[1]);
                $anyo = intval($fechaDividida[2]);
    
                if($dia < 1)
                    $diaValido = false;
    
                if($mes < 1 || $mes > 12)
                    $mesValido = false;
    
                if($anyo > (int)$fecha_actual->format('Y'))
                    $anyoValido = false;
    
                if($diaValido && $mesValido && $anyoValido) {
                    if($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12) {
                        if($dia > 31) {
                            $diaValido = false;
                        }
                    }
                    else if($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) {
                        if($dia > 30) {
                            $diaValido = false;
                        }
                    }
                    else { // febrero
                        if((int)$fecha_actual->format('Y')%4 == 0) {// bisiesto
                            if($dia > 29) {
                                $diaValido = false;
                            }
                        }
                        else {
                            if($dia > 28) {
                                $diaValido = false;
                            }
                        }
                    }
                }
    
                if($diaValido && $mesValido && $anyoValido) {// si hasta aqui la fecha es valida compruebo si es mayor de edad
                    if((int)$fecha_actual->format('Y') - $anyo > 18) // si hace mas de 18 anyos ya de diferencia no se comprueba nada mas
                        $fechaValida = true;
                    else if((int)$fecha_actual->format('Y') - $anyo < 18) { // del mismo modo, si hace menos de 18 anyos desde esa fecha se sabe que es menor
                        $fechaValida = false;
                    }
                    else {
                        if((int)$fecha_actual->format('n') < $mes) { // format('n') te lo devuelve considerando enero un 1. Si el mes actual es menor que el de la fecha (ya ha pasado) es mayor
                            $fechaValida = false;
                        }
                        else if((int)$fecha_actual->format('n') > $mes) // si el mes es mayor, aun no ha llegado su cumpleanyos 18
                            $fechaValida = true;
                        else {
                            if ((int)$fecha_actual->format('j') >= $dia) // si estamos en el mismo mes pero su cumpleanyos fue antes en el mes u hoy, tiene 18
                                $fechaValida = true;
                        }
                    }
                }
            }
        }
    }

    return $fechaValida;
}

function validarFoto($foto):array {
    $resultado = ['valida' => false, 'error' => '']; // de resultado le voy a devolver si la foto es valida o no con un bool y el mensaje de error en caso de que no lo sea

    $tiposPermitidos = ['image/jpeg', 'image/png']; // los tipo de imagenes permitidos para que no puedan subir archivos malos
    $tamanyoMaximo = 5 * 1024 * 1024; // tamanyo maximo de 5mb para que no se puedan subir archivos muy pesados y llenar la base de datos

    if(in_array($foto['type'], $tiposPermitidos)) { // si el tipo de la foto esta en el array de tipos permitidos entonces se comprueba el tamanyo
        if($foto['size'] <= $tamanyoMaximo) { // si el tamnayo es valido entonces la foto es valida
            $resultado['valida'] = true; // se pone a true
        } else { // si es demasiado grande entonces se le manda ese erorr tambien
            $resultado['error'] = 'fotoMuyGrande';
        }
    } else { // si el formato no es valido se manda ese error
        $resultado['error'] = 'formatoFotoNoValido';
    }

    return $resultado; // se devuelve el bool y el mensaje de error si hay
}
?>