<?php
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
  header("Location: ../../login/login.html");
  exit;
}

include __DIR__ . "/../../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];
$errores = [];
$mensaje = "";

// 🔹 ACTUALIZAR DATOS GENERALES
if (isset($_POST['actualizar_perfil'])) {
  $nombre = trim($_POST['nombre']);
  $direccion = trim($_POST['direccion']);
  $telefono = trim($_POST['telefono']);
  $correo = trim($_POST['correo']);

  // 📸 Imagen de perfil
  $imagen_perfil = null;
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagen'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $permitidos = ['jpg','jpeg','png','webp','gif'];

    if (in_array($ext, $permitidos)) {
      $nombre_img = uniqid("perfil_") . "." . $ext;
      $ruta = __DIR__ . "/../imagenes_perfil/" . $nombre_img;
      if (move_uploaded_file($file['tmp_name'], $ruta)) {
        $imagen_perfil = $nombre_img;
      } else {
        $errores[] = "Error subiendo la imagen.";
      }
    } else {
      $errores[] = "Formato de imagen no permitido.";
    }
  }

  if (empty($errores)) {
    if ($imagen_perfil) {
      $sql = "UPDATE usuarios SET nombre=?, direccion=?, telefono=?, correo=?, imagen_perfil=? WHERE id_usuario=?";
      $stmt = $conexion->prepare($sql);
      $stmt->bind_param("sssssi", $nombre, $direccion, $telefono, $correo, $imagen_perfil, $id_usuario);
    } else {
      $sql = "UPDATE usuarios SET nombre=?, direccion=?, telefono=?, correo=? WHERE id_usuario=?";
      $stmt = $conexion->prepare($sql);
      $stmt->bind_param("ssssi", $nombre, $direccion, $telefono, $correo, $id_usuario);
    }

    if ($stmt->execute()) {
      $mensaje = "✅ Perfil actualizado correctamente.";
    } else {
      $errores[] = "Error al actualizar el perfil.";
    }
  }
}

// 🔹 CAMBIAR CONTRASEÑA (texto plano)
if (isset($_POST['cambiar_pass'])) {
  $actual = $_POST['actual_pass'];
  $nueva = $_POST['nueva_pass'];
  $confirmar = $_POST['confirmar_pass'];

  if (empty($actual) || empty($nueva) || empty($confirmar)) {
    $errores[] = "Debes llenar todos los campos de contraseña.";
  } elseif ($nueva !== $confirmar) {
    $errores[] = "Las contraseñas nuevas no coinciden.";
  } else {
    // Obtener contraseña actual (⚠️ con ñ)
    $stmt = $conexion->prepare("SELECT contraseña FROM usuarios WHERE id_usuario=?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res && $actual === $res['contraseña']) {
      $up = $conexion->prepare("UPDATE usuarios SET contraseña=? WHERE id_usuario=?");
      $up->bind_param("si", $nueva, $id_usuario);
      if ($up->execute()) {
        $mensaje = "🔐 Contraseña actualizada correctamente.";
      } else {
        $errores[] = "Error al actualizar la contraseña.";
      }
    } else {
      $errores[] = "La contraseña actual no es correcta.";
    }
  }
}

// 🔍 Obtener datos del usuario
$stmt = $conexion->prepare("SELECT nombre, correo, direccion, telefono, imagen_perfil FROM usuarios WHERE id_usuario=?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>

<div class="bg-white rounded-xl shadow-lg p-6 max-w-2xl mx-auto">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-6">👤 Mi Perfil</h3>

  <?php if (!empty($mensaje)): ?>
    <div class="mb-4 p-3 bg-green-50 border border-green-300 text-green-700 rounded"><?php echo $mensaje; ?></div>
  <?php endif; ?>

  <?php if (!empty($errores)): ?>
    <div class="mb-4 p-3 bg-red-50 border border-red-300 text-red-700 rounded">
      <ul class="list-disc pl-5">
        <?php foreach ($errores as $e): ?>
          <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- 🟢 FORMULARIO PERFIL -->
  <form method="POST" enctype="multipart/form-data" class="space-y-4 mb-10">
    <input type="hidden" name="actualizar_perfil" value="1">

    <div class="flex flex-col items-center">
      <?php if (!empty($usuario['imagen_perfil']) && file_exists(__DIR__."/../imagenes_perfil/".$usuario['imagen_perfil'])): ?>
        <img src="imagenes_perfil/<?php echo htmlspecialchars($usuario['imagen_perfil']); ?>" class="w-28 h-28 rounded-full object-cover border-4 border-[#00bcd4]">
      <?php else: ?>
        <div class="w-28 h-28 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-2xl">👤</div>
      <?php endif; ?>
      <label class="mt-2 text-sm text-[#00bcd4] cursor-pointer">
        Cambiar imagen
        <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp,.gif" class="hidden">
      </label>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Nombre</label>
      <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" class="w-full border rounded-lg p-2">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Correo electrónico</label>
      <input type="email" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" class="w-full border rounded-lg p-2">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Teléfono</label>
      <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" class="w-full border rounded-lg p-2">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Dirección</label>
      <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" class="w-full border rounded-lg p-2">
    </div>

    <button class="btn-electric w-full py-2 rounded mt-4">Guardar Cambios</button>
  </form>

  <!-- 🔐 FORMULARIO CAMBIO DE CONTRASEÑA -->
  <div class="border-t pt-6">
    <h4 class="text-xl font-semibold text-[#0b1c35] mb-4">🔒 Cambiar Contraseña</h4>

    <form method="POST" class="space-y-4">
      <input type="hidden" name="cambiar_pass" value="1">

      <div>
        <label class="block text-sm font-medium mb-1">Contraseña actual</label>
        <input type="password" name="actual_pass" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Nueva contraseña</label>
        <input type="password" id="nueva_pass" name="nueva_pass" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Confirmar nueva contraseña</label>
        <input type="password" id="confirmar_pass" name="confirmar_pass" class="w-full border rounded-lg p-2" required>
        <p id="msg-pass" class="text-sm mt-1 font-medium"></p>
      </div>

      <button class="btn-electric w-full py-2 rounded">Actualizar Contraseña</button>
    </form>
  </div>
</div>

<script>
// 🟢 Verificación visual de contraseñas
const nueva = document.getElementById('nueva_pass');
const confirmar = document.getElementById('confirmar_pass');
const msg = document.getElementById('msg-pass');

function verificarPasswords() {
  if (confirmar.value === "") {
    msg.textContent = "";
    return;
  }
  if (nueva.value === confirmar.value) {
    msg.textContent = "✔️ Las contraseñas coinciden";
    msg.className = "text-green-600 font-semibold animate-pulse";
  } else {
    msg.textContent = "❌ Las contraseñas no coinciden";
    msg.className = "text-red-600 font-semibold";
  }
}

nueva.addEventListener('input', verificarPasswords);
confirmar.addEventListener('input', verificarPasswords);
</script>
