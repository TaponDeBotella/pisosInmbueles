"use strict";

// estilos que hay en todas las celdas
const estiloEncabezado = { // los encabezdos
    border: '1px solid',
    padding: '10px',
    textAlign: 'center',
    backgroundColor: 'var(--colorCeldaOscura)',
    fontWeight: '600'
};
const estiloGeneral = { // las otras celdas
    border: '1px solid',
    padding: '10px',
    textAlign: 'center',
    backgroundColor: 'var(--colorCeldaClara)'
};

// crea la celda pasandole por referencia los estilos, atributos, clases, etc.
function nuevaCelda(celda, texto, { styles, colSpan } = {}) { // IMPORTANTE pasarle la referencia asi porque sin no no va los estilos
    const celdaNueva = document.createElement(celda); // se consigue la celda que hay que crear
    celdaNueva.textContent = texto; // se le asigna el texto
    
    if (colSpan) celdaNueva.colSpan = colSpan; // como hay encabezados con 2 span de columna primero hay que comprobar si hay que crear una de esas columnas o no para ponerle el span que se necesita
    Object.assign(celdaNueva.style, styles); // aqui se le meten los estilos que se han pasado por referencia

    return celdaNueva;
}

// funcoin que crea los encabezados
function nuevoTh(text, colSpan) {
    return nuevaCelda('th', text, { styles: estiloEncabezado, colSpan });
}

// funcion que crea las celdas normales
function nuevoTd(text) {
    return nuevaCelda('td', text, { styles: estiloGeneral });
}

// funcion para calcular el precio por cada pagina
function costePorPaginas(paginas) {
    let precio = 0;

    if(paginas < 5) { // si hay menos de 5 paginas se cobra 0.2 por cada una
        precio = paginas * 2.0;
    } else if(paginas <= 10) { // si hay entre 5 y 10 paginas se cobra 1.8 por cada una
        precio = paginas * 1.8;
    } else { // si hay mas de 10 paginas se cobra 1.6 por cada una
        precio = paginas * 1.6;
    }

    return precio;
}

// funcion para calcular el precio total del envio
function calcularPrecio(paginas, fotos, color, dpi) {
    const precioEnvio = 10.0;
    const precioPag = costePorPaginas(paginas); // se saca el precio de las paginas con la funcion
    let precioResolucion, precioFotoColor, precioFotos;

    if(color) { // si la foto es a color entonces cada una vale 0.5 y si no entonces es 0
        precioFotoColor = 0.5;
    } else {
        precioFotoColor = 0.0;
    }

    if (dpi > 300) { // si la resolucion es mayor a 300 entonces vale cada una 0.2, si no es 0
        precioResolucion = 0.2;
    } else {
        precioResolucion = 0.0;
    }

    precioFotos = fotos * (precioFotoColor + precioResolucion); // se calcula el precio de las fotos

    return precioEnvio + precioPag + precioFotos; // se devuelve el precio total sumandole el envio, las paginas y las fotos
}

// funcion que crea toda la vetana modal de la tabla de los precios
function verPrecios() {
    if (document.getElementById('fondo')) return; // para que no se pueda crear mas de una tabla porque si no hay que cerrar todas las que se creen una a una 

    const fondo = document.createElement('section'); // el fondo lightgrey que hay detras de la tabla
    fondo.id = 'fondo';

    Object.assign(fondo.style, {
        position: 'fixed',
        inset: '5vh 5vw', // para que la tabla no ocupe toda la pantalla
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '20px',
        boxSizing: 'border-box',
        borderRadius: '10px',
        boxShadow: 'var(--sombra)',
        overflow: 'auto', // esto es para el scroll
        backgroundColor: 'var(--colorFondoClaro)'
    });

    const tabla = document.createElement('table'); // la tabla de los precios

    Object.assign(tabla.style, {
        borderCollapse: 'collapse',
        alignItems: 'center',
        justifyContent: 'center',   // NO VAAAAAAAAAAAAAAA
        width: '90%',
        border: '3px solid var(--colorBordeBoton)', 
        display: 'block', // vale no va lo de poner en el centro por esto, pero es que si lo quito entonces no se ve bien la tabla y ya no puedo pensar mas SOCORRO
        tableLayout: 'fixed',
        boxSizing: 'border-box',
        maxHeight: 'calc(100% - 80px)', // esto es para que no se salga de la pantalla (lo he tenido que preguntar porque ni ieda de como se hacia la verdad)
        overflow: 'auto'
    });

    // aqui se crean los encabezados (los que llevan la celda con el color mas oscurito)
    const trEnc1 = document.createElement('tr');
    trEnc1.appendChild(nuevoTh('', 0)); // esta celda y la de abajo no tienen texto porque en la tabla de ejemplo lo de color y blanco y negro se ponen en una fila mas arriba
    trEnc1.appendChild(nuevoTh('', 0));
    trEnc1.appendChild(nuevoTh('Blanco y Negro', 2)); // ocupan 2 columnas asi que es span 2
    trEnc1.appendChild(nuevoTh('Color', 2)); // lo mismo que el blanco y negro
    tabla.appendChild(trEnc1);

    const trEnc2 = document.createElement('tr'); // en esta fila si que van nombres en todas las celdas
    trEnc2.appendChild(nuevoTh('Número de páginas'));
    trEnc2.appendChild(nuevoTh('Número de fotos'));
    trEnc2.appendChild(nuevoTh('150-300dpi'));
    trEnc2.appendChild(nuevoTh('450-900dpi'));
    trEnc2.appendChild(nuevoTh('150-300dpi'));
    trEnc2.appendChild(nuevoTh('450-900dpi'));
    tabla.appendChild(trEnc2);

    // las filas de los datos normales
    for (let i = 0; i < 15; i++) {
        const paginas = i + 1;
        const fotos = (i * 3) + 3;
        const tr = document.createElement('tr');

        tr.appendChild(nuevoTd(String(paginas))); // columna de las paginas
        tr.appendChild(nuevoTd(String(fotos))); // columna de las fotos
        tr.appendChild(nuevoTd(calcularPrecio(paginas, fotos, false, 300).toFixed(2) + ' €')); // bn y 150-300  el toFixed es para que el numero pase a string y con 2 decimales
        tr.appendChild(nuevoTd(calcularPrecio(paginas, fotos, false, 450).toFixed(2) + ' €')); // bn y 450-900
        tr.appendChild(nuevoTd(calcularPrecio(paginas, fotos, true, 300).toFixed(2) + ' €')); // color y 150-300
        tr.appendChild(nuevoTd(calcularPrecio(paginas, fotos, true, 450).toFixed(2) + ' €')); // color y 450-900

        tabla.appendChild(tr);
    }

    const botonCerrarTabla = document.createElement('button');
    botonCerrarTabla.type = 'button';
    botonCerrarTabla.textContent = 'X';
    botonCerrarTabla.classList.add('boton');
    Object.assign(botonCerrarTabla.style, { position: 'absolute', top: '20px', right: '20px' });

    fondo.appendChild(tabla);
    fondo.appendChild(botonCerrarTabla);

    const sePuedeMover = document.documentElement.style.overflow; // me lo guardo para poder resetearlo despues
    document.documentElement.style.overflow = 'hidden'; // para que no se pueda hacer scroll en el fondo cuando la tabla esta abierta

    function cerrarTabla() {
        if (fondo.parentNode) document.body.removeChild(fondo); // se borra todo de la tabla y el fondo
        document.documentElement.style.overflow = sePuedeMover; // se restaura el scroll de la pagina del folleto
    }

    botonCerrarTabla.addEventListener('click', cerrarTabla);

    document.body.appendChild(fondo); // se anyade todo
}