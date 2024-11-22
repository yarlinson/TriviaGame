document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("login-form");
    const gameSection = document.getElementById("game-section");
    const loginSection = document.getElementById("login-section");
    const welcomeMessage = document.getElementById("welcome-message");

    loginForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        fetch("loginUsuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ username, password }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    loginSection.style.display = "none";
                    gameSection.style.display = "block";
                    welcomeMessage.textContent = `¡Bienvenido, ${data.username}!`;
                } else {
                    alert(data.error);
                }
            });
    });
});

document.getElementById('agregarRespuesta').addEventListener('click', function () {
    var respuestas = document.querySelectorAll('#respuestas .respuesta');
    if (respuestas.length < 5) { 
        var nuevaRespuesta = document.createElement('div');
        nuevaRespuesta.classList.add('respuesta');
        nuevaRespuesta.innerHTML = '<input class="form-control" type="text" name="respuestas[]" placeholder="Respuesta ' + (respuestas.length + 1) + '" required>';
        document.getElementById('respuestas').appendChild(nuevaRespuesta);

        actualizarRespuestaCorrecta();

        document.getElementById('eliminarRespuesta').disabled = false;
    }
});

// Función para eliminar una respuesta
document.getElementById('eliminarRespuesta').addEventListener('click', function () {
    var respuestas = document.querySelectorAll('#respuestas .respuesta');
    if (respuestas.length > 2) { // Evitar eliminar hasta que haya 2 respuestas
        var lastRespuesta = respuestas[respuestas.length - 1];
        lastRespuesta.remove();

        // Actualizar las opciones del select de respuesta correcta
        actualizarRespuestaCorrecta();

        // Deshabilitar el botón de eliminar si ya no hay más respuestas para eliminar
        if (respuestas.length <= 2) {
            document.getElementById('eliminarRespuesta').disabled = true;
        }
    }
});

// Función para actualizar las opciones de respuesta correcta
function actualizarRespuestaCorrecta() {
    var respuestas = document.querySelectorAll('#respuestas .respuesta');
    var select = document.getElementById('respuestaCorrecta');

    // Limpiar las opciones actuales
    select.innerHTML = '';

    // Crear nuevas opciones basadas en la cantidad de respuestas
    for (var i = 0; i < respuestas.length; i++) {
        var option = document.createElement('option');
        option.value = i;
        option.textContent = 'Respuesta ' + (i + 1);
        select.appendChild(option);
    }
}

