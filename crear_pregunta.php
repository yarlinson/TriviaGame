<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Obtener el nombre del usuario desde la sesión
$nombre_usuario = $_SESSION['username']; // Asegúrate de que el nombre del usuario se almacene en la sesión

$mensaje = '';  // Variable para almacenar el mensaje de éxito o error

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Normalizar el texto de la pregunta para evitar duplicados en diferentes formatos
        $texto_pregunta = strtolower(trim($_POST['pregunta']));

        // Verificar si la pregunta ya existe (ignorando mayúsculas/minúsculas)
        $stmt = $conn->prepare("SELECT * FROM preguntas WHERE LOWER(texto) = :texto");
        $stmt->bindParam(':texto', $texto_pregunta);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            // Insertar la nueva pregunta si no existe
            $insertStmt = $conn->prepare("INSERT INTO preguntas (texto) VALUES (:texto)");
            $insertStmt->bindParam(':texto', $texto_pregunta);
            $insertStmt->execute();
            $pregunta_id = $conn->lastInsertId();

            // Insertar las respuestas
            foreach ($_POST['respuestas'] as $index => $respuesta) {
                $es_correcta = ($index == $_POST['respuesta_correcta']) ? 1 : 0;
                $insertRespStmt = $conn->prepare("INSERT INTO respuestas (pregunta_id, texto, es_correcta) VALUES (:pregunta_id, :texto, :es_correcta)");
                $insertRespStmt->bindParam(':pregunta_id', $pregunta_id);
                $insertRespStmt->bindParam(':texto', $respuesta);
                $insertRespStmt->bindParam(':es_correcta', $es_correcta);
                $insertRespStmt->execute();
            }

            $mensaje = "<p style='color: green;'>Pregunta y respuestas creadas exitosamente</p>";
        } else {
            $mensaje = "<p style='color: red;'>Esta pregunta ya existe</p>";
        }
    } catch (Exception $e) {
        $mensaje = "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pregunta</title>
    <link rel="stylesheet" href="./css/styles2.css">
    <style>/* Navbar estilo */
.navbar {
    background-color: #4a4e69; /* Tono pastel oscuro */
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.navbar h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #f2e9e4; /* Tono pastel claro */
}

.navbar button {
    background-color: #c9ada7; /* Botón en color pastel suave */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.navbar button:hover {
    background-color: #9a8c98; /* Tono más oscuro en hover */
}

.navbar .logout {
    background-color: #a2d2ff; /* Azul pastel */
}

.navbar .logout:hover {
    background-color: #82c2f7; /* Azul pastel más oscuro */
}

/* Ajustes para el contenido de la página debajo del navbar */
.container {
    width: 80%;
    margin: 100px auto 20px; /* Ajuste superior para compensar el navbar */
    padding: 20px;
    background-color: #f2e9e4; /* Fondo claro pastel */
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

/* Títulos */
h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #22223b; /* Color de texto oscuro */
}

/* Campos de formulario */
.form-control {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

/* Agrupación de formularios */
.form-group {
    margin-bottom: 20px;
}

/* Respuestas */
.respuesta {
    margin-bottom: 10px;
    font-size: 1rem;
}

/* Estilo para la fila de botones y select */
#opcionesFila {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr 2fr 1fr;
    align-items: center;
    gap: 10px;
}

/* Botones */
#agregarRespuesta,
#eliminarRespuesta,
#guardarBtn {
    padding: 10px 15px;
    font-size: 1rem;
    background-color: #6d6875; /* Color pastel oscuro */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#agregarRespuesta:hover,
#eliminarRespuesta:hover,
#guardarBtn:hover {
    background-color: #5c5565; /* Hover más oscuro */
}

/* Select de respuesta correcta */
#respuestaCorrecta {
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #fff; /* Fondo blanco para contraste */
}

/* Contenedor de respuestas */
#respuestas {
    margin-top: 20px;
}

/* Mensaje */
#mensaje {
    text-align: center;
    margin-top: 20px;
    font-size: 1rem;
    font-weight: bold;
    color: #6d6875; /* Color pastel oscuro */
}

/* Botones generales */
.btn {
    background-color: #a2d2ff; /* Azul pastel */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #82c2f7; /* Azul pastel más oscuro */
}

/* Respuestas con margen */
.respuesta input {
    margin-bottom: 10px;
}

    </style>
</head>

<body>
    <!-- Navbar con el nombre del usuario, el botón de volver y el botón de logout -->
    <div class="navbar">
        <h2>Creando pregunta con <?php echo $nombre_usuario; ?></h2>
        <div>
            <button onclick="window.location.href='dashboard.php'">Volver al Dashboard</button>
            <button class="logout" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
        </div>
    </div>

    <div class="container">
        <h1>Crear Nueva Pregunta</h1>
        <form method="POST" id="formPregunta">
            <div class="form-group">
                <label for="pregunta">Pregunta:</label>
                <input class="form-control" type="text" name="pregunta" id="pregunta" placeholder="Escribe tu pregunta"
                    required>
            </div>

            <!-- Botones para agregar y eliminar respuestas, y seleccionar la respuesta correcta en una sola fila -->
            <div class="form-group" id="opcionesFila">
                <button type="button" id="agregarRespuesta" class="btn">+</button>
                <button type="button" id="eliminarRespuesta" class="btn" disabled>-</button>
                <label for="respuestaCorrecta" style=" text-align:right"> Respuesta Correcta:</label>
                <select name="respuesta_correcta" id="respuestaCorrecta" class="form-control" required>
                    <option value="0">Respuesta 1</option>
                    <option value="1">Respuesta 2</option>
                </select>

                <!-- Botón de Guardar en la misma línea -->
                <button type="submit" class="btn" id="guardarBtn">Guardar</button>
            </div>

            <!-- Campos para respuestas -->
            <div class="form-group">
                <h3>Respuestas:</h3>
                <div id="respuestas">
                    <div class="respuesta">
                        <input class="form-control" type="text" name="respuestas[]" placeholder="Respuesta 1" required>
                    </div>
                    <div class="respuesta">
                        <input class="form-control" type="text" name="respuestas[]" placeholder="Respuesta 2" required>
                    </div>
                </div>
            </div>
            <div id="mensaje"><?php echo $mensaje; ?></div>
        </form>



    </div>

    <script>
        // Funcionalidad para agregar más respuestas dinámicamente
        document.getElementById('agregarRespuesta').addEventListener('click', function () {
            var respuestas = document.querySelectorAll('#respuestas .respuesta');
            if (respuestas.length < 5) { // Limitar a un máximo de 5 respuestas
                var nuevaRespuesta = document.createElement('div');
                nuevaRespuesta.classList.add('respuesta');
                nuevaRespuesta.innerHTML = '<input class="form-control" type="text" name="respuestas[]" placeholder="Respuesta ' + (respuestas.length + 1) + '" required>';
                document.getElementById('respuestas').appendChild(nuevaRespuesta);

                // Actualizar las opciones del select de respuesta correcta
                actualizarRespuestaCorrecta();

                // Habilitar el botón de eliminar ya que ahora se puede eliminar
                document.getElementById('eliminarRespuesta').disabled = false;
            }
        });

        // Funcionalidad para eliminar respuestas dinámicamente
        document.getElementById('eliminarRespuesta').addEventListener('click', function () {
            var respuestas = document.querySelectorAll('#respuestas .respuesta');
            if (respuestas.length > 2) { // No eliminar más de 2 respuestas
                respuestas[respuestas.length - 1].remove();

                // Actualizar las opciones del select de respuesta correcta
                actualizarRespuestaCorrecta();
            }

            // Deshabilitar el botón de eliminar si ya no hay respuestas
            if (document.querySelectorAll('#respuestas .respuesta').length <= 2) {
                document.getElementById('eliminarRespuesta').disabled = true;
            }
        });

        // Actualizar las opciones del select de respuesta correcta
        function actualizarRespuestaCorrecta() {
            var respuestas = document.querySelectorAll('#respuestas .respuesta');
            var select = document.getElementById('respuestaCorrecta');

            // Limpiar las opciones del select
            select.innerHTML = '';

            // Agregar opciones según el número de respuestas
            respuestas.forEach(function (respuesta, index) {
                var option = document.createElement('option');
                option.value = index;
                option.textContent = 'Respuesta ' + (index + 1);
                select.appendChild(option);
            });
        }
    </script>
</body>

</html>