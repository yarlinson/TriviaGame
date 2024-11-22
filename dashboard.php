<?php
session_start();
require 'conexion.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Obtiene el nombre del usuario desde la sesión
$username = $_SESSION['username'];

// Obtener ranking de usuarios basado en la tabla usuarios
$rankingStmt = $conn->query("SELECT username, puntaje FROM usuarios ORDER BY puntaje DESC");
$ranking = $rankingStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles2.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <header class="text-center">
            <h1 class="text-muted">Hola, <?php echo htmlspecialchars($username); ?>!</h1>
            <p class="lead">Bienvenido al panel de Trivia</p>
        </header>

        <!-- Opciones del usuario -->
        <div class="row my-4">
            <div class="col-md-6">
                <a href="crear_pregunta.php" class="btn btn-primary w-100">Crear Pregunta</a>
            </div>
            <div class="col-md-6">
                <a href="contestar_pregunta.php" class="btn btn-success w-100">Contestar Pregunta</a>
            </div>
        </div>

        <!-- Ranking -->
        <section class="my-5">
            <h2 class="text-secondary">Ranking</h2>
            <ul class="list-group">
                <?php foreach ($ranking as $user): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($user['username']); ?>
                        <span class="badge bg-primary rounded-pill">
                            <?php echo $user['puntaje']; ?> puntos
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Mensajes y Cerrar sesión -->
        <footer class="mt-4 text-center">
            <?php if (isset($_SESSION['mensaje'])): ?>
                <p class="alert alert-info">
                    <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
                </p>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
