<?php
    function render_barras($ancho, $alto, $grosor_ejes, $salto_eje, $valor_eje) {
        // parametros
        /* $ancho = 350;
        $alto = 300;
        $grosor_ejes = 5;
        $salto_eje = 5;
        $valor_eje = 2; // valor que represento */

        // otras variables
        $tam_grafica = intval(4*$alto/5 - 10);
        $margen_titulo = 40;
        $base_plot = intval(4*$alto/5 - $grosor_ejes - 1 + $margen_titulo);
        $alto_plot = intval($valor_eje*$tam_grafica/(4*$salto_eje));


        // Crea una imagen
        $img = imagecreatetruecolor($ancho, $alto);
        
        imagealphablending($img, false); // evito que se mezclen los pixeles, quiero que solo se superponga al fondo
        imagesavealpha($img, true); // guardo el canal alfa

        // Define las variables que se van a emplear
        $white          = imagecolorallocatealpha($img, 0xFF, 0xFF, 0xFF, 0);
        $azul           = imagecolorallocatealpha($img, 0x13, 0x5A, 0xBA, 0);
        $black          = imagecolorallocatealpha($img, 0x00, 0x00, 0x00, 0);
        $transparente   = imagecolorallocatealpha($img, 0x00, 0x00, 0x00, 127);
        $fuente = __DIR__.'/../Comic_Relief/ComicRelief-Regular.ttf';

        // rellena la imagen de blanco
        imagefill($img, 0, 0, $transparente);

        // creo los bordes de la imagen
        imagerectangle($img, 0, 0, $ancho-1, $alto-1, $black); 
        imagerectangle($img, 5, 5, $ancho-6, $alto-6, $black);
        imagefilltoborder($img,  2, 2, $black, $black);
        
        // dibujo la grafica
        imagefilledrectangle($img, intval($ancho/5), $base_plot + $grosor_ejes + 1, intval($ancho/5 + $grosor_ejes), $base_plot - $tam_grafica, $black); // eje vertical
        imagefilledrectangle($img, intval($ancho/5), $base_plot + $grosor_ejes + 1, intval(4*$ancho/5), $base_plot + 1, $black); // eje horizontal
        imagefilledrectangle($img, intval(2*$ancho/5), $base_plot, intval(3*$ancho/5), $base_plot - $alto_plot, $azul); // plot

        imagefilledrectangle($img, intval($ancho/5 - $grosor_ejes*2), $base_plot - $tam_grafica/4, intval($ancho/5 + $grosor_ejes + $grosor_ejes*2), $base_plot - $tam_grafica/4 + 2, $black);
        imagettftext($img, 12.0, 0.0, intval($ancho/5 - $grosor_ejes*2 - 25) - 3, $base_plot - $tam_grafica/4 + 2, $black, $fuente, strval($salto_eje)); // marcas del eje

        imagefilledrectangle($img, intval($ancho/5 - $grosor_ejes*2), $base_plot - 2*$tam_grafica/4, intval($ancho/5 + $grosor_ejes + $grosor_ejes*2), $base_plot - 2*$tam_grafica/4 + 2, $black);
        imagettftext($img, 12.0, 0.0, intval($ancho/5 - $grosor_ejes*2 - 25) - 3, $base_plot - 2*$tam_grafica/4 + 2, $black, $fuente, strval($salto_eje*2)); // marcas del eje

        imagefilledrectangle($img, intval($ancho/5 - $grosor_ejes*2), $base_plot - 3*$tam_grafica/4, intval($ancho/5 + $grosor_ejes + $grosor_ejes*2), $base_plot - 3*$tam_grafica/4 + 2, $black);
        imagettftext($img, 12.0, 0.0, intval($ancho/5 - $grosor_ejes*2 - 25) - 3, $base_plot - 3*$tam_grafica/4 + 2, $black, $fuente, strval($salto_eje*3)); // marcas del eje

        imagefilledrectangle($img, intval($ancho/5 - $grosor_ejes*2), $base_plot - $tam_grafica, intval($ancho/5 + $grosor_ejes + $grosor_ejes*2), $base_plot - $tam_grafica + 2, $black);
        imagettftext($img, 12.0, 0.0, intval($ancho/5 - $grosor_ejes*2 - 25) - 3, $base_plot - $tam_grafica + 2, $black, $fuente, strval($salto_eje)*4); // marcas del eje


        
        imagettftext($img, 15.0, 0.0, $ancho/4, 40, $black, $fuente, 'Fotos últimos 7 días'); // titulo

        
        header('Content-Type: image/png');
        imagepng($img);
        imagedestroy($img);

    }

    $ancho = (int)($_GET['ancho']);
    $alto = (int)($_GET['alto']);
    $grosor_ejes = (int)($_GET['grosor_ejes']);
    $salto_eje = (int)($_GET['salto_eje']);
    $valor_eje = (int)($_GET['valor_eje']);

    render_barras($ancho, $alto, $grosor_ejes, $salto_eje, $valor_eje);
?>