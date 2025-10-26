"use strict";

function misubmit( evt ) {
    evt.preventDefault();
    
    let emailValido = validarEmail();;
    let passValida = validarPass();

    if(!emailValido)
        console.log('email invalido');
    else if(!passValida) 
        console.log('pass invalida')

}

function validarEmail() {
    let campoTexto = document.getElementById('email');
    let texto = campoTexto.value;
    let emailValido = false;

    if(texto.split('@').length === 2) {
        emailValido = true;
    }
    else {
        emailValido = false;
    }
    
    return emailValido;
}

function validarPass() {
    let campoTexto = document.getElementById('password');
    let texto = campoTexto.value;
    let mayusSi = false;
    let passValida = false;
    let caracterInvalido = false; 
    let minusSi = false; 
    let numSi = false;
    let signoSi = false; // esto no sirve de nada mas que para pasar al ultimo else

    if(texto.length >= 6 && texto.length <= 15) { // longitud correcta
        for(let i=0; i< texto.length; i++) {
            if(texto.charCodeAt(i) >= 65 && texto.charCodeAt(i) <= 90) // mayuscula
                mayusSi = true;
            else if(texto.charCodeAt(i) >= 97 && texto.charCodeAt(i) <= 122) // minuscula
                minusSi = true;
            else if(texto.charCodeAt(i) >= 48 && texto.charCodeAt(i) <= 57)
                numSi = true;
            else if(texto.charCodeAt(i) == 45 || texto.charCodeAt(i) == 95)
                signoSi = true;
            else
                caracterInvalido = true;

            if(!caracterInvalido && mayusSi && minusSi && numSi)
                passValida = true;
        }
    }

    return passValida;
}