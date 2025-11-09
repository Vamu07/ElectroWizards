<?php
session_start();
include_once __DIR__ . "/../config/conexion.php"; // conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir datos del formulario
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    // Verificar si los campos no están vacíos
    if (empty($correo) || empty($contraseña)) {
        echo "<script>alert('Por favor completa todos los campos.'); window.location.href='login.html';</script>";
        exit;
    }

    // Buscar el usuario por correo
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si existe el usuario
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña (sin encriptar ahora)
        if ($contraseña === $usuario['contraseña']) {

            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirección según el rol
            if ($usuario['rol'] === 'admin') {
                header("Location: ../dashboard_admin/dashboard_admin.php");
                exit;
            } else {
                header("Location: ../dashboard_cliente/dashboard_cliente.php");
                exit;
            }

        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='login.html';</script>";
    }

    $stmt->close();
}
?>
