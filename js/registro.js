"use strict";
let ESTILO_EMAIL;
window.addEventListener('load', function() {
    ESTILO_EMAIL = this.window.getComputedStyle(this.document.getElementById('email'));
});


function misubmit( evt ) {
    evt.preventDefault();
    
    let nombreValido = validarNombre();;
    let emailValido = validarEmail();;
    let passValida = validarPass();
    let repeatPassValida = validarRepeatPass();
    let campoNombre = document.getElementById('name');
    let campoEmail = document.getElementById('email');
    let campoPass = document.getElementById('password');
    let campoRepeatPass = document.getElementById('password2');
    campoNombre.style.animation = 'none';
    campoNombre.offsetHeight;
    campoEmail.style.animation = 'none';
    campoEmail.offsetHeight;
    campoPass.style.animation = 'none';
    campoPass.offsetHeight;
    campoRepeatPass.style.animation = 'none';
    campoRepeatPass.offsetHeight;

    if(emailValido && passValida && campoNombre && campoRepeatPass)
        document.getElementById("formularioRegistro").submit();
    else
        ventanaModal(!emailValido, !passValida, !nombreValido, !repeatPassValida);
}

function ventanaModal( email, pass, nombre, repeatPass ) {
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

    if(nombre)
        cosas_mal.push('nombre');

    if(email)
        cosas_mal.push('email');

    if(pass)
        cosas_mal.push('contraseña');

    if(repeatPass)
        cosas_mal.push('repetir contraseña');

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
        let campoNombre = document.getElementById('name');
        let campoRepeatPass = document.getElementById('password2');
        
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

        if(nombre) {
            campoNombre.style.borderColor = 'var(--colorBordeError)';
            campoNombre.style.backgroundColor = 'var(--colorFondoError)';
            campoNombre.style.animation = 'shake 0.5s';
        }

        if(repeatPass) {
            campoRepeatPass.style.borderColor = 'var(--colorBordeError)';
            campoRepeatPass.style.backgroundColor = 'var(--colorFondoError)';
            campoRepeatPass.style.animation = 'shake 0.5s';
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

function validarNombre() {
    let campoTexto = document.getElementById('name');
    let texto = campoTexto.value;
    let nombreValido = false;
    let mayusSi = false;
    let caracterInvalido = false; 
    let minusSi = false; 
    let numSi = false;

    if(texto.length >= 3 && texto.length <= 15) { // longitud correcta
        if(texto.charCodeAt(0) >= 48 && texto.charCodeAt(0) <= 57) { // si el texto empieza por un numero no es valido y no se hace nada mas
            nombreValido = false;
        }
        else {
            for(let i=0; i< texto.length; i++) {
                if(texto.charCodeAt(i) >= 65 && texto.charCodeAt(i) <= 90) // mayuscula
                    mayusSi = true;
                else if(texto.charCodeAt(i) >= 97 && texto.charCodeAt(i) <= 122) // minuscula
                    minusSi = true;
                else if(texto.charCodeAt(i) >= 48 && texto.charCodeAt(i) <= 57) // numeros
                    numSi = true;
                else
                    caracterInvalido = true;

                if(!caracterInvalido)
                    nombreValido = true;
            }
        }
    }

    return nombreValido;
}

function validarEmail() {
    let campoTexto = document.getElementById('email');
    let texto = campoTexto.value;
    let texto_dividido = texto.split('@');
    let emailValido = false;
    let parteLocal = -1;
    let dominio = -1;
    let caracterInvalido = false; 
    let minusSi = false; 
    let numSi = false;
    let signoSi = false; // esto no sirve de nada mas que para pasar al ultimo else
    let comprobacionPuntoFallida = false; // El punto no puede aparecer ni al principio ni al final y tampoco pueden aparecer dos o más puntos seguidos.
    let caracteres = '!#$%&*+-/=?^_`{|}~.' // falta ' se anyadira con otra consulta
    let caracteres_lista = caracteres.split('');
    let parteLocalValida = false;
    let dominioValido = false;
    let salir_bucle = false;

    if(texto.length != 0 && texto_dividido.length == 2) { // si sigue el formato a primera vista (length == 2) y no esta vacio (length == 0)
        parteLocal = texto_dividido[0];
        dominio = texto_dividido[1];
        if(texto.length < 254) { // si el correo no tiene mas de 254 caracteres
            if(parteLocal.length >= 1 && parteLocal.length <= 64 && dominio.length >= 1 && dominio.length <= 255) { // compruebo que el tamanyo de lo local y el dominio sean correctos
                for(let i=0; i< parteLocal.length; i++) { // recorro local y busco caracteres no validos
                    signoSi = false; // reinicio la comprobacion de simbolos validos

                    if(parteLocal.charCodeAt(i) >= 65 && parteLocal.charCodeAt(i) <= 90) // mayuscula
                        mayusSi = true;
                    else if(parteLocal.charCodeAt(i) >= 97 && parteLocal.charCodeAt(i) <= 122) // minuscula
                        minusSi = true;
                    else if(parteLocal.charCodeAt(i) >= 48 && parteLocal.charCodeAt(i) <= 57) // numeros
                        numSi = true;
                    else {
                        for(let j=0; j<caracteres_lista.length; j++) {
                            if(parteLocal[i] == caracteres_lista[j])  {
                                signoSi = true;
                            }
                        }
                        if(parteLocal.charCodeAt(i) == 39) // si el caracter es '
                            signoSi = true;
                        
                        if(!signoSi) // si el caracter introducido no coincide con nada hasta ahora es invalido
                            caracterInvalido = true;
                    }

                    if(i >= 1) { // para no salirme de rango, ya que compruebo el caracter anterior
                        if(parteLocal[i] == '.' && parteLocal[i-1] == '.') // si el caracter actual y el caracter anterior fueron un punto
                            comprobacionPuntoFallida = true;
                    }
                }
                if(parteLocal[0] == '.' || parteLocal[parteLocal.length-1] == '.') // si el punto aparece al principio o al final de la parte local
                    comprobacionPuntoFallida = true;

                if(!caracterInvalido && !comprobacionPuntoFallida)
                    parteLocalValida = true;
            }
            
            let lista_subdominios = dominio.split('.');
            let subdominio_actual;

            for(let i=0; i<lista_subdominios.length && !salir_bucle; i++) {
                subdominio_actual = lista_subdominios[i];

                if(lista_subdominios[i].length > 63) {
                    salir_bucle = true; // dominioValido si que es false, no hace falta seguir
                }

                if(lista_subdominios[i][0] == '-' || lista_subdominios[i][lista_subdominios[i].length - 1] == '-') {        
                    salir_bucle = true; // dominioValido si que es false, no hace falta seguir
                }
                
                for(let j=0; j<subdominio_actual.length && !salir_bucle; j++) {
                    if(subdominio_actual.charCodeAt(j) >= 65 && subdominio_actual.charCodeAt(j) <= 90) // mayuscula
                        mayusSi = true;
                    else if(subdominio_actual.charCodeAt(j) >= 97 && subdominio_actual.charCodeAt(j) <= 122) // minuscula
                        minusSi = true;
                    else if(subdominio_actual.charCodeAt(j) >= 48 && subdominio_actual.charCodeAt(j) <= 57) // numeros
                        numSi = true;
                    else if(subdominio_actual[j] == '-') // es el -
                        signoSi = true;
                    else {// es cualquier otro caracter
                        salir_bucle = true; // dominioValido si que es false, no hace falta seguir
                    }
                }
            }

        }
        if(!salir_bucle)
            dominioValido = true;

        if(dominioValido && parteLocalValida)
            emailValido = true;     
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

function validarRepeatPass() {
    let campoTexto_pass = document.getElementById('password');
    let texto_pass = campoTexto_pass.value;
    let campoTexto_pass2 = document.getElementById('password2');
    let texto_pass2 = campoTexto_pass2.value;
    let repeatPassValido = false;

    if(texto_pass == texto_pass2 && texto_pass2 != '')
        repeatPassValido = true;

    return repeatPassValido;
}