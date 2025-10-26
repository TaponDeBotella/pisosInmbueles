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
    

    if(!emailValido){
        campoEmail.style.borderColor = 'var(--colorBordeError)';
        campoEmail.style.backgroundColor = 'var(--colorFondoError)';
        campoEmail.style.animation = 'shake 0.5s';
    }
    
    if(!passValida) {
        campoPass.style.borderColor = 'var(--colorBordeError)';
        campoPass.style.backgroundColor = 'var(--colorFondoError)';
        campoPass.style.animation = 'shake 0.5s';
    }

    if(emailValido && passValida)
        document.getElementById("formularioLogin").submit();
}

function restaurarEstilo (id) {
    document.getElementById(id).style = ESTILO_EMAIL;
    console.log('estilo restaurado');
}

function validarCampo(id) {
    let campoTexto = document.getElementById(id);
    let texto = campoTexto.value;
    let emailValido = false;

    if(texto.length != 0) {
        for(let i=0; i< texto.length; i++) {
            if(texto[i] != ' ' && texto[i] != '\t')
                emailValido = true;
        }
    }

    return emailValido;
}