"use strict";
let ESTILO_EMAIL;
window.addEventListener('load', function() {
    this.document.getElementById('sex').selectedIndex = 0 
    ESTILO_EMAIL = this.window.getComputedStyle(this.document.getElementById('email'));
});


function misubmit( evt ) {
    evt.preventDefault();
    
    let validoEmail = validarEmail();
    let validoNombre = validarNombre();
    let validoPass = validarPass();
    let validoRepeatPass = validarRepeatPass();
    let validoSexo = validarSexo();
    let validoFecha = validarFechaNac();

    let nombreValido = validoNombre[0];
    let emailValido = validoEmail[0];
    let passValida = validoPass[0];
    let repeatPassValida = validoRepeatPass[0];
    let sexoValido = validoSexo[0];
    let edadValida = validoFecha[0];
    let campoNombre = document.getElementById('name');
    let campoEmail = document.getElementById('email');
    let campoPass = document.getElementById('password');
    let campoRepeatPass = document.getElementById('password2');
    let campoSexo = document.getElementById('sex');
    let campoFechaNac = document.getElementById('birth');
    
    campoNombre.style.animation = 'none';
    campoNombre.offsetHeight;
    campoEmail.style.animation = 'none';
    campoEmail.offsetHeight;
    campoPass.style.animation = 'none';
    campoPass.offsetHeight;
    campoRepeatPass.style.animation = 'none';
    campoRepeatPass.offsetHeight;
    campoSexo.style.animation = 'none';
    campoSexo.offsetHeight;
    campoFechaNac.style.animation = 'none';
    campoFechaNac.offsetHeight;

    let numError;

    if(validoNombre[1] != -1)
        numError = validoNombre[1];
    else if(validoPass[1] != -1)
        numError = validoPass[1];
    else if(validoRepeatPass[1] != -1)
        numError = validoRepeatPass[1]
    else if(validoEmail[1] != -1)
        numError = validoEmail[1];
    else if(validoSexo[1] != -1)
        numError = validoSexo[1];
    else if(validoFecha[1] != -1)
        numError = validoFecha[1];
    



    if(emailValido && passValida && nombreValido && repeatPassValida && sexoValido && edadValida)
        document.getElementById("formularioRegistro").submit();
    else
        ventanaModal(!emailValido, !passValida, !nombreValido, !repeatPassValida, !sexoValido, !edadValida, numError);
}

function ventanaModal( email, pass, nombre, repeatPass, sex, edad, numError ) {
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
    message.style.whiteSpace = 'pre-line';
    
    let cosas_mal = [];

    if(nombre)
        cosas_mal.push('nombre');

    if(email)
        cosas_mal.push('email');

    if(pass)
        cosas_mal.push('contraseña');

    if(repeatPass)
        cosas_mal.push('repetir contraseña');

    if(sex)
        cosas_mal.push('sexo');

    if(edad)
        cosas_mal.push('fecha de nacimiento');

    let texto_mensaje = `   No se puede dejar ningún campo en blanco. 
                        Esto incluye poner solo espacios o solo tabulaciones.\n
                        Campos erróneos: `
    
    for(let i=0; i<cosas_mal.length; i++) {
        if(i != cosas_mal.length-1)
            texto_mensaje += cosas_mal[i]+', ';
        else
            texto_mensaje += cosas_mal[i];
    }


    let texto_error;

    switch (numError) {
        case 1:
            texto_error = `   No se puede dejar ningún campo vacío`;
            break;

        // errores para el email
        case 2:
            texto_error = `   Formato inválido. \n 
                        El email debe tener el siguiente formato: parte-local@dominio `;
            break;
        case 3:
            texto_error = `   Formato inválido. \n 
                        La parte local del email no puede estar vacía: parte-local@dominio `;
            break;
        case 4:
            texto_error = `   Formato inválido. \n 
                        El dominio del email no puede estar vacío: parte-local@dominio `;
            break;
        case 5:
            texto_error= `   Formato inválido. \n 
                        La parte local del email es demasiado larga (más de 64 caracteres). \n`;
            break;
        case 6:
            texto_error = `   Formato inválido. \n 
                        El dominio del email es demasiado largo (más de 255 caracteres). \n`;
            break;
        case 7:
            texto_error = `   Formato inválido. \n 
                        Punto al principio de la parte local del email. `;
            break;
        case 8:
            texto_error = `   Formato inválido. \n 
                        Punto al final de la parte local del email. `;
            break;
        case 9:
            texto_error = `   Formato inválido. \n 
                        Dos puntos seguidos en la parte local del email. `;
            break;
        case 10:
            texto_error = `   Formato inválido. \n 
                        Caracteres no permitidos en la parte local del email. `;
            break;
        case 11:
            texto_error = `   Formato inválido. \n 
                        Subdominio del email demasiado largo (más de 63 caracteres). `;
            break;
        case 12:
            texto_error = `   Formato inválido. \n 
                        Guión al principio del subdominio del email. `;
            break;
        case 13:
            texto_error = `   Formato inválido. \n 
                        Guión al final del subdominio del email. `;
            break;
        case 14:
            texto_error = `   Formato inválido. \n 
                        Caracteres no permitidos en el subdominio del email. `;
            break;
        
        case 15:
            texto_error = `   Email demasiado largo. Máximo 254 caracteres.`;
            break;

        // errores nombre
        case 16:
            texto_error = `    Nombre demasiado corto. Mínimo 3 caracteres.`
            break;

        case 17:
            texto_error = `    Nombre demasiado largo. Máximo 15 caracteres.`
            break;

        case 18:
            texto_error = `    El nombre no puede empezar por un número`
            break;

        case 19:
            texto_error = `    Formato inválido. Caracteres no permitidos en el nombre`
            break;

        // errores pass
        case 20:
            texto_error = `    Contraseña demasiado corta.`
            break;

        case 21:
            texto_error = `    Contraseña demasiado larga.`
            break;

        case 22:
            texto_error = `    Formato inválido. Caracteres no permitidos en la contraseña.`
            break;

        case 23:
            texto_error = `    Formato inválido. La contraseña debe tener al menos una mayúscula.`
            break;

        case 24:
            texto_error = `    Formato inválido. La contraseña debe tener al menos una minúscula.`
            break;

        case 25:
            texto_error = `    Formato inválido. La contraseña debe tener al menos un número.`
            break;

        // repeat pass

        case 26:
            texto_error = `    Las contraseñas deben coincidir.`
            break;
        
        // fecha nacimiento
        case 27:
            texto_error = `    Error de formato. La fecha debe seguir el siguiente formato: día-mes-año`
            break;

        case 28:
            texto_error = `    Error de formato. Solo se admiten caracteres numéricos y el guión.`
            break;
        
        case 29:
            texto_error = `    Error de formato. Fecha incompleta.`
            break;

        case 30:
            texto_error = `    Fecha incorrecta. No existe ese día para ese mes y año.`
            break;

        case 31:
            texto_error = `    Fecha no válida. Debe ser mayor de edad.`
            break;
        
        default:
            texto_error = `    Error no recogido.`
            break;


    }

    texto_mensaje += `\n\n Uno de los errores: ${texto_error}`
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
        let campoSexo = document.getElementById('sex');
        let campoFechaNac = document.getElementById('birth');
        
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

        if(sex) {
            campoSexo.style.borderColor = 'var(--colorBordeError)';
            campoSexo.style.backgroundColor = 'var(--colorFondoError)';
            campoSexo.style.animation = 'shake 0.5s';
        }

        if(edad) {
            campoFechaNac.style.borderColor = 'var(--colorBordeError)';
            campoFechaNac.style.backgroundColor = 'var(--colorFondoError)';
            campoFechaNac.style.animation = 'shake 0.5s';
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
    let numError = -1;

    if(texto.length >= 3 && texto.length <= 15) { // longitud correcta
        if(texto.charCodeAt(0) >= 48 && texto.charCodeAt(0) <= 57) { // si el texto empieza por un numero no es valido y no se hace nada mas
            nombreValido = false;
            numError = 18;
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

                if(caracterInvalido) 
                    numError = 19;
                else
                    nombreValido = true;

            }
        }
    }
    else {
        if(texto.length < 3)
            numError = 16;
        else
            numError = 17;
    }

    let output = [nombreValido, numError];

    return output;
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
    let numError = -1;

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
                        
                        if(!signoSi) { // si el caracter introducido no coincide con nada hasta ahora es invalido
                            caracterInvalido = true;
                            numError = 10;
                        }
                    }

                    if(i >= 1) { // para no salirme de rango, ya que compruebo el caracter anterior
                        if(parteLocal[i] == '.' && parteLocal[i-1] == '.') {// si el caracter actual y el caracter anterior fueron un punto
                            comprobacionPuntoFallida = true;
                            numError = 9;
                        }
                    }
                }
                if(parteLocal[parteLocal.length-1] == '.') {// si el punto aparece al final de la parte local
                    comprobacionPuntoFallida = true;
                    numError = 8;
                }

                if(parteLocal[0] == '.') {// si el punto aparece al principio de la parte local
                    comprobacionPuntoFallida = true;
                    numError = 7;
                }

                if(!caracterInvalido && !comprobacionPuntoFallida)
                    parteLocalValida = true;
            }
            else {
                if(parteLocal.length < 1)
                    numError = 3;
                else if(parteLocal.length > 64)
                    numError = 5;
                else if(dominio.length < 1)
                    numError = 4;
                else if(dominio.length > 255)
                    numError = 6;
            }
            
            let lista_subdominios = dominio.split('.');
            let subdominio_actual;

            for(let i=0; i<lista_subdominios.length && !salir_bucle; i++) {
                subdominio_actual = lista_subdominios[i];

                if(lista_subdominios[i].length > 63) {
                    salir_bucle = true; // dominioValido si que es false, no hace falta seguir
                    numError = 11;
                }

                if(lista_subdominios[i][0] == '-') {  // gion al principio del subdominio       
                    salir_bucle = true; // dominioValido si que es false, no hace falta seguir
                    numError = 12;
                }

                if(lista_subdominios[i][lista_subdominios[i].length - 1] == '-') {     // guion al final del subdominio    
                    salir_bucle = true; // dominioValido si que es false, no hace falta seguir
                    numError = 13;
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
                        numError = 14;
                    }
                }
            }

        }
        else {
            numError = 15;
        }
        if(!salir_bucle)
            dominioValido = true;

        if(dominioValido && parteLocalValida)
            emailValido = true;     
    }
    else {
        if(texto.length == 0)
            numError = 1;
        else
            numError = 2;
    }

    let output = [emailValido, numError]

    return output;
}

function validarPass() { //////////// CAMBIAR EL IF if(!caracterInvalido && mayusSi && minusSi && numSi) 
    let campoTexto = document.getElementById('password');
    let texto = campoTexto.value;
    let mayusSi = false;
    let passValida = false;
    let caracterInvalido = false; 
    let minusSi = false; 
    let numSi = false;
    let signoSi = false; // esto no sirve de nada mas que para pasar al ultimo else
    let numError = -1;

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

            if(!caracterInvalido && mayusSi && minusSi && numSi) {
                if(caracterInvalido)
                    numError = 22;
                else if(!mayusSi)
                    numError = 23;
                else if(!minusSi)
                    numError = 24;
                else if(!numSi)
                    numError = 25;

                passValida = true;
            }
        }
    }
    else {
        if(texto.length < 6)
            numError = 20;
        else
            numError = 21;
    }

    let output = [passValida, numError];

    return output;
}

function validarRepeatPass() {
    let campoTexto_pass = document.getElementById('password');
    let texto_pass = campoTexto_pass.value;
    let campoTexto_pass2 = document.getElementById('password2');
    let texto_pass2 = campoTexto_pass2.value;
    let repeatPassValido = false;
    let numError = -1;

    if(texto_pass2 == '')
        numError = 1;
    else if(texto_pass != texto_pass2)
        numError = 26;
    else
        repeatPassValido = true;

    let output = [repeatPassValido, numError];

    return output;
}

function validarSexo() {
    let sexoValido = false;
    let numError = -1;

    if(document.getElementById('sex').selectedOptions[0].value != '')
        sexoValido = true;
    else
        numError = 1

    let output = [sexoValido, numError]

    return output;
}

function validarFechaNac() {
    let campo_texto = document.getElementById('birth');
    let texto = campo_texto.value;
    let fechaValida = false;
    let fechaDividida = texto.split('-')
    let diaValido = true;
    let mesValido = true;
    let anyoValido = true;
    let caracteresNumericos = true;
    let fechaCompleta = true;
    let fecha_actual = new Date();
    let numError = -1;

    if(fechaDividida.length == 3) {
        for(let i=0; i<fechaDividida.length; i++) {
            for(let j=0; j<fechaDividida[i].length; j++) {
                if(fechaDividida[i].charCodeAt(j) < 48 || fechaDividida[i].charCodeAt(j) > 57) {// caracter no numerico
                    caracteresNumericos = false;
                    numError = 28;
                }
            }
            
            if(fechaDividida[i].length == 0) {// dia, mes o anyo vacio
                fechaCompleta = false;
                numError = 29;
            }
        }

        if(caracteresNumericos && fechaCompleta) {   // si se han introducido solo numeros
            let dia = parseInt(fechaDividida[0]);
            let mes = parseInt(fechaDividida[1]);
            let anyo = parseInt(fechaDividida[2]);

            if(dia < 1)
                diaValido = false;

            if(mes < 1 || mes > 12)
                mesValido = false;

            if(anyo > fecha_actual.getFullYear())
                anyoValido = false;

            if(diaValido && mesValido && anyoValido) {
                if(mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12) {
                    if(dia > 31) {
                        diaValido = false;
                        numError = 30;
                    }
                }
                else if(mes == 4 || mes == 6 || mes == 9 || mes == 11) {
                    if(dia > 30) {
                        diaValido = false;
                        numError = 30;
                    }
                }
                else { // febrero
                    if(fecha_actual.getFullYear()%4 == 0) {// bisiesto
                        if(dia > 29) {
                            diaValido = false;
                            numError = 30;
                        }
                    }
                    else {
                        if(dia > 28) {
                            diaValido = false;
                            numError = 30;
                        }
                    }
                }
            }

            if(diaValido && mesValido && anyoValido) {// si hasta aqui la fecha es valida compruebo si es mayor de edad
                if(fecha_actual.getFullYear() - anyo > 18) // si hace mas de 18 anyos ya de diferencia no se comprueba nada mas
                    fechaValida = true;
                else if(fecha_actual.getFullYear() - anyo < 18) { // del mismo modo, si hace menos de 18 anyos desde esa fecha se sabe que es menor
                    fechaValida = false;
                    numError = 31;
                }
                else {
                    if(fecha_actual.getMonth() + 1 < mes) { // getmonth te lo devuelve considerando enero un 0. Si el mes actual es menor que el de la fecha (ya ha pasado) es mayor
                        fechaValida = false;
                        numError = 31;
                    }
                    else if(fecha_actual.getMonth() + 1 > mes) // si el mes es mayor, aun no ha llegado su cumpleanyos 18
                        fechaValida = true;
                    else {
                        if(fecha_actual.getDate() >= dia) // si estamos en el mismo mes pero su cumpleanyos fue antes en el mes u hoy, tiene 18
                            fechaValida = true;
                        else 
                            numError = 31;
                    }
                }
            }
        }
    }
    else {
        if(texto.length == 0)
            numError = 1;
        else
            numError = 27;
    }

    let output = [fechaValida, numError];

    return output;
}

function borrarVacio() {
    if(document.getElementById('vacio'))
        document.getElementById('vacio').remove();
}