<?php
// seguridad: solo admin
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../login/login.html");
    exit;
}

include __DIR__ . "/../../config/conexion.php";

$errores = [];
$success = "";

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $marca = trim($_POST['marca'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Validaciones básicas
    if ($marca === '') $errores[] = "La marca es obligatoria.";
    if ($nombre === '') $errores[] = "El nombre del producto es obligatorio.";
    if ($precio === '' || !is_numeric($precio)) $errores[] = "Precio inválido.";
    if ($stock === '' || !ctype_digit($stock)) $errores[] = "Stock inválido.";
    if ($id_categoria === '') $errores[] = "Selecciona una categoría.";
    if ($descripcion === '') $errores[] = "La descripción es obligatoria.";

    // Manejo de imagen
    $imagen_db = null;
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE){
        $file = $_FILES['imagen'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];

        if(!in_array($ext, $allowed)){
            $errores[] = "Formato no permitido.";
        } else {
            $nombre_unico = uniqid("prod_").".".$ext;
            $destino_dir = __DIR__."/../imagenes_productos/";
            if(!is_dir($destino_dir)) mkdir($destino_dir,0755,true);
            $destino = $destino_dir.$nombre_unico;

            if(move_uploaded_file($file['tmp_name'], $destino)){
                $imagen_db = $nombre_unico;
            } else {
                $errores[] = "Error al subir la imagen.";
            }
        }
    }

    if(empty($errores)){
        $id_usuario = $_SESSION['id_usuario'];

        $sql = "INSERT INTO productos (marca, nombre_producto, precio, stock, id_categoria, imagen, descripcion, id_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        if(!$stmt){
            $errores[] = "Error preparando consulta: ".$conexion->error;
        } else {
            $precio_val = floatval($precio);
            $stock_val = intval($stock);
            $stmt->bind_param("ssdisssi", $marca, $nombre, $precio_val, $stock_val, $id_categoria, $imagen_db, $descripcion, $id_usuario);

            if($stmt->execute()){
                // ✅ Redirigir correctamente dentro del mismo dashboard
                echo "<script>
                    alert('✅ Producto agregado con éxito');
                    window.location.href='dashboard_admin.php?view=ver_productos_admin';
                </script>";
                exit;
            } else {
                $errores[] = "Error ejecutando insert: ".$stmt->error;
            }
            $stmt->close();
        }
    }
}

// Traer categorías dinámicamente
$cats = [];
$q = $conexion->query("SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria ASC");
while($r = $q->fetch_assoc()){ $cats[] = $r; }

?>

<div class="bg-white rounded-xl shadow-lg p-6">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-4">Agregar Producto</h3>

  <?php if (!empty($errores)): ?>
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
      <ul class="list-disc pl-5">
        <?php foreach($errores as $e): ?>
          <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Marca</label>
        <input type="text" name="marca" required class="w-full px-4 py-2 rounded-lg border" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Nombre del producto</label>
        <input type="text" name="nombre" required class="w-full px-4 py-2 rounded-lg border" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Precio</label>
        <input type="number" step="0.01" name="precio" required class="w-full px-4 py-2 rounded-lg border" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Stock</label>
        <input type="number" name="stock" required class="w-full px-4 py-2 rounded-lg border" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Categoría</label>
        <select name="id_categoria" required class="w-full px-4 py-2 rounded-lg border">
          <option value="">-- Selecciona --</option>
          <?php foreach($cats as $c): ?>
            <option value="<?php echo $c['id_categoria']; ?>"><?php echo $c['nombre_categoria']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Imagen</label>
        <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp,.gif" />
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Descripción</label>
      <textarea name="descripcion" rows="4" class="w-full px-4 py-2 rounded-lg border"></textarea>
    </div>

    <button class="btn-electric px-5 py-2 rounded">Guardar Producto</button>
  </form>
</div>
