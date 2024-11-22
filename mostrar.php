<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'DateTriviaJuegos';
$user = 'postgres';
$password = 'postgres';

try {
    // Conexión a la base de datos
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener todos los usuarios
    $query = "SELECT id, username, password,puntos FROM usuarios";
    $stmt = $conn->query($query);

    // Mostrar los resultados en una tabla
    echo "<h1>Lista de Usuarios</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>puntos</th>
            </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['puntos']}</td>
              </tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>