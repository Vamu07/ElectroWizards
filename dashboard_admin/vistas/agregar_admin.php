<?php
// seguridad: solo admin
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../login/login.html");
    exit;
}

include __DIR__ . "/../../config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    if ($nombre && $correo && $contraseña) {
        $rol = 'admin';
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $correo, $contraseña, $rol);

        if ($stmt->execute()) {
            $mensaje = "<div class='p-3 bg-green-100 text-green-700 rounded'>✅ Administrador agregado correctamente</div>";
        } else {
            $mensaje = "<div class='p-3 bg-red-100 text-red-700 rounded'>❌ Error al agregar administrador: ".$stmt->error."</div>";
        }
    } else {
        $mensaje = "<div class='p-3 bg-red-100 text-red-700 rounded'>⚠️ Todos los campos son obligatorios</div>";
    }
}
?>

<div class="bg-white rounded-xl shadow-lg p-6">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-6">👑 Agregar Nuevo Administrador</h3>

  <?php echo $mensaje; ?>

  <form method="POST" class="space-y-4 max-w-lg">
    <div>
      <label class="block text-sm font-medium mb-1">Nombre</label>
      <input type="text" name="nombre" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-[#00e0ff]">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Correo</label>
      <input type="email" name="correo" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-[#00e0ff]">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Contraseña</label>
      <input type="password" name="contraseña" required class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-[#00e0ff]">
    </div>

    <button type="submit" class="btn-electric px-6 py-2 rounded w-full">Guardar Administrador</button>
  </form>
</div>
