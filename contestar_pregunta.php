<?php
session_start();
require 'conexion.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Obtén una pregunta aleatoria
$query = $conn->query("
    SELECT p.id AS pregunta_id, p.contenido AS pregunta 
    FROM preguntas p 
    ORDER BY RANDOM() LIMIT 1
");
$pregunta = $query->fetch(PDO::FETCH_ASSOC);

if (!$pregunta) {
    echo "No hay preguntas disponibles.";
    exit;
}

// Obtén las respuestas asociadas a la pregunta
$respuestasQuery = $conn->prepare("
    SELECT id, contenido 
    FROM respuestas 
    WHERE pregunta_id = :pregunta_id
");
$respuestasQuery->execute([':pregunta_id' => $pregunta['pregunta_id']]);
$respuestas = $respuestasQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contestar Pregunta</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles2.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Contestar Pregunta</h1>
        <form action="procesar_respuesta.php" method="POST">
            <h2 class="mb-4"><?php echo htmlspecialchars($pregunta['pregunta']); ?></h2>
            <?php foreach ($respuestas as $respuesta): ?>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="respuesta" id="respuesta<?php echo $respuesta['id']; ?>" value="<?php echo $respuesta['id']; ?>" required>
                    <label class="form-check-label" for="respuesta<?php echo $respuesta['id']; ?>">
                        <?php echo htmlspecialchars($respuesta['contenido']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="pregunta_id" value="<?php echo $pregunta['pregunta_id']; ?>">
            <button type="submit" class="btn btn-primary w-100 mt-3">Revisar Respuesta</button>
        </form>
        <div class="text-center mt-4">
            <a href="dashboard.php">Volver al inicio</a>
        </div>
        <?php if (isset($_SESSION['mensaje'])): ?>
            <p class="alert alert-info mt-3"><?php echo htmlspecialchars($_SESSION['mensaje']); ?></p>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
