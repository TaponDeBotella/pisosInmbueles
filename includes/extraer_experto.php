<?php

function extraer_experto(string $nombre_archivo):array {
    $texto = file_get_contents($nombre_archivo); // guardo en texto plano el contenido del documento
    // Quitar BOM UTF-8 si existe
    if (substr($texto, 0, 3) === "\xEF\xBB\xBF") {
        $texto = substr($texto, 3);
    }
    preg_match_all('/\{(.*?)\}/s', $texto, $entradas); // separo por {} para delimitar las entradas
    
    
    
    $regex = '/^<(?P<clave>[A-Za-z0-9\-]+)>\s*->\s*<<(?P<valor>[\p{Latin}\p{M}\p{N}\s\.\-\_\'\,]+)>>$/um'; // () recoge valores dentro del regex, \s* dice que admite espacios ahi
    $data = [];
    

    for($i=0; $i<count($entradas[1]); $i++) { // para cada entrada hecha en el archivo
        $lineas = preg_split('/\r\n|\r|\n/', $entradas[1][$i]); // separo por saltos de linea el contenido de la entrada $i
        for($j=0; $j<count($lineas); $j++) { // recorro para cada linea
            $linea_actual = $lineas[$j];
            if($linea_actual !== '') { // si la linea no esta vacia
                if(preg_match($regex, $linea_actual, $valores))
                    $data[$i][$valores['clave']] = $valores['valor']; // anyado a data la clave leida con su valor asignado (data es un array asociativo), el primer indice del array delimita el numero de entrada [$i]
            }
        }
    }


    return $data;
}



?>