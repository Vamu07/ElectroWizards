<?php
include __DIR__ . "/../../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];

// 🗑️ eliminar producto del carrito
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $conexion->query("DELETE FROM carrito WHERE id_carrito = $id AND id_usuario = $id_usuario");
    echo "<script>alert('Producto eliminado del carrito'); window.location.href='dashboard_cliente.php?view=carrito';</script>";
    exit;
}

// 🛍️ finalizar compra (solo productos seleccionados)
if (isset($_POST['finalizar_compra']) && !empty($_POST['seleccionados'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $seleccionados = $_POST['seleccionados'];

    $ids_placeholders = implode(',', array_fill(0, count($seleccionados), '?'));
    $tipos = str_repeat('i', count($seleccionados));
    $params = $seleccionados;

    // obtener productos seleccionados
    $q = $conexion->prepare("
        SELECT c.id_producto, c.cantidad, p.precio, p.stock
        FROM carrito c
        INNER JOIN productos p ON c.id_producto = p.id_producto
        WHERE c.id_usuario = ? AND c.id_carrito IN ($ids_placeholders)
    ");

    $bind_types = "i" . $tipos;
    $bind_values = array_merge([$id_usuario], $params);
    $q->bind_param($bind_types, ...$bind_values);
    $q->execute();
    $res = $q->get_result();

    if ($res->num_rows > 0) {
        $total = 0;
        $detalles = [];

        while ($fila = $res->fetch_assoc()) {
            // Verificar stock
            if ($fila['cantidad'] > $fila['stock']) {
                echo "<script>alert('⚠️ No hay suficiente stock para uno de los productos seleccionados');</script>";
                exit;
            }

            $total += $fila['precio'] * $fila['cantidad'];
            $detalles[] = $fila;
        }

        // Registrar pedido
        $stmt = $conexion->prepare("INSERT INTO pedidos (id_usuario, total, fecha) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $id_usuario, $total);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;

        // Insertar detalles y actualizar stock
        $detalleStmt = $conexion->prepare("
            INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
            VALUES (?, ?, ?, ?)
        ");
        $updateStock = $conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
        foreach ($detalles as $d) {
            $detalleStmt->bind_param("iiid", $id_pedido, $d['id_producto'], $d['cantidad'], $d['precio']);
            $detalleStmt->execute();

            // Actualizar stock
            $updateStock->bind_param("ii", $d['cantidad'], $d['id_producto']);
            $updateStock->execute();
        }

        // Eliminar solo los productos comprados
        $del = $conexion->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_carrito IN ($ids_placeholders)");
        $bind_values = array_merge([$id_usuario], $params);
        $del->bind_param($bind_types, ...$bind_values);
        $del->execute();

        echo "<script>alert('✅ Compra realizada con éxito'); window.location.href='dashboard_cliente.php?view=pedidos';</script>";
        exit;
    } else {
        echo "<script>alert('⚠️ No hay productos seleccionados.');</script>";
    }
}

// obtener productos del carrito
$sql = "
SELECT c.id_carrito, p.nombre_producto, p.precio, p.imagen, c.cantidad, (p.precio*c.cantidad) AS subtotal
FROM carrito c
JOIN productos p ON c.id_producto = p.id_producto
WHERE c.id_usuario = $id_usuario
";
$res = $conexion->query($sql);
?>

<div class="bg-white rounded-xl shadow-lg p-6">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-6">🛒 Mi Carrito</h3>

  <?php if($res && $res->num_rows > 0): ?>
  <form method="POST">
    <table class="w-full border-collapse mb-6">
      <thead>
        <tr class="bg-[#0b1c35] text-white">
          <th class="p-3 text-center">Seleccionar</th>
          <th class="p-3 text-left">Producto</th>
          <th class="p-3">Precio</th>
          <th class="p-3">Cantidad</th>
          <th class="p-3">Subtotal</th>
          <th class="p-3">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while($item = $res->fetch_assoc()): ?>
        <tr class="border-b hover:bg-gray-50 transition">
          <td class="p-3 text-center">
            <input type="checkbox" name="seleccionados[]" value="<?php echo $item['id_carrito']; ?>" class="seleccion-producto w-5 h-5">
          </td>
          <td class="p-3 flex items-center space-x-3">
            <?php if(!empty($item['imagen'])): ?>
              <img src="../dashboard_admin/imagenes_productos/<?php echo htmlspecialchars($item['imagen']); ?>" class="w-12 h-12 object-cover rounded">
            <?php endif; ?>
            <span><?php echo htmlspecialchars($item['nombre_producto']); ?></span>
          </td>
          <td class="p-3 text-center">$<?php echo number_format($item['precio'],2); ?></td>
          <td class="p-3 text-center"><?php echo $item['cantidad']; ?></td>
          <td class="p-3 text-center subtotal" data-subtotal="<?php echo $item['subtotal']; ?>">
            $<?php echo number_format($item['subtotal'],2); ?>
          </td>
          <td class="p-3 text-center">
            <a href="dashboard_cliente.php?view=carrito&delete_id=<?php echo $item['id_carrito']; ?>" 
               onclick="return confirm('¿Eliminar este producto del carrito?')"
               class="btn-electric px-4 py-2 rounded text-sm">
               Eliminar
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="text-right">
      <h4 class="text-xl font-semibold mb-4">
        Total Seleccionado: <span id="total-seleccionado" class="text-[#00bcd4]">$0.00</span>
      </h4>
      <input type="hidden" name="finalizar_compra" value="1">
      <button class="btn-electric px-6 py-2 rounded">Finalizar Compra</button>
    </div>
  </form>

  <?php else: ?>
    <p class="text-gray-600 text-center">Tu carrito está vacío 🛍️</p>
  <?php endif; ?>
</div>

<script>
// 🧮 Actualizar total en tiempo real
const checkboxes = document.querySelectorAll('.seleccion-producto');
const totalEl = document.getElementById('total-seleccionado');

checkboxes.forEach(chk => {
  chk.addEventListener('change', calcularTotal);
});

function calcularTotal() {
  let total = 0;
  document.querySelectorAll('.seleccion-producto:checked').forEach(chk => {
    const fila = chk.closest('tr');
    const subtotal = parseFloat(fila.querySelector('.subtotal').dataset.subtotal);
    total += subtotal;
  });
  totalEl.textContent = '$' + total.toLocaleString('es-CO', { minimumFractionDigits: 2 });
}
</script>
