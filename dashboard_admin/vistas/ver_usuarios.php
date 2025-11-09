<?php
// seguridad: solo admin
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../../login/login.html");
    exit;
}

include __DIR__ . "/../../config/conexion.php";

$sql = "SELECT id_usuario, nombre, correo, rol FROM usuarios ORDER BY rol, nombre ASC";
$resultado = $conexion->query($sql);
?>

<div class="bg-white rounded-xl shadow-lg p-6">
  <h3 class="text-2xl font-semibold text-[#0b1c35] mb-6">👥 Lista de Usuarios</h3>

  <div class="flex flex-wrap gap-4 mb-6">
    <input type="text" id="filtroNombre" placeholder="Buscar por nombre" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
    <input type="text" id="filtroCorreo" placeholder="Buscar por correo" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
    <select id="filtroRol" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00e0ff]">
      <option value="">Todos los roles</option>
      <option value="admin">Administrador</option>
      <option value="cliente">Cliente</option>
    </select>
  </div>

  <table class="min-w-full border border-gray-200 rounded-lg">
    <thead class="bg-[#0b1c35] text-white">
      <tr>
        <th class="py-2 px-4 text-left">#</th>
        <th class="py-2 px-4 text-left">Nombre</th>
        <th class="py-2 px-4 text-left">Correo</th>
        <th class="py-2 px-4 text-left">Rol</th>
      </tr>
    </thead>
    <tbody id="tablaUsuarios">
      <?php while($u = $resultado->fetch_assoc()): ?>
        <tr class="border-b hover:bg-gray-50 filaUsuario">
          <td class="py-2 px-4"><?php echo $u['id_usuario']; ?></td>
          <td class="py-2 px-4 nombre"><?php echo htmlspecialchars($u['nombre']); ?></td>
          <td class="py-2 px-4 correo"><?php echo htmlspecialchars($u['correo']); ?></td>
          <td class="py-2 px-4 rol"><?php echo htmlspecialchars($u['rol']); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
const nombreFiltro = document.getElementById('filtroNombre');
const correoFiltro = document.getElementById('filtroCorreo');
const rolFiltro = document.getElementById('filtroRol');
const filas = document.querySelectorAll('.filaUsuario');

function filtrar() {
  const nombre = nombreFiltro.value.toLowerCase();
  const correo = correoFiltro.value.toLowerCase();
  const rol = rolFiltro.value;

  filas.forEach(f => {
    const n = f.querySelector('.nombre').textContent.toLowerCase();
    const c = f.querySelector('.correo').textContent.toLowerCase();
    const r = f.querySelector('.rol').textContent;

    const visible = (!nombre || n.includes(nombre)) &&
                    (!correo || c.includes(correo)) &&
                    (!rol || r === rol);

    f.style.display = visible ? '' : 'none';
  });
}

[nombreFiltro, correoFiltro, rolFiltro].forEach(e => e.addEventListener('input', filtrar));
</script>
