<?php
// ✅ Seguridad: solo admin
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../login/login.html");
    exit;
}

include __DIR__ . "/../../config/conexion.php";

// ✅ Eliminar producto (con imagen)
if(isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Buscar imagen asociada
    $q = $conexion->prepare("SELECT imagen FROM productos WHERE id_producto = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $res = $q->get_result()->fetch_assoc();

    if($res && $res['imagen']){
        $ruta_img = __DIR__ . "/../imagenes_productos/" . $res['imagen'];
        if(file_exists($ruta_img)) unlink($ruta_img);
    }

    // Eliminar producto
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        echo "<script>alert('✅ Producto eliminado correctamente'); window.location.href='dashboard_admin.php?view=ver_productos_admin';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Error al eliminar el producto');</script>";
    }
}

// ✅ Consulta de productos con categoría
$sql = "
SELECT p.id_producto, p.marca, p.nombre_producto, p.precio, p.stock, p.imagen, p.descripcion, c.nombre_categoria 
FROM productos p
LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
ORDER BY p.id_producto DESC
";

$resultado = $conexion->query($sql);
?>

<div class="bg-white rounded-xl shadow-lg p-6">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-6">📦 Productos Publicados</h3>

  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <!-- 🧩 Filtros multicriterio -->
    <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4 mb-6">
      <input type="text" id="filtroNombre" placeholder="Buscar por nombre" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
      <input type="text" id="filtroMarca" placeholder="Buscar por marca" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
      <select id="filtroCategoria" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
        <option value="">Todas las categorías</option>
        <?php
        $cats = $conexion->query("SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria ASC");
        while($c = $cats->fetch_assoc()):
        ?>
          <option value="<?php echo htmlspecialchars($c['nombre_categoria']); ?>">
            <?php echo htmlspecialchars($c['nombre_categoria']); ?>
          </option>
        <?php endwhile; ?>
      </select>
      <input type="number" id="precioMin" placeholder="Precio mínimo" step="0.01" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
      <input type="number" id="precioMax" placeholder="Precio máximo" step="0.01" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
    </div>

    <!-- 🧩 Tarjetas de productos -->
    <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6" id="contenedorProductos">
      <?php while($fila = $resultado->fetch_assoc()): ?>
        <div class="producto border rounded-lg shadow hover:shadow-lg transition overflow-hidden"
             data-nombre="<?php echo strtolower($fila['nombre_producto']); ?>"
             data-marca="<?php echo strtolower($fila['marca']); ?>"
             data-categoria="<?php echo strtolower($fila['nombre_categoria']); ?>"
             data-precio="<?php echo $fila['precio']; ?>">

          <?php if(!empty($fila['imagen']) && file_exists(__DIR__."/../imagenes_productos/".$fila['imagen'])): ?>
            <img src="imagenes_productos/<?php echo htmlspecialchars($fila['imagen']); ?>" alt="Imagen del producto" class="w-full h-48 object-cover">
          <?php else: ?>
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">Sin imagen</div>
          <?php endif; ?>

          <div class="p-4">
            <h4 class="text-lg font-bold text-[#0b1c35]"><?php echo htmlspecialchars($fila['nombre_producto']); ?></h4>
            <p class="text-sm text-gray-600 mb-1">Marca: <?php echo htmlspecialchars($fila['marca']); ?></p>
            <p class="text-sm text-gray-600 mb-1">Categoría: <?php echo htmlspecialchars($fila['nombre_categoria'] ?? 'N/A'); ?></p>
            <p class="text-sm text-gray-600 mb-1">Stock: <?php echo htmlspecialchars($fila['stock']); ?></p>
            <p class="text-[#00bcd4] font-semibold text-lg mb-2">$<?php echo number_format($fila['precio'], 2); ?></p>
            <p class="text-sm text-gray-700 mb-3"><?php echo htmlspecialchars($fila['descripcion']); ?></p>

            <div class="flex space-x-2">
              <button 
                onclick="abrirModal('<?php echo $fila['id_producto']; ?>','<?php echo htmlspecialchars($fila['nombre_producto']); ?>','<?php echo $fila['precio']; ?>','<?php echo $fila['stock']; ?>','<?php echo htmlspecialchars($fila['descripcion']); ?>')"
                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">Editar</button>

              <a href="dashboard_admin.php?view=ver_productos_admin&delete_id=<?php echo $fila['id_producto']; ?>"
                 onclick="return confirm('¿Seguro que deseas eliminar este producto?')"
                 class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Eliminar</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-gray-600 text-center">No hay productos publicados todavía.</p>
  <?php endif; ?>
</div>

<!-- 🟢 MODAL PARA EDITAR PRODUCTO -->
<div id="modal-editar" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md relative">
    <button onclick="cerrarModal()" class="absolute top-2 right-3 text-gray-500 hover:text-red-600 text-xl">&times;</button>
    <h3 class="text-2xl font-semibold text-[#0b1c35] mb-4">Editar Producto</h3>
    <form id="form-editar" enctype="multipart/form-data">
      <input type="hidden" name="id_producto" id="edit_id">

      <label class="block mb-1 text-sm">Nombre del Producto</label>
      <input type="text" name="nombre" id="edit_nombre" class="w-full border rounded-lg p-2 mb-3">

      <label class="block mb-1 text-sm">Precio</label>
      <input type="number" step="0.01" name="precio" id="edit_precio" class="w-full border rounded-lg p-2 mb-3">

      <label class="block mb-1 text-sm">Stock</label>
      <input type="number" name="stock" id="edit_stock" class="w-full border rounded-lg p-2 mb-3">

      <label class="block mb-1 text-sm">Descripción</label>
      <textarea name="descripcion" id="edit_descripcion" rows="4" class="w-full border rounded-lg p-2 mb-4"></textarea>

      <button type="submit" class="btn-electric w-full">Guardar Cambios</button>
    </form>
  </div>
</div>

<script>
// 🟢 Modal editar
function abrirModal(id, nombre, precio, stock, descripcion) {
  document.getElementById('modal-editar').classList.remove('hidden');
  document.getElementById('modal-editar').classList.add('flex');
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_nombre').value = nombre;
  document.getElementById('edit_precio').value = precio;
  document.getElementById('edit_stock').value = stock;
  document.getElementById('edit_descripcion').value = descripcion;
}

function cerrarModal() {
  document.getElementById('modal-editar').classList.add('hidden');
  document.getElementById('modal-editar').classList.remove('flex');
}

// 🟢 Guardar cambios con AJAX
document.getElementById('form-editar').addEventListener('submit', async (e) => {
  e.preventDefault();
  const datos = new FormData(e.target);
  const resp = await fetch('vistas/editar_producto_ajax.php', {
    method: 'POST',
    body: datos
  });
  const texto = await resp.text();
  alert(texto);
  cerrarModal();
  location.reload();
});

// 🟢 Filtros multicriterio con rango de precio
const filtroNombre = document.getElementById('filtroNombre');
const filtroMarca = document.getElementById('filtroMarca');
const filtroCategoria = document.getElementById('filtroCategoria');
const precioMin = document.getElementById('precioMin');
const precioMax = document.getElementById('precioMax');
const productos = document.querySelectorAll('.producto');

function aplicarFiltros() {
  const nombre = filtroNombre.value.toLowerCase();
  const marca = filtroMarca.value.toLowerCase();
  const categoria = filtroCategoria.value.toLowerCase();
  const min = parseFloat(precioMin.value) || 0;
  const max = parseFloat(precioMax.value) || Infinity;

  productos.forEach(p => {
    const precio = parseFloat(p.dataset.precio);
    const coincide = (!nombre || p.dataset.nombre.includes(nombre)) &&
                     (!marca || p.dataset.marca.includes(marca)) &&
                     (!categoria || p.dataset.categoria === categoria) &&
                     (precio >= min && precio <= max);
    p.style.display = coincide ? '' : 'none';
  });
}

[filtroNombre, filtroMarca, filtroCategoria, precioMin, precioMax].forEach(i => i.addEventListener('input', aplicarFiltros));
</script>
