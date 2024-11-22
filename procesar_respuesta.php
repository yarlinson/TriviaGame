<?php
session_start();
require 'conexion.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Obtiene el usuario y la respuesta seleccionada
$user_id = $_SESSION['user_id'];
$pregunta_id = $_POST['pregunta_id'];
$respuesta_id = $_POST['respuesta'];

// Verifica si la respuesta es correcta
$query = $conn->prepare("
    SELECT r.es_correcta 
    FROM respuestas r 
    WHERE r.id = :respuesta_id AND r.pregunta_id = :pregunta_id
");
$query->execute([
    ':respuesta_id' => $respuesta_id,
    ':pregunta_id' => $pregunta_id,
]);

$respuesta = $query->fetch(PDO::FETCH_ASSOC);

if ($respuesta && $respuesta['es_correcta']) {
    // Incrementa el puntaje del usuario si la respuesta es correcta
    $updatePuntaje = $conn->prepare("
        UPDATE usuarios 
        SET puntaje = puntaje + 1 
        WHERE id = :user_id
    ");
    $updatePuntaje->execute([':user_id' => $user_id]);

    $_SESSION['mensaje'] = "¡Respuesta correcta! Se ha sumado 1 punto a tu puntaje.";
} else {
    $_SESSION['mensaje'] = "Respuesta incorrecta. Inténtalo nuevamente.";
}

// Redirige al usuario a la página de contestar preguntas
header("Location: contestar_pregunta.php");
exit;
?>