function cambiarTipoPrecio(tipoAnuncio) {
    let tipoPrecioElement = document.getElementById('tipoPrecio');
    if(tipoAnuncio === '2') 
        tipoPrecioElement.textContent = '€/mes';
    else if(tipoAnuncio === '1') 
        tipoPrecioElement.textContent = '€';
    else 
        tipoPrecioElement.textContent = '';
    
}