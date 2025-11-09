<?php
    session_start();
    
    $title="Accesibilidad de la Página";
    $acceder = "Acceder";
    $css="css/accesibilidad.css";
    include 'includes/header.php';
?>
        <h1><?php echo $title; ?></h1>
        <section id="explicacion">
            <h2>Descripción de la Accesibilidad</h2>
            <p>Esta página ha sido diseñada teniendo en cuenta la accesibilidad para todos los usuarios, incluyendo aquellos con discapacidades visuales. Se han implementado las siguientes características:</p>
            <ul>
                <li>Uso de colores con alto contraste para facilitar la lectura.</li>
                <li>Uso de textos con tamaño de fuente grande.</li>
                <li>Uso conjunto de letra grande y contraste alto.</li>
            </ul>
            <p>El estilo de alto contraste tiene las siguientes características:</p>
            <ul>
                <li>Fondo claro con texto oscuro para resaltar los textos.</li>
                <li>Colores de botones e inputs que destacan claramente del fondo.</li>
            </ul>
            <p>El estilo de tamaño de letra grande tiene las siguientes características:</p>
            <ul>
                <li>Tamaños de fuente aumentados para facilitar la lectura.</li>
            </ul>
            <p>El estilo combinado de letra grande y alto contraste incluye todas las características mencionadas anteriormente.</p>
            <p>El estilo modo noche tiene las siguientes características:</p>
            <ul>
                <li>Fondo oscuro con texto claro para reducir la fatiga visual.</li>
                <li>Colores de botones e inputs que destacan claramente del fondo oscuro con bordes morados y fondos un poco más claros.</li>
            </ul>

            <p>Por último, el estilo de imprenta permite poder imprimir en papel. Para ello tiene las siguientes características:</p>
            <ul>
                <li>Colores blancos, negros o grises.</li>
                <li>Se han eliminado elementos que no importan para la imprenta (botones, forms o navs)</li>
            </ul>

            <h2>Cómo Cambiar la Accesibilidad</h2>
            <p>Para cambiar la accesibilidad de la página, se puede seleccionar entre los diferentes estilos disponibles yendo al menú ver en el navegador y luego a Estilo de página. Estos estilos incluyen:</p>
            <ul>
                <li>Estilo predeterminado: El estilo original de la página.</li>
                <li>Estilo de tamaño grande: Aumenta el tamaño de la fuente para facilitar la lectura.</li>
                <li>Estilo de alto contraste: Mejora la visibilidad mediante colores con contraste.</li>
                <li>Modo noche: Usa colores oscuros.</li>
                <li>Estilo de tamaño grande y alto contraste: Combina ambos estilos para una mejor accesibilidad.</li>
                <li>Estilo de imprenta: Permite poder imprimir en papel.</li>
            </ul>
        </section>
<?php
    include 'includes/footer.php';
?>