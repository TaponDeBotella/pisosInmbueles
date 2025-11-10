function cambiarTipoPrecio(tipoAnuncio) {
    let tipoPrecioElement = document.getElementById('tipoPrecio');
    if(tipoAnuncio === 'alquiler') 
        tipoPrecioElement.textContent = '€/mes';
    else if(tipoAnuncio === 'venta') 
        tipoPrecioElement.textContent = '€';
    else 
        tipoPrecioElement.textContent = '';
    
}