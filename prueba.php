<?php

$texto = file_get_contents('prueba.experto'); // guardo en texto plano el contenido del documento
// Quitar BOM UTF-8 si existe
if (substr($texto, 0, 3) === "\xEF\xBB\xBF") {
    $texto = substr($texto, 3);
}
$lineas = preg_split('/\r\n|\r|\n/', $texto); // separo por saltos de linea

$regex = '/^<(?P<clave>[A-Za-z0-9\-]+)>\s*->\s*<<(?P<valor>[\p{Latin}\p{M}\p{N}\s\.\-\_\']+)>>$/um'; // () recoge valores dentro del regex, \s* dice que admite espacios ahi
$data = [];

for($i=0; $i<count($lineas); $i++) {
    $linea_actual = $lineas[$i];
    if($linea_actual !== '') { // si la linea no esta vacia
        if(preg_match($regex, $linea_actual, $valores))
            $data[$valores['clave']] = $valores['valor']; // anyado a data la clave leida con su valor asignado (data es un array asociativo)
    }
}

var_dump($data);

?>