<?php
session_start();
require 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Contraseña ingresada: " . $password . "<br>";
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            echo "Hash " . $hashed_password . "<br>";
            echo "Hash almacenado: " . $user['password'] . "<br>";
            echo "Usuario o contraseña incorrectos";
        }
    } else {
        echo "Usuario no encontrado";
    }
}
?>