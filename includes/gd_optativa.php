<?php
    $nombre_archivo = '';

    if(isset($_GET['f']))
        $nombre_archivo = urldecode($_GET['f']);

    if (!$nombre_archivo) { http_response_code(400); exit; } // si no ha recibido una ruta  no hace nada

    $path = __DIR__.'/..'.DIRECTORY_SEPARATOR.$nombre_archivo; // DIRECTORY_SEPARATOR es / o \ dependiendo del SO

    if (!is_file($path)) { http_response_code(404); exit; } // no se encuentra la imagen

    $data = file_get_contents($path);
    if ($data === false) { http_response_code(500); exit; } // fallo en el servidor

    $img = @imagecreatefromstring($data); // la @ es pa que no de warnings, que se cargan la salida binaria
    if ($img === false) { http_response_code(415); exit; } // formato incorrecto (no la imagen no esta en uno de los formatos aceptados por imagecreatefromstring)


    $ancho = imagesx($img);
    $alto = imagesy($img);


    $img = imagescale($img, $ancho/4, $alto/4); // reescalo
    
    header('Content-Type: image/png');
    imagepng($img);
    imagedestroy($img);
?>