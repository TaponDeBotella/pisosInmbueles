"use strict";
let ESTILO_EMAIL;
window.addEventListener('load', function() {
    ESTILO_EMAIL = this.window.getComputedStyle(this.document.getElementById('email'));
});


function misubmit( evt ) {
    evt.preventDefault();
    
    let emailValido = validarCampo('email');;
    let passValida = validarCampo('password');
    let campoEmail = document.getElementById('email');
    let campoPass = document.getElementById('password');
    campoEmail.style.animation = 'none';
    campoEmail.offsetHeight;
    campoPass.style.animation = 'none';
    campoPass.offsetHeight;

    if(emailValido && passValida)
        document.getElementById("formularioLogin").submit();
    else
        ventanaModal(!emailValido, !passValida);
}

function ventanaModal( email, pass ) {
    let modal = document.createElement('section');
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    modal.style.display = 'flex';        
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.zIndex = '1000';

    let modalContent = document.createElement('section');
    modalContent.style.backgroundColor = 'white';
    modalContent.style.padding = '20px';
    modalContent.style.borderRadius = '5px';
    modalContent.style.textAlign = 'center';

    let message = document.createElement('p');
    
    let cosas_mal = [];

    if(email)
        cosas_mal.push('email');

    if(pass)
        cosas_mal.push('contraseña');

    let texto_mensaje = `   No se puede dejar ningún campo en blanco. \n 
                        Esto incluye poner solo espacios o solo tabulaciones.
                        Campos erróneos: `
    
    for(let i=0; i<cosas_mal.length; i++) {
        if(i != cosas_mal.length-1)
            texto_mensaje += cosas_mal[i]+', ';
        else
            texto_mensaje += cosas_mal[i];
    }

    message.textContent = texto_mensaje;



    let closeButton = document.createElement('button');
    closeButton.textContent = 'Cerrar';
    closeButton.style.marginTop = '10px';
    closeButton.onclick = function () {

        document.body.removeChild(modal); // Eliminar la ventana modal

        let campoEmail = document.getElementById('email');
        let campoPass = document.getElementById('password');
        
        if(email){
            campoEmail.style.borderColor = 'var(--colorBordeError)';
            campoEmail.style.backgroundColor = 'var(--colorFondoError)';
            campoEmail.style.animation = 'shake 0.5s';
        }
        
        if(pass) {
            campoPass.style.borderColor = 'var(--colorBordeError)';
            campoPass.style.backgroundColor = 'var(--colorFondoError)';
            campoPass.style.animation = 'shake 0.5s';
        }
    };
    
    modalContent.appendChild(message);
    modalContent.appendChild(closeButton);
    modal.appendChild(modalContent);
    document.body.appendChild(modal); // Agregar la ventana modal al documento 
}

function restaurarEstilo (id) {
    document.getElementById(id).style = ESTILO_EMAIL;
    console.log('estilo restaurado');
}

function validarCampo(id) {
    let campoTexto = document.getElementById(id);
    let texto = campoTexto.value;
    let campoValido = false;

    if(texto.length != 0) {
        for(let i=0; i< texto.length; i++) {
            if(texto[i] != ' ' && texto[i] != '\t')
                campoValido = true;
        }
    }

    return campoValido;
}