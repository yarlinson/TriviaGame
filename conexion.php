<?php
// Datos de conexión
$host = 'localhost';
$dbname = 'DateTriviaJuegos';
$user = 'postgres';
$password = '15102003';

try {
    // Crear conexión PDO
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    // Configurar el modo de error de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Manejo de errores
    die("Error en la conexión: " . $e->getMessage());
}
?>