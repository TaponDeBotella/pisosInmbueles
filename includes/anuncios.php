<?php
    // archivo con los datos de anuncios de prueba

    $anuncios = array(
        array(
            'idAnuncio' => 1,
            'tipoAnuncio' => 'alquiler',
            'tipoVivienda' => 'Habitacion',
            'fotos' => [['foto1.png', 'foto2.png', 'foto3.png', 'foto4.png'], ['Foto del piso', 'Foto del piso', 'Foto del piso', 'Foto del piso']],
            'titulo' => 'Piso en San Vicent del Raspeig cerca de la universidad',
            'precio' => 250, // si es un alquiler esto sera precio por mes, si es una venta sera el precio total
            'texto' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis enim vel est iaculis, quis pretium libero posuere. Integer vitae sapien mollis, condimentum nisl mollis, aliquet urna. Mauris auctor molestie mi quis efficitur. Sed nibh metus, pharetra ut feugiat nec, iaculis convallis diam. Vivamus laoreet risus porttitor posuere auctor. Etiam at sagittis lectus. In nec eros in magna varius aliquam vitae ac velit. Vivamus rhoncus nunc sed sem fringilla, eu sagittis nulla convallis. Pellentesque lobortis eros nec lacinia pellentesque. Vivamus non ipsum ut justo dapibus molestie. Praesent bibendum vitae quam ut condimentum. Nullam sed diam nec lectus vulputate aliquet et ut diam. Duis congue at dui id euismod. ',
            'fecha' => new DateTime('2025-11-09'),
            'ciudad' => 'San Vicent del Raspeig',
            'pais' => 'España',
            'caracteristicas' =>    array(
                                        'superficieVivienda' => 100, // en m^2
                                        'numHabitaciones' => 1, // si el tipo de anuncio es alquiler de una habitacion se pondra esto como 1, aunque hayan mas en la vivienda
                                        'numBanyo' => 2,
                                        'planta' => 1,
                                        'anyoConstruccion' => 1980
                                    ),
            'duenyo' => 'Usuario1'
        ),

        array(
            'idAnuncio' => 2,
            'tipoAnuncio' => 'venta',
            'tipoVivienda' => 'Casa',
            'fotos' => [['casa1.jpg', 'casa2.jpg', 'casa3.jpg', 'jardin1.jpg', 'piscina1.jpg'], ['Foto de la casa', 'Foto de la casa', 'Foto de la casa', 'Foto del jardin', 'Foto de la piscina']],
            'titulo' => 'Acogedor chalet con piscina en Mutxamel',
            'precio' => 450000, // precio total de venta
            'texto' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc facilisis enim vel est iaculis, quis pretium libero posuere. Integer vitae sapien mollis, condimentum nisl mollis, aliquet urna. Mauris auctor molestie mi quis efficitur. Sed nibh metus, pharetra ut feugiat nec, iaculis convallis diam. Vivamus laoreet risus porttitor posuere auctor. Etiam at sagittis lectus. In nec eros in magna varius aliquam vitae ac velit. Vivamus rhoncus nunc sed sem fringilla, eu sagittis nulla convallis. Pellentesque lobortis eros nec lacinia pellentesque. Vivamus non ipsum ut justo dapibus molestie. Praesent bibendum vitae quam ut condimentum. Nullam sed diam nec lectus vulputate aliquet et ut diam. Duis congue at dui id euismod.',
            'fecha' => new DateTime('2025-11-08'),
            'ciudad' => 'Mutxamel',
            'pais' => 'España',
            'caracteristicas' =>    array(
                                        'superficieVivienda' => 280, // en m^2
                                        'numHabitaciones' => 4,
                                        'numBanyo' => 3,
                                        'planta' => 2,
                                        'anyoConstruccion' => 2020
                                    ),
            'duenyo' => 'Usuario2'
        )
    );
?>