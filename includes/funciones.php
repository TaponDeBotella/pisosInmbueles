<?php
    function costePorPagina($paginas){ // esta funcion calcula el precio total de las paginas
            $precio=0;
            // como va por tramos hay que ir almacenando el precio
            if ($paginas < 5){ // si son menos de 5 paginas no hay problema con los tramos
                $precio=$paginas*2.0;
            }elseif ($paginas <= 10){ // si son entre 5 y 10 entonces hay que sumarle el precio de las primeras 4 paginas al precio anterior mas las paginas restantes al precio del tramo actual
                $precio=(4*2.0) + (($paginas - 4 ) * 1.8);
            }else { // lo mismo pero ahora con 3 tramos
                $precio = (4*2.0)+(6*1.8)+(($paginas-10) * 1.6);
            }
            return $precio;
        }


    function calcularPrecio($paginas,$fotos,$color,$dpi){ // esta funcion es la que se usa para calcular el precio que se va a mostrar en cada una de las celdas de la tabla 
        $precioEnvio=10.00; // esto es fijo
        $precioPag=costePorPagina($paginas); // se saca el precio de las paginas

        $precioFotoColor= $color ? 0.5 : 0.0; // ahora si es a color vale 0.5 y si no 0

        $precioResolucion = ($dpi > 300) ? 0.2 : 0.0; // si el dpi es mayor que 300 vale 0.2 y si no vale 0

        $precioFotos = $fotos * ($precioFotoColor+$precioResolucion); // ahora se saca el precio de las fotos teniendo en cuenta las dos variables que acabamos de sacar

        return $precioEnvio + $precioPag + $precioFotos; // y se suma todo

    }

    // funcion para lo de la busqueda rapida que es un poco largo y lioso asi que lo meto aqui por si acaso
    function procesarBusquedaRapida($textoBusqueda, $db) { // se le pasa por parametro el texto de busqueda y la conexion a la base de datos
        // se crean los arrays con las cosas que el usuario puede buscar por la busqueda rapida, que solo es el tipo de vivienda, el tipo de anuncio y el pais
        $tiposViviendas = [];
        $tiposAnuncios = [];
        $paises = [];
        
        // se saca el tipo de viviendaes sin importar mayusculas o minusculas
        $result = $db->query('SELECT IdTVivienda, LOWER(NomTVivienda) as NomTVivienda FROM TiposViviendas');
        if ($result) {
            while ($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                $tiposViviendas[strtolower($fila['NomTVivienda'])] = $fila['IdTVivienda'];
            }
        }
        
        // se saca el tipo de anuncios sin importar mayusculas o minusculas
        $result = $db->query('SELECT IdTAnuncio, LOWER(NomTAnuncio) as NomTAnuncio FROM TiposAnuncios');
        if ($result) {
            while ($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                $tiposAnuncios[strtolower($fila['NomTAnuncio'])] = $fila['IdTAnuncio'];
            }
        }
        
        // y los paises sin importar mayusculas o minusculas tambien 
        $result = $db->query('SELECT IdPais, LOWER(NomPais) as NomPais FROM Paises');
        if ($result) {
            while ($fila = $result->fetch_array(MYSQLI_ASSOC)) {
                $paises[strtolower($fila['NomPais'])] = $fila['IdPais'];
            }
        }
        
        // se convierte todo el texto a minusculas y se le quitan los espacios que no sirven por si acaso
        $texto = strtolower(trim($textoBusqueda)); // eso es lo que hace el trim
        
        // se le quitan las palabras que no sirven para la quiery 
        $palabrasVacias = ['un', 'una', 'en', 'de']; // como el profe ha dich oque solo estas pues solo estas
        foreach ($palabrasVacias as $palabra) { 
            $texto = preg_replace('/\b' . $palabra . '\b/i', '', $texto); // se le hace una busqueda y reemolazo para quitar todas las palabras que no me siren y dejar el texto limpio
        }
        
        // se divide en palabras sueltas el texto que ha pasado el usuario
        $palabras = array_filter(explode(' ', $texto));        
        $palabras = array_values($palabras); // se cambia el array 
        
        // se crea el array de parametros para ver que cosas ha pasado el usuario y cuales no y se guardan luego
        $parametrosInmueble = '';
        $parametrosAnuncio = '';
        $parametrosCiudad = '';
        
        $indice = 0;
        
        // tipo de vivienda
        if ($indice < count($palabras)) { // se comprueba que no se haya pasado ya del numero de palabras
            $palabra = $palabras[$indice]; // se saca la palabra actual
            if (isset($tiposViviendas[$palabra])) { // se ve si existe o no
                $parametrosInmueble = $tiposViviendas[$palabra]; // y se guarda el parametro
                $indice++;
            }
        }
        
        // tipo de anuncio
        if ($indice < count($palabras)) { // se comprueba lo mismo que antes y se guarda igual 
            $palabra = $palabras[$indice];
            if (isset($tiposAnuncios[$palabra])) {
                $parametrosAnuncio = $tiposAnuncios[$palabra];
                $indice++;
            }
        }
        
        // ciudad
        if ($indice < count($palabras)) {
            $parametrosCiudad = implode(' ', array_slice($palabras, $indice)); // como la ciudad no tiene tabla en la base de datos no se comprueba nada y simplemente se guarda
        }
        
        return [
            'tipo_inmueble' => $parametrosInmueble,
            'tipo_anuncio' => $parametrosAnuncio,
            'ciudad_busquedaForm' => $parametrosCiudad
        ];
    }

// helper para convertir a referencias (necesario para bind_param al construir dinamicamente la query sql)
function refValues(array &$arr) {
    $refs = [];
    foreach ($arr as $k => &$v) {
        $refs[$k] = &$v;
    }
    return $refs;
}

// Devuelve la ruta correcta para usar en el atributo src de una imagen
function ruta_imagen($ruta) {
    if (empty($ruta)) 
        return '';
    // Si ya es una ruta dentro de fotosSubidas o contiene una barra, devolverla tal cual
    if (strpos($ruta, 'fotosSubidas/') === 0 || strpos($ruta, '/') !== false) {
        return $ruta;
    }
    // Si es solo un nombre de archivo antiguo, está en la carpeta img
    return 'img/' . $ruta;
}

/* function tipoConsejo($consejo) {
    $devolver = -1;
    switch($consejo['categoria']) {
        case 'Compra':
            $devolver = 0;
            break;

        case 'Venta':
            $devolver = 1;
            break;
        
        case 'Alquiler':
            $devolver = 2;
            break;
    }

    return $devolver;
} */

function ordenarConsejosTipo($consejos) {
    $array = [];

    $arrayCompra = [];
    $arrayVenta = [];
    $arrayAlquiler = [];

    

    foreach($consejos as $consejo) {
        if($consejo['categoria'] === 'Compra') {
            unset($consejo['categoria']);
            $arrayCompra[] = $consejo;
        }
        else if($consejo['categoria'] === 'Venta') {
            unset($consejo['categoria']);
            $arrayVenta[] = $consejo;
        }
        else if($consejo['categoria'] === 'Alquiler') {
            unset($consejo['categoria']);
            $arrayAlquiler[] = $consejo;
        }
    }

    $array['Compra'] = $arrayCompra;
    $array['Venta'] = $arrayVenta;
    $array['Alquiler'] = $arrayAlquiler;

    return $array;
}

function darSegunImportancia($consejos) {
    $array = [];

    $arrayBaja= [];
    $arrayMedia= [];
    $arrayAlta = [];

    

    foreach($consejos as $consejo) {
        if($consejo['importancia'] === 'Baja') {
            unset($consejo['importancia']);
            $arrayBaja[] = $consejo;
        }
        else if($consejo['importancia'] === 'Media') {
            unset($consejo['importancia']);
            $arrayMedia[] = $consejo;
        }
        else if($consejo['importancia'] === 'Alta') {
            unset($consejo['importancia']);
            $arrayAlta[] = $consejo;
        }
    }

    $array['Baja'] = $arrayBaja;
    $array['Media'] = $arrayMedia;
    $array['Alta'] = $arrayAlta;

    return $array;
}

?>
