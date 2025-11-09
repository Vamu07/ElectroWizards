<?php
include __DIR__ . "/../../config/conexion.php";

$id = intval($_POST['id_producto']);
$nombre = trim($_POST['nombre']);
$precio = floatval($_POST['precio']);
$stock = intval($_POST['stock']);
$descripcion = trim($_POST['descripcion']);

$stmt = $conexion->prepare("UPDATE productos SET nombre_producto=?, precio=?, stock=?, descripcion=? WHERE id_producto=?");
$stmt->bind_param("sdisi", $nombre, $precio, $stock, $descripcion, $id);

if($stmt->execute()){
    echo "✅ Producto actualizado correctamente";
} else {
    echo "❌ Error al actualizar el producto";
}
$stmt->close();
?>
