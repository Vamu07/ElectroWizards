<?php
session_start();
include_once "../config/conexion.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    if(empty($nombre) || empty($correo) || empty($contraseña)){
        echo "<script>alert('Todos los campos son obligatorios'); window.location.href='registro.html';</script>";
        exit;
    }

    // Insertar nuevo usuario con rol por defecto cliente
    $sql = "INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, 'cliente')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $correo, $contraseña);

    if($stmt->execute()){
        echo "<script>alert('Cuenta creada con éxito, ahora inicia sesión'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('El correo ya está registrado'); window.location.href='registro.html';</script>";
    }

    $stmt->close();
}
?>
