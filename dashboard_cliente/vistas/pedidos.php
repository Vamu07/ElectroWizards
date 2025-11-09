<?php
include __DIR__ . "/../../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];

// filtros
$where = "WHERE p.id_usuario = $id_usuario";
$condiciones = [];

if (!empty($_GET['buscar'])) {
    $busqueda = $conexion->real_escape_string($_GET['buscar']);
    $condiciones[] = "(pr.nombre_producto LIKE '%$busqueda%' OR p.id_pedido LIKE '%$busqueda%')";
}

if (!empty($_GET['fecha_desde']) && !empty($_GET['fecha_hasta'])) {
    $desde = $conexion->real_escape_string($_GET['fecha_desde']);
    $hasta = $conexion->real_escape_string($_GET['fecha_hasta']);
    $condiciones[] = "(DATE(p.fecha) BETWEEN '$desde' AND '$hasta')";
}

if (!empty($_GET['precio_min']) && !empty($_GET['precio_max'])) {
    $min = floatval($_GET['precio_min']);
    $max = floatval($_GET['precio_max']);
    $condiciones[] = "(p.total BETWEEN $min AND $max)";
}

if (count($condiciones) > 0) {
    $where .= " AND " . implode(" AND ", $condiciones);
}

// obtener pedidos
$sql = "
SELECT p.id_pedido, p.fecha, p.total
FROM pedidos p
$where
ORDER BY p.fecha DESC
";
$pedidos = $conexion->query($sql);
?>

<div class="bg-white rounded-xl shadow-lg p-6">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-6">📦 Mis Pedidos</h3>

  <!-- 🔍 FILTROS -->
  <form method="GET" class="grid md:grid-cols-5 gap-4 mb-6">
    <input type="hidden" name="view" value="pedidos">
    <div>
      <label class="block text-sm text-gray-700 mb-1">Buscar</label>
      <input type="text" name="buscar" value="<?php echo $_GET['buscar'] ?? ''; ?>" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Desde</label>
      <input type="date" name="fecha_desde" value="<?php echo $_GET['fecha_desde'] ?? ''; ?>" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Hasta</label>
      <input type="date" name="fecha_hasta" value="<?php echo $_GET['fecha_hasta'] ?? ''; ?>" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Precio mín</label>
      <input type="number" step="0.01" name="precio_min" value="<?php echo $_GET['precio_min'] ?? ''; ?>" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
      <label class="block text-sm text-gray-700 mb-1">Precio máx</label>
      <input type="number" step="0.01" name="precio_max" value="<?php echo $_GET['precio_max'] ?? ''; ?>" class="w-full border rounded-lg px-3 py-2">
    </div>

    <div class="md:col-span-5 flex justify-end space-x-2 mt-3">
      <button class="btn-electric px-4 py-2 rounded">Filtrar</button>
      <a href="dashboard_cliente.php?view=pedidos" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 text-sm">Limpiar</a>
    </div>
  </form>

  <?php if ($pedidos && $pedidos->num_rows > 0): ?>
    <?php while($pedido = $pedidos->fetch_assoc()): ?>

      <!-- 🧾 Tarjeta del pedido -->
      <div class="border rounded-lg shadow mb-6">
        <div class="bg-[#0b1c35] text-white p-4 rounded-t-lg flex justify-between items-center">
          <span class="font-semibold">Pedido #<?php echo $pedido['id_pedido']; ?></span>
          <span class="text-sm"><?php echo date("d/m/Y H:i", strtotime($pedido['fecha'])); ?></span>
        </div>

        <div class="p-4">
          <?php
          // obtener detalle del pedido
          $id_pedido = $pedido['id_pedido'];
          $det = $conexion->query("
              SELECT pr.nombre_producto, pr.imagen, d.cantidad, d.precio_unitario
              FROM detalle_pedido d
              JOIN productos pr ON d.id_producto = pr.id_producto
              WHERE d.id_pedido = $id_pedido
          ");
          ?>

          <?php if($det && $det->num_rows > 0): ?>
            <table class="w-full border-collapse mb-3">
              <thead>
                <tr class="bg-gray-100 text-sm">
                  <th class="p-2 text-left">Producto</th>
                  <th class="p-2 text-center">Cantidad</th>
                  <th class="p-2 text-center">Precio</th>
                  <th class="p-2 text-center">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php while($item = $det->fetch_assoc()): ?>
                  <tr class="border-b">
                    <td class="p-2 flex items-center space-x-2">
                      <?php if(!empty($item['imagen'])): ?>
                        <img src="../dashboard_admin/imagenes_productos/<?php echo htmlspecialchars($item['imagen']); ?>" class="w-10 h-10 object-cover rounded">
                      <?php endif; ?>
                      <span><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
                    </td>
                    <td class="text-center"><?php echo $item['cantidad']; ?></td>
                    <td class="text-center">$<?php echo number_format($item['precio_unitario'],2); ?></td>
                    <td class="text-center">$<?php echo number_format($item['cantidad']*$item['precio_unitario'],2); ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          <?php endif; ?>

          <div class="flex justify-between items-center mt-2">
            <span class="text-gray-600 text-sm">Estado: <b class="text-green-600">Completado</b></span>
            <span class="text-[#00bcd4] font-semibold text-lg">Total: $<?php echo number_format($pedido['total'],2); ?></span>
          </div>
        </div>
      </div>

    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-gray-600 text-center">Aún no tienes pedidos registrados 🛍️</p>
  <?php endif; ?>
</div>
